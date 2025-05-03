<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Capsule\CapsuleSales;
use App\Models\Capsule\HistoryCapsule;
use App\Models\Capsule\InventoryCapsule;
use App\Models\Capsule\InventoryCapsuleLine;
use App\Models\ItemPos;
use App\Models\ItemPosLines;
use App\Models\Submaster\ModeOfPayment;
use App\Models\Submaster\SalesType;
use App\Models\Submaster\SubLocations;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class POSItemPointofSaleController extends Controller
{
    public function index()
    {
        $is_missing_eod_or_sod = (new POSDashboardController)->check_sod_or_eod();
        if ($is_missing_eod_or_sod) {
            return $is_missing_eod_or_sod;
        } 
   
        $data = [];
        $data['mode_of_payments'] = ModeOfPayment::whereNull('type')->where('status', 'ACTIVE')->get();
 
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
        }])->where('locations_id', $location_id)->first();
        
        if ($item) {
            if (is_null($item->current_srp) && $item->current_srp < 0){
                return response()->json([
                    'status' => 'item has no current srp',
                ]);
            }

            if ($item->item_stockroom_data->qty < intval($request->input('scan_qty'))) {
                return response()->json([
                    'status' => 'insufficient stock',
                ]);
            }

            return response()->json([
                'status' => 'found',
                'data'=> $item,
                'item' => [
                    'name' => $item->item->item_description,
                    'code' => $item->item->digits_code,
                    'digits_code' => $item->item->digits_code2,
                    'price' => $item->item->current_srp,
                    'quantity' => intval($request->input('scan_qty')),
                    'item_stock_qty' => $item->item_stockroom_data->qty,
                ],
            ]);
            
        } 
        else {
            return response()->json([
                'status' => 'not_found'
            ]);
        }
    }

    public function submitTransaction(Request $request){


        $items = $request->input('items');
        $mode_of_payment = $request->input('mode_of_payment');
        $total = $request->input('total');
        $amount_entered = $request->input('amount_entered');
        $change = $request->input('change');
        $reference_number = $request->input('reference_number');
        $mode_of_payment_text = $request->input('mode_of_payment_text');
        

        $transactionReferenceNumber = 'PS-' . str_pad(ItemPos::count() + 1, 8, '0', STR_PAD_LEFT);
        
        $location_id = auth()->user()->location_id;
        $sub_location = SubLocations::where('location_id', $location_id)->where('description', 'STOCK ROOM')->first();
        $capsule_type_id = DB::table('capsule_action_types')->where('status', 'ACTIVE')->where('id', 16)->value('id');
        $sales_types_id = SalesType::where('id', 8)
        ->where('status', 'ACTIVE')
        ->pluck('id')
        ->first();

        try {
            DB::beginTransaction();

            $insertData = [
                'reference_number' => $transactionReferenceNumber,
                'total_value' => $total,
                'change_value' => $change,
                'mode_of_payments_id' => $mode_of_payment,
                'locations_id' => $location_id,
                'status' => 'POSTED',
            ];
            
            if ($mode_of_payment == 1) {
                $insertData['amount_value'] = $amount_entered;
            } else {
                $insertData['payment_reference'] = $reference_number;
            }
            
            $itemPos = ItemPos::create($insertData);

            foreach($items as $item){
                ItemPosLines::create([
                    'item_pos_id' => $itemPos->id,
                    'digits_code' => $item['digits_code'],
                    'jan_number' => $item['code'],
                    'item_description' => $item['name'],
                    'qty' => $item['quantity'],
                    'current_srp' => $item['price'],
                    'total_price' => $item['subtotal'],
                    'locations_id' => $location_id,
                    'status' => 'POSTED',
                ]);

                HistoryCapsule::insert([
                    'reference_number' => $itemPos->reference_number,
                    'item_code' => $item['digits_code'],
                    'capsule_action_types_id' => $capsule_type_id,
                    'locations_id' => $location_id,
                    'qty' => (int)$item['quantity'] * -1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id
                ]);
    
                InventoryCapsuleLine::leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
                ->where('ic.locations_id', $location_id)
                ->where('inventory_capsule_lines.sub_locations_id', $sub_location->id)
                ->where('ic.item_code',  $item['digits_code'])
                ->update([
                    'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty - " . (int)$item['quantity']),
                    'inventory_capsule_lines.updated_by' => Auth::user()->id,
                    'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
                ]);
    
                CapsuleSales::insert([
                    'reference_number' => $itemPos->reference_number,
                    'item_code' => $item['code'],
                    'locations_id' => $location_id,
                    'qty' => $item['quantity'],
                    'sales_type_id' => $sales_types_id,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'reference_number' => $transactionReferenceNumber,
                'payment_reference' => $reference_number,
                'mode_of_payment' => $mode_of_payment_text,
                'total' => $total,
                'amount_entered' => $amount_entered,
                'change' => $change

            ]);
        }

        catch (\Exception $e) {
      
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);

        }
        
    }
    

}
