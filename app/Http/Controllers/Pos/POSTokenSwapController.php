<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submaster\ModeOfPayment;
use App\Models\Submaster\TokenConversion;
use App\Models\Submaster\Preset;
use App\Models\Submaster\Item;
use App\Models\Submaster\AddOnMovementHistory;
use App\Models\Token\TokenInventory;
use App\Models\Capsule\HistoryCapsule;
	use App\Models\Capsule\InventoryCapsule;
	use App\Models\Capsule\InventoryCapsuleLine;
use App\Models\Submaster\CapsuleActionType;
use App\Models\PosFrontend\AddonsHistory;
use App\Models\PosFrontend\POSTokenSwap;
use App\Models\PosFrontend\SwapHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Pos\POSDashboardController;



class POSTokenSwapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $is_missing_eod_or_sod = (new POSDashboardController)->check_sod_or_eod();
        if ($is_missing_eod_or_sod) {
            return $is_missing_eod_or_sod;
        } 

        $data = [];
        $data['tokens'] = Preset::where('status', 'ACTIVE')->where('preset_type', 'token')->select('value')->get();
        $data['paymayas'] = Preset::where('status', 'ACTIVE')->where('preset_type', 'paymaya')->select('value')->get();
        $data['mode_of_payments'] = ModeOfPayment::where('status', 'ACTIVE')->get();
        $data['addons'] = DB::table('add_ons')->where('qty', '>', '0')->where('status', 'ACTIVE')->where('locations_id', Auth::user()->location_id)->get();
        $data['cash_value'] = TokenConversion::first()->current_cash_value;
        $data['inventory_qty'] = TokenInventory::where('locations_id', Auth::user()->location_id)->value('qty');

        return view('pos-frontend.views.token-swap',$data);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tokenSwapCount = DB::table('swap_histories')->max('id');
        $headerCount = str_pad($tokenSwapCount + 1, 8, "0", STR_PAD_LEFT);
        $refNumber = 'TS-'.$headerCount;
        
        $current_cash_value = DB::table('token_conversions')->value('current_cash_value');
        $typeId = DB::table('token_action_types')->select('id')->where('description', 'Swap')->first()->id;

        $token_inventory = TokenInventory::where('locations_id', Auth::user()->location_id);
        $token_inventory_qty = $token_inventory->first()->qty;
        $total_qty = $token_inventory_qty - intval(str_replace(',', '',$request->token_value));

        if ($token_inventory_qty >= intval(str_replace(',', '',$request->token_value))) {

            TokenInventory::updateOrInsert(['locations_id' => Auth::user()->location_id],['qty' => $total_qty]);

            if($request->mode_of_payment == 32) {
            
            $commonData = [
                'reference_number' => $refNumber,
                'cash_value' => intval(str_replace(',', '', $request->cash_value)),
                'change_value' => intval(str_replace(',', '', $request->change_value)),
                'current_cash_value' => $current_cash_value,
                'type_id' => $typeId,
                'locations_id' => Auth::user()->location_id,
                'mode_of_payments_id' => $request->mode_of_payment,
                'payment_reference' => $request->amount_received ?? $request->payment_reference,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            //  negative total_value
            DB::table('swap_histories')->insertGetId(array_merge($commonData, [
                'total_value' => intval(str_replace(',', '', $request->total_value)) * -1,
            ]));
            
            // positive total_value
            DB::table('swap_histories')->insertGetId(array_merge($commonData, [
                'total_value' => intval(str_replace(',', '', $request->total_value)),
                'token_value' => intval(str_replace(',', '', $request->token_value)),
            ]));

            $action_type = CapsuleActionType::where(DB::raw('UPPER(description)'), '=', 'DEFECTIVE')->first();

            $sub_locations_id = DB::table('sub_locations')
            ->where('location_id', Auth::user()->location_id)
            ->where('description', 'STOCK ROOM(D)')
            ->pluck('id')
            ->first();

            foreach($request->jan_number as $key => $jan){
                
                $inputted_qty_one = $request->jan_qty[$key]; 
				$digits_code2 = Item::where('digits_code', $jan)->pluck('digits_code2')->first();
				//updating inventory qty of from machine 1 to 0
				$is_existing_capsule= InventoryCapsuleLine::leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
					->where('ic.locations_id', Auth::user()->location_id)
                    ->where('inventory_capsule_lines.sub_locations_id',  $sub_locations_id )
					->where('ic.item_code', $digits_code2)
					->exists();

                
                HistoryCapsule::insert([
                    'reference_number' => $refNumber,
                    'item_code' => $digits_code2,
                    'capsule_action_types_id' => $action_type->id,
                    'locations_id' => Auth::user()->location_id,
                    'to_sub_locations_id' => $sub_locations_id,
                    'qty' => $inputted_qty_one,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id
                ]);
        
				if ($is_existing_capsule) {
					// updating the qty if existing
					InventoryCapsuleLine::leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
						->where('ic.locations_id', Auth::user()->location_id)
                        ->where('inventory_capsule_lines.sub_locations_id',  $sub_locations_id )
						->where('ic.item_code', $digits_code2)
						->update([
							'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty + $inputted_qty_one"),
							'inventory_capsule_lines.updated_by' => Auth::user()->id,
							'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
						]);

				} else {
					$inventory_capsules_id = InventoryCapsule::where([
						'item_code' => $digits_code2,
						'locations_id' => Auth::user()->location_id,
					])->pluck('id')->first();

					if (!$inventory_capsules_id) {
						// inserting a new entry for inventory capsule if not existing
						$inventory_capsules_id = InventoryCapsule::insertGetId([
							'item_code'    => $digits_code2,
							'locations_id' => Auth::user()->location_id,
							'created_by'   => Auth::user()->id,
							'created_at'   => date('Y-m-d H:i:s')
						]);
					}

					// inserting a new entry for inventory capsule lines
					InventoryCapsuleLine::insert([
						'inventory_capsules_id' => $inventory_capsules_id,
						'sub_locations_id'       => $sub_locations_id,
						'qty'                   => $inputted_qty_one,
						'created_by'            => Auth::user()->id,
						'created_at'            => date('Y-m-d H:i:s')
					]);
				}

            }
          }else {
            
                $swapId = DB::table('swap_histories')->insertGetId([
                    'reference_number' =>  $refNumber,
                    'cash_value' => intval(str_replace(',', '',$request->cash_value)),
                    'token_value' => intval(str_replace(',', '',$request->token_value)),
                    'total_value' => intval(str_replace(',', '',$request->total_value)),
                    'change_value' => intval(str_replace(',', '',$request->change_value)),
                    'current_cash_value' => $current_cash_value,
                    'type_id' => $typeId,
                    'locations_id' => Auth::user()->location_id,
                    'mode_of_payments_id' => $request->mode_of_payment,
                    'payment_reference' => $request->amount_received ?? $request->payment_reference,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
    
                $inserDataLines = [];
                $inserDataLinesContainer = [];
                if($request->description) {
                    foreach($request->description as $key => $val){
                        $description = explode("-",$val)[1];
                        $inserDataLinesContainer['token_swap_id'] = $swapId;
                        $inserDataLinesContainer['digits_code'] = $request->digits_code[$key];
                        $inserDataLinesContainer['qty'] = $request->quantity[$key];
                        $inserDataLinesContainer['created_by'] = Auth::user()->id;
                        $inserDataLinesContainer['created_at'] = date('Y-m-d H:i:s');
                        $inserDataLines[] = $inserDataLinesContainer;
                    }
                    // dd($inserDataLines);
                    AddonsHistory::insert($inserDataLines);
        
                    foreach($inserDataLines as $key => $data) {
                        DB::table('add_ons')->where('digits_code', $data['digits_code'])->where('locations_id',Auth::user()->location_id)->decrement('qty',(int)$data['qty']);
                    } 
                }
    
                $inserMovementHistories = [];
                $inserMovementHistoriesContainer = [];
                $addOnTypeId = DB::table('add_on_action_types')->select('id')->where('description', 'Swap')->first()->id;
                if($request->description) {
                    foreach($request->description as $key => $val){
                        $description = explode("-",$val)[1];
                        $inserMovementHistoriesContainer['reference_number'] =  $refNumber;
                        $inserMovementHistoriesContainer['digits_code'] = $request->digits_code[$key];
                        $inserMovementHistoriesContainer['add_on_action_types_id'] = $addOnTypeId;
                        $inserMovementHistoriesContainer['locations_id'] = Auth::user()->location_id;
                        $inserMovementHistoriesContainer['qty'] = -$request->quantity[$key];
                        $inserMovementHistoriesContainer['created_by'] = Auth::user()->id;
                        $inserMovementHistoriesContainer['created_at'] = date('Y-m-d H:i:s');
                        $inserMovementHistories[] = $inserMovementHistoriesContainer;
                    }
                    
                    AddOnMovementHistory::insert($inserMovementHistories);
        
                }
            }
        }

            return json_encode(['message'=>'success', 'reference_number'=> $refNumber]);
        
    }
    public function suggestJanNumber(Request $request)
    {
        $term = $request->input('term');
        $store_location = Auth::user()->location_id;
        $suggestions = InventoryCapsule::where('locations_id', $store_location)
            ->leftJoin('items', 'items.digits_code2', 'inventory_capsules.item_code')
            ->select('inventory_capsules.*',
                'items.*',
                'items.digits_code',
                'items.item_description'
            )
            ->where('items.digits_code', 'like', '%' . $term . '%')
            ->get();

        $formattedSuggestions = [];
        foreach ($suggestions as $suggestion) {
            $formattedSuggestions[] = [
                'id' => $suggestion->id,
                'text' => $suggestion->digits_code, // Change this to whatever property you want to display
                'description' => $suggestion->item_description,
                'no_of_tokens' => $suggestion->no_of_tokens
            ];
        }

        return response()->json($formattedSuggestions);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}