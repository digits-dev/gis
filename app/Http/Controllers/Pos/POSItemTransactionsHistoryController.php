<?php

namespace App\Http\Controllers\Pos;
use App\Http\Controllers\Controller;
use App\Models\Capsule\InventoryCapsule;
use App\Models\Submaster\SubLocations;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ItemPos;
use App\Models\ItemPosLines;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Submaster\SalesType;
use App\Http\Controllers\Pos\POSDashboardController;
use App\Models\Capsule\HistoryCapsule;
use App\Models\Capsule\InventoryCapsuleLine;
use App\Models\Capsule\CapsuleSales;
use App\Models\Submaster\AddOns;
use App\Models\PosFrontend\AddonsHistory;
use App\Models\Submaster\AddOnMovementHistory;
use App\Models\Submaster\AddOnActionType;

class POSItemTransactionsHistoryController extends Controller
{
    public function getAllData(){
        $searchTerm = request('search');
       
        $query = ItemPos::query()->with(['creator:id,name','updator:id,name','ModeOfPayments','location']);
        
        if (Auth::user()->id_cms_privileges != 1) {
            $query->where('item_pos.locations_id', Auth::user()->location_id);
        }

        $filter = $query->filter(['search' => $searchTerm]);
        $result = $filter->orderBy('item_pos.created_at','desc');
 
        return $result->paginate(10)->through(function ($item) {
            return [
                'id'                   => $item->id,
                'reference_number'     => $item->reference_number,
                'total_value'          => $item->total_value,
                'change_value'         => $item->change_value,
                'mode_of_payments_id'  => $item->ModeOfPayments->payment_description,
                'locations_id'         => $item->location->location_name,
                'payment_reference'    => $item->payment_reference,
                'status'               => $item->status,
                'created_by'           => $item->creator->name ?? null,
                'created_at'           => $item->created_at ?? NULL,
                'updated_by'           => $item->updator->name ?? null,   
                'updated_at'           => $item->updated_at ?? NULL,
            ];
        });
    }

    public function index()
    {
        $is_missing_eod_or_sod = (new POSDashboardController)->check_sod_or_eod();
        if ($is_missing_eod_or_sod) {
            return $is_missing_eod_or_sod;
        } 
        $data = [];
        $data['page_title'] = 'Item POS Transactions';
        $data['item_pos']   = self::getAllData();
        $tableHeader = [
            'action'              => 'Action',
            'reference_number'    => 'Reference #',
            'total_value'         => 'Total Value',
            'change_value'        => 'Change Value',
            'mode_of_payments_id' => 'Mode of Payment',
            'locations_id'        => 'Location',
            'payment_reference'   => 'Payment Reference',
            'status'              => 'Status',
            'created_by'          => 'Created By',
            'created_at'          => 'Created At',
            'updated_by'          => 'Updated By',
            'updated_at'          => 'Updated At'
        ];

        $data['table_header'] =  $tableHeader;
        return view('pos-frontend.views.item-pos-transactions',$data);
    }

    public function getDetail($id){
        $data = [];
        $data['items'] = ItemPos::with(['item_lines','creator:id,name','updator:id,name','ModeOfPayments','location'])->where('id',$id)->first();
        $data['addons'] = AddonsHistory::where('item_pos_id', $id)->where('add_ons.locations_id', Auth::user()->location_id)->leftjoin('add_ons', 'add_ons.digits_code', 'addons_history.digits_code')->select('add_ons.description', 'addons_history.qty' )->get();
        return response()->json($data);
    }

    public function void($id){
        $header = ItemPos::where('id',$id)->first();
        if (date('Y-m-d', strtotime($header->created_at)) != date('Y-m-d')){
            return response()->json(['message'=>'not allowed to void' ]);
        }
        if ($header->status == 'VOID') {
            return response()->json(['message'=>'error' ]);
        }
        
        $lines = ItemPosLines::where('item_pos_id',$id)->get();
        $capsule_type_id = DB::table('capsule_action_types')->where('status', 'ACTIVE')->where('id', 14)->value('id');
        $sales_types_id = SalesType::where('id',8)
				->where('status', 'ACTIVE')
                ->first()->id;
        $addOns = AddonsHistory::where('item_pos_id',$id)->get();
        $addOnTypeId = AddOnActionType::where('id', 3)->first()->id;
        $sub_location_id = SubLocations::where('location_id',$header->locations_id)->value('id');
        foreach($lines ?? [] as $key => $value){
            HistoryCapsule::insert([
                'reference_number' => $header->reference_number,
                'item_code' => $value->digits_code,
                'capsule_action_types_id' => $capsule_type_id,
                'locations_id' => $value->locations_id,
                'to_sub_locations_id' => $sub_location_id,
                'qty' => $value->qty,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id
            ]);

            InventoryCapsuleLine::leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
            ->where('ic.locations_id', $value->locations_id)
            ->where('inventory_capsule_lines.sub_locations_id', $sub_location_id)
            ->where('ic.item_code', $value->digits_code)
            ->update([
                'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty + $value->qty"),
                'inventory_capsule_lines.updated_by' => Auth::user()->id,
                'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
            ]);

            CapsuleSales::insert([
                'reference_number' => $header->reference_number,
                'item_code' => $value->jan_number,
                'locations_id' => $value->locations_id,
                'qty' => $value->qty * -1,
                'sales_type_id' => $sales_types_id,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        if(!empty($addOns)){
            foreach($addOns ?? [] as $key => $val){
                AddOns::where('digits_code', $val->digits_code)->where('locations_id',Auth::user()->location_id)->increment('qty', $val->qty);
                AddOnMovementHistory::insert([
                    'reference_number' => $header->reference_number,
                    'digits_code' => $val->digits_code,
                    'add_on_action_types_id' => $addOnTypeId,
                    'locations_id' => Auth::user()->location_id,
                    'qty' => $val->qty,
                    'status' => 'VOID',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id
                ]);
            }
        }
        $header->update(['status' => "VOID", 'updated_by' => Auth::user()->id, 'updated_at' =>  date('Y-m-d H:i:s')]);
        return response()->json(['message'=>'Void successfully!', 'type'=>'success' ]);
    }

    public function show($id){
        $data = [];
        $data['page_title'] = 'View Item POS Transactions';
        $data['items'] = ItemPos::query()->with(['item_lines','creator:id,name','updator:id,name','ModeOfPayments','location','add_ons'])->where('id',$id)->first();
        $data['addons'] = AddonsHistory::where('item_pos_id', $id)->where('add_ons.locations_id', Auth::user()->location_id)->leftjoin('add_ons', 'add_ons.digits_code', 'addons_history.digits_code')->select('add_ons.description', 'addons_history.qty' )->get();
        return view('pos-frontend.views.item-pos-transaction-show',$data);
    }
}
