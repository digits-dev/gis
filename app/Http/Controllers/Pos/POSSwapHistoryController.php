<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PosFrontend\SwapHistory;
use App\Models\Token\TokenInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Pos\POSDashboardController;


class POSSwapHistoryController extends Controller
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
        
        $query = SwapHistory::leftjoin('cms_users', 'cms_users.id', 'swap_histories.created_by')
        ->leftjoin('locations', 'locations.id', 'swap_histories.locations_id')
        ->leftjoin('token_action_types', 'token_action_types.id', 'swap_histories.type_id')
        ->leftjoin('mode_of_payments', 'mode_of_payments.id', 'swap_histories.mode_of_payments_id')
        ->select('swap_histories.id', 'swap_histories.reference_number', 'swap_histories.total_value', 'swap_histories.token_value', 'token_action_types.description as type_id', 'mode_of_payments.payment_description as mod_description', 'locations.location_name', 'cms_users.name as created_by', 'swap_histories.created_at', 'swap_histories.status', 'swap_histories.payment_reference')
        ->orderBy('swap_histories.created_at', 'desc');
    
        if (Auth::user()->id_cms_privileges != 1) {
            $query->where('swap_histories.locations_id', Auth::user()->location_id);
        }
        
        $data['swap_histories'] = $query->paginate(10);

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
        $data['created_by'] = DB::table('cms_users')->where('id', $swapData->created_by)->select('name')->first();
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
        
        $token_inventory = DB::table('token_inventories')->where('locations_id', Auth::user()->location_id)->first();
        $token_inventory_qty = $token_inventory->qty;
        $total_qty = $token_inventory_qty + $histories_id;
        DB::table('swap_histories')->where('id', $id)->update(['status' => "VOID"]);
        
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