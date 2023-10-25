<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PosFrontend\SwapHistory;
use App\Models\Token\TokenInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class POSSwapHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (Auth::user()->id == 1){
            $data['swap_histories'] = SwapHistory::get();
        }else {
            $data['swap_histories'] = SwapHistory::where('locations_id',Auth::user()->location_id)->get();
        }

        foreach ($data['swap_histories'] as $swap_history) {
            $user = DB::table('cms_users')
                ->where('id', $swap_history->created_by)
                ->select('name', 'location_id')
                ->first();

            $location = DB::table('locations')
                ->where('id', $user->location_id)
                ->select('location_name')
                ->first();

            $typeId = DB::table('token_action_types')
                ->where('id', $swap_history->type_id)
                ->select('description')
                ->first();

            $swap_history->created_by = $user->name;
            $swap_history->location_name = $location->location_name;
            $swap_history->type_id = $typeId->description;
         
        }
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