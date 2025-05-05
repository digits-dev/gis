<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Capsule\CapsuleSales;
use App\Models\Capsule\HistoryCapsule;
use App\Models\Capsule\InventoryCapsule;
use App\Models\Capsule\InventoryCapsuleLine;
use App\Models\ItemPos;
use App\Models\ItemPosLines;
use App\Models\ItemPosReservedQuantity;
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

        $location_id = auth()->user()->location_id;
        $user_id = auth()->user()->id;

        ItemPosReservedQuantity::where('locations_id', $location_id)
        ->where('user_id', $user_id)
        ->delete();
   
        $data = [];
        $data['mode_of_payments'] = ModeOfPayment::whereNull('type')->where('status', 'ACTIVE')->get();
 
        return view('pos-frontend.views.item-pos',$data);
    }

    public function check(Request $request)
    {
        $code = $request->input('jan_code');
        $location_id = auth()->user()->location_id;
        $user_id = auth()->user()->id;
        $sub_location = SubLocations::where('location_id', $location_id)->where('description', 'STOCK ROOM')->first();
        $requestQuantity = intval($request->input('scan_qty'));
        
        $item = InventoryCapsule::whereHas('item', function ($query) use ($code) {
            $query->where('digits_code', $code);
        })->with(['item', 'item_stockroom_data' => function ($query) use ($sub_location) {
            $query->where('sub_locations_id', $sub_location->id);
        }])->where('locations_id', $location_id)->first();
        
        $itemReserved = ItemPosReservedQuantity::where('jan_number', $code)
        ->where('locations_id', $location_id);

        $stockQty = optional($item->item_stockroom_data)->qty ?? 0;

        if ($item) {

            if ($itemReserved->exists()) {

                $specificReservedItem = ItemPosReservedQuantity::where('jan_number', $code)
                ->where('locations_id', $location_id)
                ->where('user_id', $user_id)
                ->first();


                $totalQty = $itemReserved->sum('qty');

                if ($stockQty - $totalQty < $requestQuantity) {
                    return response()->json([
                        'status' => 'insufficient stock',
                        'available_stock' => $stockQty - $totalQty,
                    ]);
                }

                else{
                    
                    if ($specificReservedItem){
                        $specificReservedItem->qty = $specificReservedItem->qty + $requestQuantity;
                        $specificReservedItem->save();
                    }
                    else{
                        
                        ItemPosReservedQuantity::create([
                            'user_id' => $user_id,
                            'locations_id' => $location_id,
                            'digits_code' => $item->item->digits_code2,
                            'jan_number' => $item->item->digits_code,
                            'item_description' => $item->item->item_description,
                            'current_srp' => $item->item->current_srp,
                            'qty' => $requestQuantity,
                        ]);
                    }
                }

                $totalQty = $itemReserved->sum('qty');
                
            } 
            else {

                $totalQty = $requestQuantity;

                if ($stockQty < $requestQuantity) {
                    return response()->json([
                        'status' => 'insufficient stock',
                        'available_stock' => $stockQty,
                    ]);
                }
                else{
                    ItemPosReservedQuantity::create([
                        'user_id' => $user_id,
                        'locations_id' => $location_id,
                        'digits_code' => $item->item->digits_code2,
                        'jan_number' => $item->item->digits_code,
                        'item_description' => $item->item->item_description,
                        'current_srp' => $item->item->current_srp,
                        'qty' => $requestQuantity,
                    ]);
                }

                
            }


            if (is_null($item->current_srp) && $item->current_srp < 0){
                return response()->json([
                    'status' => 'item has no current srp',
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
                    'quantity' => $requestQuantity,
                    'item_stock_qty' => $stockQty - $totalQty,
                ],
            ]);
            
        } 
        else {
            return response()->json([
                'status' => 'not_found'
            ]);
        }
    }

    public function itemOption(Request $request){

        $code = $request->input('jan_number');
        $item_action = $request->input('item_action');
        $location_id = auth()->user()->location_id;
        $user_id = auth()->user()->id;
        $sub_location = SubLocations::where('location_id', $location_id)->where('description', 'STOCK ROOM')->first();

        $item = InventoryCapsule::whereHas('item', function ($query) use ($code) {
            $query->where('digits_code', $code);
        })->with(['item', 'item_stockroom_data' => function ($query) use ($sub_location) {
            $query->where('sub_locations_id', $sub_location->id);
        }])->where('locations_id', $location_id)->first();
        
        $stockQty = optional($item->item_stockroom_data)->qty ?? 0;

        $itemReserved = ItemPosReservedQuantity::where('jan_number', $code)
        ->where('locations_id', $location_id);

        $specificReservedItem = ItemPosReservedQuantity::where('jan_number', $code)
        ->where('locations_id', $location_id)
        ->where('user_id', $user_id)
        ->first();

        if ($item_action == "add"){

            $totalQty = $itemReserved->sum('qty');

            if ($stockQty - $totalQty == 0){
                return response()->json([
                    'status' => 'error',
                    'message' => 'insufficient stock',
                ]);
            }
            else{

                $specificReservedItem->qty = $specificReservedItem->qty + 1;
                $specificReservedItem->save();
    
                $totalQty = $itemReserved->sum('qty');
    
                return response()->json([
                    'status' => 'quantity added',
                    'item_stock_qty' => $stockQty - $totalQty,
                    'jan_number' => $code,
                    'action' => 'add',
                ]);
            }
        }
        elseif($item_action == "minus"){

            $specificReservedItem->qty = $specificReservedItem->qty - 1;
            $specificReservedItem->save();

            $totalQty = $itemReserved->sum('qty');

            return response()->json([
                'status' => 'quantity deduct',
                'item_stock_qty' => $stockQty - $totalQty,
                'jan_number' => $code,
            ]);
        }
        elseif($item_action == "remove"){
            if ($specificReservedItem) {
                $specificReservedItem->delete();
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'insufficient stock',
                ]);
            }

            return response()->json([
                'status' => 'item reservation removed',
                'jan_number' => $code,
            ]);
        }
        else{

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
        
        $user_id = auth()->user()->id;
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

            ItemPosReservedQuantity::where('locations_id', $location_id)
            ->where('user_id', $user_id)
            ->delete();

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

    public function clearCart(){

        $location_id = auth()->user()->location_id;
        $user_id = auth()->user()->id;

        ItemPosReservedQuantity::where('locations_id', $location_id)
        ->where('user_id', $user_id)
        ->delete();

        
        return response()->json([
            'status' => 'success',
            'message' => 'cart cleared'
        ]);
    }
    

}
