<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Capsule\InventoryCapsule;
use App\Models\Submaster\SubLocations;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Http\Request;

class POSItemPointofSaleController extends Controller
{
    public function index()
    {
        $is_missing_eod_or_sod = (new POSDashboardController)->check_sod_or_eod();
        if ($is_missing_eod_or_sod) {
            return $is_missing_eod_or_sod;
        } 

        $data = [];
 
        return view('pos-frontend.views.item-pos',$data);
    }

    public function check(Request $request)
    {
        $code = $request->input('jan_code');
        $location_id = auth()->user()->location_id;
        $sub_location = SubLocations::where('location_id', $location_id)->where('description', 'STOCK ROOM')->first();

        $item = InventoryCapsule::whereHas('item', function ($query) use ($code) {
            $query->where('digits_code', $code);
        })->with(['item', 'item_stockroom_data' => function ($query) use ($sub_location) {
        $query->where('sub_locations_id', $sub_location->id);
        }])->where('locations_id', $location_id)->first();;
        
        if ($item) {
            return response()->json([
                'status' => 'found',
                'data'=> $item,
                'item' => [
                    'name' => $item->item->item_description,
                    'code' => $item->item->digits_code,
                    'price' => $item->item->current_srp,
                    'quantity' => intval($request->input('scan_qty')),
                ],
            ]);
        } else {
            return response()->json([
                'status' => 'not_found'
            ]);
        }
    }
    

}
