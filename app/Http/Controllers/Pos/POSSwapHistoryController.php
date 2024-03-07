<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PosFrontend\SwapHistory;
use App\Models\Token\TokenInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Submaster\AddOnMovementHistory;
use App\Http\Controllers\Pos\POSDashboardController;


class POSSwapHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $is_missing_eod_or_sod = (new POSDashboardController)->check_sod_or_eod();
        if ($is_missing_eod_or_sod) {
            return $is_missing_eod_or_sod;
        }   
        $data = [];
        
        $swap_histories = SwapHistory::leftjoin('cms_users', 'cms_users.id', 'swap_histories.created_by')
        ->leftjoin('cms_users as updated_by', 'updated_by.id', 'swap_histories.updated_by')
        ->leftjoin('locations', 'locations.id', 'swap_histories.locations_id')
        ->leftjoin('token_action_types', 'token_action_types.id', 'swap_histories.type_id')
        ->leftjoin('mode_of_payments', 'mode_of_payments.id', 'swap_histories.mode_of_payments_id')
        ->select('swap_histories.id', 'swap_histories.reference_number', 'swap_histories.total_value', 'swap_histories.token_value', 'token_action_types.description as type_id', 'mode_of_payments.payment_description as mod_description', 'locations.location_name', 'cms_users.name as created_by', 'swap_histories.created_at','swap_histories.updated_at', 'updated_by.name as updated_by', 'swap_histories.status', 'swap_histories.payment_reference')
        ->orderBy('swap_histories.created_at', 'desc');
    
        if (Auth::user()->id_cms_privileges != 1) {
            $swap_histories->where('swap_histories.locations_id', Auth::user()->location_id);
        }

        
        $searchTerm = request('search');
        $data['swap_histories'] = $swap_histories->filter(['search' => $searchTerm])->paginate(10);
        $data['swap_histories']->appends(['search' => $searchTerm]);
        return view('pos-frontend.views.swap-history', $data);
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
        //
    }


    public function getDetails($id) {
        $data = [];
        $data['swap_histories'] = SwapHistory::where('swap_histories.id', $id)->leftjoin('mode_of_payments', 'swap_histories.mode_of_payments_id','mode_of_payments.id')->first();
        $data['addons'] = DB::table('addons_history')
        ->where('token_swap_id', $id)
        ->where('add_ons.locations_id', Auth::user()->location_id)
        ->leftjoin('add_ons', 'add_ons.digits_code', 'addons_history.digits_code')
        ->select('add_ons.description', 'addons_history.qty' )->get()->toArray();

        return json_encode($data);
        
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $data = [];
        $data['swap_histories'] = SwapHistory::find($id);
        $swapData = $data['swap_histories'];
        
        $data['mod_description'] = DB::table('mode_of_payments')->where('id', $swapData->mode_of_payments_id)->select('payment_description')->first();
        $data['location_name'] = DB::table('locations')->where('id', $swapData->locations_id)->select('location_name')->first();
        $data['addons'] = DB::table('addons_history')->where('token_swap_id', $id)->where('add_ons.locations_id', Auth::user()->location_id)->leftjoin('add_ons', 'add_ons.digits_code', 'addons_history.digits_code')->select('add_ons.description', 'addons_history.qty' )->get()->toArray();
        $data['created_by'] = DB::table('cms_users')->where('id', $swapData->created_by)->select('name')->first();
        $data['updated_by'] = DB::table('cms_users')->where('id', $swapData->updated_by)->select('name')->first();
        return view('pos-frontend.views.show-swap-history', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $check_date = DB::table('swap_histories')->where('id', $id)->value('created_at');
        if (date('Y-m-d', strtotime($check_date)) != date('Y-m-d'))
        {
            return json_encode(['message'=>'not allowed to void' ]);
        }
        $histories_id = DB::table('swap_histories')->where('id', $id)->value('token_value');
        $histories_status = DB::table('swap_histories')->where('id', $id)->value('status');

        if ($histories_status == 'VOID') {
            return json_encode(['message'=>'error' ]);
        }
    
        
        $histories_ref_number = DB::table('swap_histories')->where('id', $id)->value('reference_number');
        $items = DB::table('add_on_movement_histories')->where('reference_number', $histories_ref_number)->select('digits_code', DB::raw('ABS(qty) as qty'))->get();
        foreach ($items as $key => $data) {
            DB::table('add_ons')->where('digits_code', $data->digits_code)->where('locations_id',Auth::user()->location_id)->increment('qty', $data->qty);
        }
        DB::table('add_on_movement_histories')->where('reference_number', $histories_ref_number)->update(['status' => "VOID", 'updated_by' => Auth::user()->id, 'updated_at' =>  date('Y-m-d H:i:s')]);
        // dd($items);
        // $inserMovementHistories = [];
        // $inserMovementHistoriesContainer = [];
        // $addOnTypeId = DB::table('add_on_action_types')->select('id')->where('description', 'DR')->first()->id;

        // foreach($items as $key => $val){
        //         $inserMovementHistoriesContainer['reference_number'] =  $histories_ref_number;
        //         $inserMovementHistoriesContainer['digits_code'] = $val->digits_code;
        //         $inserMovementHistoriesContainer['add_on_action_types_id'] = $addOnTypeId;
        //         $inserMovementHistoriesContainer['locations_id'] = Auth::user()->location_id;
        //         $inserMovementHistoriesContainer['qty'] = $val->qty;
        //         $inserMovementHistoriesContainer['created_by'] = Auth::user()->id;
        //         $inserMovementHistoriesContainer['created_at'] = date('Y-m-d H:i:s');
        //         $inserMovementHistories[] = $inserMovementHistoriesContainer;
        //     }
        //     AddOnMovementHistory::insert($inserMovementHistories);
                
        $token_inventory = DB::table('token_inventories')->where('locations_id', Auth::user()->location_id)->first();
        $token_inventory_qty = $token_inventory->qty;
        $total_qty = $token_inventory_qty + $histories_id;
        DB::table('swap_histories')->where('id', $id)->update(['status' => "VOID", 'updated_by' => Auth::user()->id, 'updated_at' =>  date('Y-m-d H:i:s')]);
        
        TokenInventory::updateOrInsert(['locations_id' => Auth::user()->location_id],['qty' => $total_qty]);

            
            return json_encode(['message'=>'success', 'swap_history' => SwapHistory::find($id), 'histories_id' => $histories_status ]);
        
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