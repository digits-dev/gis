<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submaster\ModeOfPayment;
use App\Models\Submaster\TokenConversion;
use App\Models\Submaster\Preset;
use App\Models\Submaster\AddOnMovementHistory;
use App\Models\Token\TokenInventory;
use App\Models\PosFrontend\AddonsHistory;
use App\Models\PosFrontend\POSTokenSwap;
use App\Models\PosFrontend\SwapHistory;
use App\Models\Capsule\InventoryCapsule;
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
        dd($request->all());
        $tokenSwapCount = DB::table('swap_histories')->max('id');
        $headerCount = str_pad($tokenSwapCount + 1, 8, "0", STR_PAD_LEFT);
        $refNumber = 'TS-'.$headerCount;
        
        $token_inventory = TokenInventory::where('locations_id', Auth::user()->location_id);
        $token_inventory_qty = $token_inventory->first()->qty;
        $total_qty = $token_inventory_qty - intval(str_replace(',', '',$request->token_value));

        if ($token_inventory_qty >= intval(str_replace(',', '',$request->token_value))) {
            
            TokenInventory::updateOrInsert(['locations_id' => Auth::user()->location_id],['qty' => $total_qty]);
            $current_cash_value = DB::table('token_conversions')->value('current_cash_value');
            $typeId = DB::table('token_action_types')->select('id')->where('description', 'Swap')->first()->id;
            
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
                'description' => $suggestion->item_description
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