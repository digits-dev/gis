<?php namespace App\Http\Controllers\Capsule;

	use App\Models\Capsule\CapsuleReturn;
	use App\Models\Capsule\CapsuleSales;
	use App\Models\Capsule\HistoryCapsule;
	use App\Models\Capsule\InventoryCapsule;
	use App\Models\Capsule\InventoryCapsuleLine;
	use App\Models\Submaster\CapsuleActionType;
	use App\Models\Submaster\Counter;
	use App\Models\Submaster\GashaMachines;
use App\Models\Submaster\Item;
use App\Models\Submaster\Locations;
	use App\Models\Submaster\SalesType;
	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminCapsuleReturnsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = in_array(CRUDBooster::myPrivilegeId(), [1,3,5]);
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "capsule_returns";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference Number","name"=>"reference_number"];
			$this->col[] = ["label"=>"JAN #","name"=>"item_code"];
			$this->col[] = ["label"=>"Qty","name"=>"qty"];
			$this->col[] = ["label"=>"Sub Location","name"=>"sub_locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Machine","name"=>"gasha_machines_id","join"=>"gasha_machines,serial_number"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'JAN #','name'=>'item_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Qty','name'=>'qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Sub Location','name'=>'sub_locations_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'locations,location_name'];
			$this->form[] = ['label'=>'Machine','name'=>'gasha_machines_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'gasha_machines,location_name'];
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


	        /*
	        | ----------------------------------------------------------------------
	        | Add More Button Selected
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button
	        | Then about the action, you should code at actionButtonSelected method
	        |
	        */
	        $this->button_selected = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------
	        | @message = Text of message
	        | @type    = warning,success,danger,info
	        |
	        */
	        $this->alert = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add more button to header button
	        | ----------------------------------------------------------------------
	        | @label = Name of button
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        |
	        */
	        $this->index_button = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
	        |
	        */
	        $this->table_row_color = array();


	        /*
	        | ----------------------------------------------------------------------
	        | You may use this bellow array to add statistic at dashboard
	        | ----------------------------------------------------------------------
	        | @label, @count, @icon, @color
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add javascript at body
	        | ----------------------------------------------------------------------
	        | javascript code in the variable
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code before index table
	        | ----------------------------------------------------------------------
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code after index table
	        | ----------------------------------------------------------------------
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include Javascript File
	        | ----------------------------------------------------------------------
	        | URL of your javascript each array
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add css style at body
	        | ----------------------------------------------------------------------
	        | css code in the variable
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;



	        /*
	        | ----------------------------------------------------------------------
	        | Include css File
	        | ----------------------------------------------------------------------
	        | URL of your css each array
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();


	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for button selected
	    | ----------------------------------------------------------------------
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here

	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate query of index result
	    | ----------------------------------------------------------------------
	    | @query = current sql query
	    |
	    */
	    public function hook_query_index(&$query) {
	        if(CRUDBooster::isSuperadmin()){
				$query->whereNull('capsule_returns.deleted_at')
					  ->orderBy('capsule_returns.id', 'desc');
			}else if(in_array(CRUDBooster::myPrivilegeId(),[3,5])){
				$query
					->leftJoin('gasha_machines as gm', 'capsule_returns.gasha_machines_id', 'gm.id')
					->where('capsule_returns.created_by', CRUDBooster::myId())
					->where('gm.location_id', CRUDBooster::myLocationId())
					->orderBy('capsule_returns.id', 'desc');
			} 
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate row of index table html
	    | ----------------------------------------------------------------------
	    |
	    */
	    public function hook_row_index($column_index,&$column_value) {
	    	//Your code here
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before add data is execute
	    | ----------------------------------------------------------------------
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {
	        //Your code here
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after add public static function called
	    | ----------------------------------------------------------------------
	    | @id = last insert id
	    |
	    */
	    public function hook_after_add($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before update data is execute
	    | ----------------------------------------------------------------------
	    | @postdata = input post data
	    | @id       = current id
	    |
	    */
	    public function hook_before_edit(&$postdata,$id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_edit($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

		public function getAdd() {
			//Create an Auth
			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = [];
			$data['user_location_id'] = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();
			$data['page_title'] = 'Add Data';
			$data['stockroom'] = Locations::find(CRUDBooster::myLocationId());
			//Please use view method instead view method from laravel
			return $this->view('capsule.capsule-return',$data);
		}

		public function submitCapsuleReturn(Request $request){

			$return_inputs = Request::all();

			$gasha_machines = GashaMachines::where('serial_number', $return_inputs['gasha_machine'])->first();
			$inventory_capsule_lines = InventoryCapsuleLine::get();
			$validateGM = $inventory_capsule_lines->where('gasha_machines_id', $gasha_machines->id)->first();
						
			$filteredData = [];
			
			foreach ($return_inputs as $key => $value) {
				if (strpos($key, 'qty_') === 0) {
					$newKey = substr($key, 4); // Remove "qty_" prefix
					// $filteredData[$newKey] = $value;
					$jan_no = Item::where('digits_code2', $newKey)->pluck('digits_code')->first();
					$filteredData[$jan_no] = $value;
				}
			}
			
			$capsule_return_rn = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
			$sales_rn = Counter::getNextReference(DB::table('cms_moduls')->where('name', 'Capsule Sales')->first()->id);
			
			foreach($filteredData as $key=>$value){
				$inventory_capsule = InventoryCapsule::leftJoin('items', 'items.digits_code2', 'inventory_capsules.item_code')
					->where('locations_id', CRUDBooster::myLocationId());

				$inventory_capsule_id = $inventory_capsule->where('items.digits_code', $key)->select('inventory_capsules.id')->first()->id;
				
				$capsule = new CapsuleReturn([
					'reference_number' =>$capsule_return_rn,
					'item_code' => $key,
					'qty' => (int) str_replace(',', '', $value),
					'sub_locations_id' => $return_inputs['stock_room'],
					'gasha_machines_id' => $gasha_machines->id,
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s')
				]);

				$capsule->save();

				$items = Item::where('digits_code', $capsule->item_code)->get()->first();
				
				HistoryCapsule::insert([
					'reference_number' => $capsule->reference_number,
					'item_code' => $items->digits_code2,
					'capsule_action_types_id' => CapsuleActionType::where('description', 'Return')->first()->id,
					'gasha_machines_id' => $capsule->gasha_machines_id,
					'locations_id' => $return_inputs['stock_room'],
					'qty' => $capsule->qty,
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s')
				]);

				// Gasha Machine
				$current_capsule_value = InventoryCapsuleLine::where('inventory_capsules_id', $inventory_capsule_id)
					->where('gasha_machines_id', $capsule->gasha_machines_id)
					->where('sub_locations_id', null)
					->first()
					->qty;

				InventoryCapsuleLine::where('inventory_capsules_id', $inventory_capsule_id)
					->where('gasha_machines_id', $capsule->gasha_machines_id)->where('sub_locations_id', null)
					->update([
						'inventory_capsule_lines.qty' => 0,
						'updated_by' => CRUDBooster::myId()
				]);

				// Stockroom
				DB::table('inventory_capsule_lines')->whereNotNull('sub_locations_id')
					->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
					->leftJoin('sub_locations', 'sub_locations.id', 'inventory_capsule_lines.sub_locations_id')
					->where('inventory_capsules_id', $inventory_capsule_id)
					->where('inventory_capsules.item_code', $items->digits_code2)
					->update([
						'inventory_capsule_lines.updated_by' => CRUDBooster::myId(),
						'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty + $capsule->qty")
				]);

				CapsuleSales::insert([
					'reference_number' => $sales_rn,
					'item_code' => $capsule->item_code,
					'gasha_machines_id' => $capsule->gasha_machines_id,
					'sales_type_id' => SalesType::where('description', 'RETURN')->first()->id,
					'locations_id' => $gasha_machines->location_id,
					'qty' =>  abs($capsule->qty - $current_capsule_value),
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s')
				]);
			}

			return response()->json(['success'=>$filteredData, 'reference_number' => $capsule_return_rn, 'module_id'=>CRUDBooster::getCurrentModule()->id]);
		}

		public function validateGashaMachine(Request $request){

			$return_inputs = Request::all();

			$gasha_machines = GashaMachines::where('serial_number', $return_inputs['gasha_machine'])->first();
			$inventory_capsule_lines = InventoryCapsuleLine::get();
			$inventory_capsules = InventoryCapsule::get();
			$list_of_gm = $inventory_capsule_lines->where('gasha_machines_id', $gasha_machines->id)->where('qty', '>', 0)->pluck('inventory_capsules_id');
			$list_of_ic = $inventory_capsules->whereIn('id', $list_of_gm);
			$validateGM = $inventory_capsule_lines->where('gasha_machines_id', $gasha_machines->id)->first();
			$validateQty = $return_inputs['qty'] > $validateGM->qty;

			return response()->json([
				'not_exist' => $gasha_machines->location_id != CRUDBooster::myLocationId(),
				'gasha_machine'=>$validateGM->gasha_machines_id,
				'qty' => $validateQty,
				'list_of_gm' => $inventory_capsule_lines->where('gasha_machines_id', $gasha_machines->id)->where('qty', '>', 0),
				'list_of_ic' => $list_of_ic,
			]);

		}

	}
