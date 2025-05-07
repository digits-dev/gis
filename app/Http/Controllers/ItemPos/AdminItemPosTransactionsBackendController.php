<?php namespace App\Http\Controllers\ItemPos;
	use Session;
	use App\Http\Controllers\Controller;
	use App\Models\Capsule\InventoryCapsule;
	use App\Models\Submaster\SubLocations;
	use Request;
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
	use CRUDBooster;
	use Illuminate\Support\Facades\Redirect;
	use App\Models\Submaster\AddOns;
	use App\Models\PosFrontend\AddonsHistory;
	use App\Models\Submaster\AddOnMovementHistory;
	use App\Models\Submaster\AddOnActionType;

	class AdminItemPosTransactionsBackendController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "item_pos";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Reference #","name"=>"reference_number"];
			$this->col[] = ["label"=>"Total Value","name"=>"total_value"];
			$this->col[] = ["label"=>"Change Value","name"=>"change_value"];
			$this->col[] = ["label"=>"Location","name"=>"locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Mode Of Payments Id","name"=>"mode_of_payments_id","join"=>"mode_of_payments,payment_description"];
			$this->col[] = ["label"=>"Payment Reference","name"=>"payment_reference"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Change Value','name'=>'change_value','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Locations Id','name'=>'locations_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'locations,location_name'];
			$this->form[] = ['label'=>'Mode Of Payments Id','name'=>'mode_of_payments_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'mode_of_payments,id'];
			$this->form[] = ['label'=>'Payment Reference','name'=>'payment_reference','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Reference Number','name'=>'reference_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Status','name'=>'status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Total Value','name'=>'total_value','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Updated By','name'=>'updated_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();
			if(CRUDBooster::isUpdate()) {
				if(in_array(CRUDBooster::myPrivilegeId(),[1,5,15])){
					$this->addaction[] = ['title'=>'View Void Transaction',
					'url'=>CRUDBooster::mainpath('void-transaction/[id]'),
					'icon'=>'fa fa-times-circle', 
					"showIf"=>"[status] == 'POSTED'",
					'confirmation'=>'Yes',
					'confirmation_title'=>'Confirm void',
					'confirmation_text'=>'Are you sure to void this transaction?',
					'color'=>'danger'
				];
				}
			}
	    }

	    public function hook_row_index($column_index,&$column_value) {	        
	    	if($column_index == 1){
				if($column_value == 'POSTED'){
					$column_value = '<span class="label label-success">'.$column_value.'</span>';
				}else {
					$column_value = '<span class="label label-danger">'.$column_value.'</span>';
				}
			}
	    }

		public function voidTransaction($id){
			
			$header = ItemPos::where('id',$id)->first();
			if (date('Y-m-d', strtotime($header->created_at)) != date('Y-m-d')){
				CRUDBooster::redirect(CRUDBooster::adminpath('item_pos_transactions_backend'), trans("not allowed to void"), 'danger');
			}
			if ($header->status == 'VOID') {
				CRUDBooster::redirect(CRUDBooster::adminpath('item_pos_transactions_backend'), trans("Already voided"), 'danger');
			}
			
			$lines = ItemPosLines::where('item_pos_id',$id)->get();
			$capsule_type_id = DB::table('capsule_action_types')->where('status', 'ACTIVE')->where('description', 'Void')->value('id');
			$sales_types_id = SalesType::where(DB::raw('UPPER(description)'), 'ITEMS')
					->where('status', 'ACTIVE')
					->pluck('id')
					->first();
			$sub_location_id = SubLocations::where('location_id',$header->locations_id)->value('id');
			$addOns = AddonsHistory::where('item_pos_id',$id)->get();
			$addOnTypeId = AddOnActionType::where('id', 3)->first()->id;

			foreach($lines ?? [] as $key => $value){
				HistoryCapsule::insert([
					'reference_number' => $header->reference_number,
					'item_code' => $value->digits_code,
					'capsule_action_types_id' => $capsule_type_id,
					'locations_id' => $value->locations_id,
					'to_sub_locations_id' => $sub_location_id,
					'qty' => $value->qty,
					'created_at' => date('Y-m-d H:i:s'),
					'created_by' => CRUDBooster::myId()
				]);
	
				InventoryCapsuleLine::leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
				->where('ic.locations_id', $value->locations_id)
				->where('inventory_capsule_lines.sub_locations_id', $sub_location_id)
				->where('ic.item_code', $value->digits_code)
				->update([
					'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty + $value->qty"),
					'inventory_capsule_lines.updated_by' => CRUDBooster::myId(),
					'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
				]);
	
				CapsuleSales::insert([
					'reference_number' => $header->reference_number,
					'item_code' => $value->jan_number,
					'locations_id' => $value->locations_id,
					'qty' => $value->qty * -1,
					'sales_type_id' => $sales_types_id,
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s')
				]);
			}

			if(!empty($addOns)){
				foreach($addOns ?? [] as $key => $val){
					AddOns::where('digits_code', $val->digits_code)->where('locations_id',CRUDBooster::myLocationId())->increment('qty', $val->qty);
					AddOnMovementHistory::insert([
						'reference_number' => $header->reference_number,
						'digits_code' => $val->digits_code,
						'add_on_action_types_id' => $addOnTypeId,
						'locations_id' => CRUDBooster::myLocationId(),
						'qty' => $val->qty,
						'status' => 'VOID',
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => CRUDBooster::myId()
					]);
				}
			}
			$header->update(['status' => "VOID", 'updated_by' => CRUDBooster::myId(), 'updated_at' =>  date('Y-m-d H:i:s')]);
			CRUDBooster::redirect(CRUDBooster::adminpath('item_pos_transactions_backend'), trans("Void successfully!"), 'success');
		}

		public function getDetail($id){
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }
			$data = [];
			$data['page_title'] = 'View Item POS Transactions';
			$data['items'] = ItemPos::query()->with(['item_lines','creator:id,name','updator:id,name','ModeOfPayments','location'])->where('id',$id)->first();
			$data['addons'] = AddonsHistory::where('item_pos_id', $id)->where('add_ons.locations_id', CRUDBooster::myLocationId())->leftjoin('add_ons', 'add_ons.digits_code', 'addons_history.digits_code')->select('add_ons.description', 'addons_history.qty' )->get();
			return view('pos-items.item-pos-transactions',$data);
		}
	}