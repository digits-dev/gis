<?php namespace App\Http\Controllers\Capsule;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\Models\Submaster\Locations;
	use App\Models\Submaster\Counter;
	use App\Models\Capsule\InventoryCapsule;
    use App\Models\Capsule\InventoryCapsuleLine;
	use App\Models\Submaster\GashaMachines;
	use App\Models\Submaster\SubLocations;
	use App\Models\Submaster\Item;

	class AdminCapsuleAdjustmentsController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_export = false;
			$this->table = "capsule_adjustments";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference #","name"=>"reference_number"];
			$this->col[] = ["label"=>"Jan#","name"=>"jan_number"];
			$this->col[] = ["label"=>"Machine/Stockroom","name"=>"machine"];
			$this->col[] = ["label"=>"Adjustment Qty","name"=>"adjustment_qty"];
			$this->col[] = ["label"=>"Reason","name"=>"reason"];
			$this->col[] = ["label"=>"Before Qty","name"=>"before_qty"];
			$this->col[] = ["label"=>"After Qty","name"=>"after_qty"];
			$this->col[] = ["label"=>"Location","name"=>"locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

			# END FORM DO NOT REMOVE THIS LINE
			$this->form[] = ['label'=>'Reference #','name'=>'reference_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Jan#','name'=>'jan_number','type'=>'text','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Machine/Stockroom','name'=>'machine','type'=>'text','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Adjustment Qty','name'=>'adjustment_qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Reason','name'=>'reason','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Before Qty','name'=>'before_qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'After Qty','name'=>'after_qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Location','name'=>'locations_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'locations,location_name'];
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
	        $this->alert        = array();
	                

	        
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				$this->index_button[] = ["label"=>"Adjust capsule","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('adjust-capsule'),"color"=>"success"];
			}

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
	        $this->load_css[] = asset("css/font-family.css");
	        
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
	        //Your code here
	            
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

		public function getAdjustCapsule(){
			$this->cbLoader();
			if(!CRUDBooster::isCreate() && $this->global_privilege == false) {
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}
			$data = [];
			$data['page_title'] = 'Adjust Capsule Balance';
			$data['locations'] = Locations::activeDisburseToken();

			return $this->view("capsule.capsule-adjust", $data);
		}

		public function getJanCode(Request $request){
			$user_request = $request->all();
			$location_id = $user_request['location_id'];
			$items = InventoryCapsule::leftjoin('items','inventory_capsules.item_code','items.digits_code2')
							->select('inventory_capsules.*','items.*','inventory_capsules.id AS inv_id')
							->where('locations_id', $location_id)->get();

			return response()->json(['items'=> $items]);
		}

		public function getMachines(Request $request){
			$fields = $request->all();
			$id = $fields['id'];
			$location_id = $fields['location_id'];
			// $items = InventoryCapsuleLine::leftjoin('gasha_machines','inventory_capsule_lines.gasha_machines_id','gasha_machines.id')
			// 							->leftjoin('sub_locations','inventory_capsule_lines.sub_locations_id','sub_locations.id')
			// 							->select('inventory_capsule_lines.*',
			// 							'gasha_machines.*',
			// 							'inventory_capsule_lines.id AS icl_id',
			// 							DB::raw('IF(gasha_machines.serial_number IS NULL, sub_locations.description, gasha_machines.serial_number) AS machines'))
			// 							->where('inventory_capsules_id', $id)->get();
			$machines = GashaMachines::where('location_id',$location_id)
										->select('id AS item_id',
										'serial_number AS description')
										->get()->toArray();

			$stockroom = SubLocations::where('location_id',$location_id)
										->select('id AS item_id',
												'description AS description')
										->get()->toArray();
			$items = array_merge($stockroom, $machines);

			return response()->json(['items'=> $items]);
		}

		public function getMachinesQty(Request $request){
			$fields = $request->all();
			$inv_id = $fields['inv_id'];
			$id = $fields['id'];
			$type = $fields['type'];
			$items = InventoryCapsule::leftjoin('inventory_capsule_lines','inventory_capsule_lines.inventory_capsules_id','inventory_capsules.id')
			->where('inventory_capsules.id', $inv_id);
			if($type === "STOCK ROOM"){
				$capsule_qty   = $items->where('inventory_capsule_lines.sub_locations_id', $id)->pluck('qty')->first();
				
			}else{
				$capsule_qty   = $items->where('inventory_capsule_lines.gasha_machines_id', $id)->pluck('qty')->first();
				
			}

			return response()->json(['qty'=> $capsule_qty]);
		}

		public function getCapsuleInventory(Request $request) {
			$locations_id = $request->get('location_id');
			$inv_id = $request->get('janCodeId');
			$capsule_id = $request->get('capsule_id');
			$action = $request->get('action');
			$adjustment_qty =  $request->get('value');
			$type =  $request->get('type');
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$current_inventory = new \stdClass();
			$locationname = Locations::where('id',$locations_id)->pluck('location_name')->first();
			if($type === "STOCK ROOM"){
				$isExist = DB::table('inventory_capsule_lines')
					->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
					->leftJoin('locations', 'locations.id', 'inventory_capsules.locations_id')
					->where('inventory_capsule_lines.sub_locations_id', $capsule_id)
					->where('inventory_capsules.id', $inv_id)
					->where('inventory_capsules.locations_id', $locations_id)
					->get()
					->first();
				//check if not existing
				if(!$isExist){
					// // inserting a new entry for inventory capsule lines
					// InventoryCapsuleLine::insert([
					// 	'inventory_capsules_id' => $inv_id,
					// 	'sub_locations_id' => $capsule_id,
					// 	'qty' => 0,
					// 	'created_by' => $action_by,
					// 	'created_at' => $time_stamp,
					// ]);
					$current_inventory->action = $action;
					$current_inventory->qty = 0;
					$current_inventory->location_name = $locationname;
					$current_inventory->type = "STOCK ROOM";
				}else{
					$current_inventory = DB::table('inventory_capsule_lines')
					->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
					->leftJoin('locations', 'locations.id', 'inventory_capsules.locations_id')
					->where('inventory_capsule_lines.sub_locations_id', $capsule_id)
					->where('inventory_capsules.id', $inv_id)
					->where('inventory_capsules.locations_id', $locations_id)
					->select('*','inventory_capsule_lines.id as icl_id')
					->get()
					->first();
					$current_inventory->type = "STOCK ROOM";
				}
				
			}else{
				$isExist = DB::table('inventory_capsule_lines')
					->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
					->leftJoin('locations', 'locations.id', 'inventory_capsules.locations_id')
					->where('inventory_capsule_lines.gasha_machines_id', $capsule_id)
					->where('inventory_capsules.id', $inv_id)
					->where('inventory_capsules.locations_id', $locations_id)
					->get()
					->first();
				//check if not existing
				if(!$isExist){
					// // inserting a new entry for inventory capsule lines
					// InventoryCapsuleLine::insert([
					// 	'inventory_capsules_id' => $inv_id,
					// 	'gasha_machines_id' => $capsule_id,
					// 	'qty' => 0,
					// 	'created_by' => $action_by,
					// 	'created_at' => $time_stamp,
					// ]);
					$current_inventory->action = $action;
					$current_inventory->qty = 0;
					$current_inventory->location_name = $locationname;
					$current_inventory->type = "MACHINE";
				}else{
					$current_inventory = DB::table('inventory_capsule_lines')
					->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
					->leftJoin('locations', 'locations.id', 'inventory_capsules.locations_id')
					->where('inventory_capsule_lines.gasha_machines_id', $capsule_id)
					->where('inventory_capsules.id', $inv_id)
					->where('inventory_capsules.locations_id', $locations_id)
					->select('*','inventory_capsule_lines.id as icl_id')
					->get()
					->first();
					$current_inventory->type = "MACHINE";
				}
				
			}
		
			$current_inventory->action = $action;
			$current_inventory->adjustment_qty = (integer) $adjustment_qty;
			if ($action == 'add') {
				$current_inventory->new_qty = $current_inventory->qty + $adjustment_qty;
			} else {
				$current_inventory->new_qty = $current_inventory->qty - $adjustment_qty;

			}
		
			return response()->json($current_inventory);
		}

		public function submitCapsuleAmount(Request $request){
			$data = $request->all();
			$time_stamp = date('Y-m-d H:i:s');
			$reference_number = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
			$action_by = CRUDBooster::myId();
			$header_id = $data['jan_no'];
			$capsule_id = $data['capsule_line_id'];
			$locations_id = $data['locations_id'];
			$action = $data['action'];
			$adjustment_qty = preg_replace("/\D/", '', $data['adjustment_qty_' . $action]);
			$reason = $data['reason_' . $action];
			$capsule_type = 6;
			$type = $data['type'];
			$id = $data['machine'];
			// $inventory_query = DB::table('inventory_capsule_lines')
			// 				->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
			// 				->leftJoin('locations', 'locations.id', 'inventory_capsules.locations_id')
			// 				->where('inventory_capsule_lines.id', $capsule_id)
			// 				->where('inventory_capsules.locations_id', $locations_id);
	
			if($type === "MACHINE"){
				$isExist = DB::table('inventory_capsule_lines')
					->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
					->leftJoin('locations', 'locations.id', 'inventory_capsules.locations_id')
					->where('inventory_capsule_lines.gasha_machines_id', $id)
					->where('inventory_capsules.id', $header_id)
					->where('inventory_capsules.locations_id', $locations_id)
					->get()
					->first();
				if(!$isExist){
					// inserting a new entry for inventory capsule lines
					InventoryCapsuleLine::insert([
						'inventory_capsules_id' => $header_id,
						'gasha_machines_id' => $id,
						'qty' => 0,
						'created_by' => $action_by,
						'created_at' => $time_stamp,
					]);
			
				}
				$inventory_query   = InventoryCapsule::leftjoin('inventory_capsule_lines','inventory_capsule_lines.inventory_capsules_id','inventory_capsules.id')
				->where('inventory_capsules.id', $header_id)->where('inventory_capsule_lines.gasha_machines_id', $id)->where('inventory_capsules.locations_id', $locations_id);
				
			}else{
				$isExist = DB::table('inventory_capsule_lines')
					->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
					->leftJoin('locations', 'locations.id', 'inventory_capsules.locations_id')
					->where('inventory_capsule_lines.sub_locations_id', $id)
					->where('inventory_capsules.id', $header_id)
					->where('inventory_capsules.locations_id', $locations_id)
					->get()
					->first();
				if(!$isExist){
					// inserting a new entry for inventory capsule lines
					InventoryCapsuleLine::insert([
						'inventory_capsules_id' => $header_id,
						'sub_locations_id' => $id,
						'qty' => 0,
						'created_by' => $action_by,
						'created_at' => $time_stamp,
					]);
			
				}
				$inventory_query   = InventoryCapsule::leftjoin('inventory_capsule_lines','inventory_capsule_lines.inventory_capsules_id','inventory_capsules.id')
				->where('inventory_capsules.id', $header_id)->where('inventory_capsule_lines.sub_locations_id', $id)->where('inventory_capsules.locations_id', $locations_id);
				
			}
			
			$before_qty = $inventory_query->pluck('qty')->first();
		
			if ($action == 'add') {
				$inventory_query->update([
					'qty' => DB::raw("qty + $adjustment_qty"),
					'inventory_capsule_lines.updated_at' => $time_stamp,
					'inventory_capsule_lines.updated_by' => $action_by,
				]);
				
			} else {
				if ($adjustment_qty > $inventory_query->pluck('qty')->first()){
					return CRUDBooster::redirect(CRUDBooster::mainpath(), 'Deduct Capsule cannot exceed to capsule inventory.',"danger");
				}
				$adjustment_qty = "-$adjustment_qty";
				$inventory_query->update([
					'qty' => DB::raw("qty + ($adjustment_qty)"),
					'inventory_capsule_lines.updated_at' => $time_stamp,
					'inventory_capsule_lines.updated_by' => $action_by,
				]);
			}
		
			
			$after_qty = $inventory_query->pluck('qty')->first();
			$itemCode = $inventory_query->pluck('item_code')->first();
			$digits_code = Item::where('digits_code2',$itemCode)->pluck('digits_code')->first();
			$machineId = $inventory_query->pluck('gasha_machines_id')->first();
			$serial_number = GashaMachines::where('id', $machineId)->pluck('serial_number')->first();
			$subLocationId = $inventory_query->pluck('sub_locations_id')->first();
			$subLocationDescription = SubLocations::where('id',$subLocationId)->pluck('description')->first();

			if($machineId){
				$machine = $serial_number;
			}else{
				$machine = $subLocationDescription;
			}

			$to_be_inserted = [
				'reference_number' => $reference_number,
				'jan_number'   => $digits_code,
				'machine'   => $machine,
				'locations_id' => $locations_id,
				'adjustment_qty' => $adjustment_qty,
				'reason' => $reason,
				'before_qty' => $before_qty,
				'after_qty' => $after_qty,
				'created_by' => $action_by,
				'created_at' => $time_stamp
			];

			DB::table('capsule_adjustments')->insert($to_be_inserted);
			if ($action == 'add') {
				$history = [
					'reference_number' => $reference_number,
					'item_code' => $inventory_query->pluck('item_code')->first(),
					'capsule_action_types_id' => $capsule_type,
					'locations_id' => $locations_id,
					'to_machines_id' => $inventory_query->pluck('gasha_machines_id')->first(),
					'to_sub_locations_id' => $inventory_query->pluck('sub_locations_id')->first(),
					'qty' => $adjustment_qty,
					'created_at' => $time_stamp,
					'created_by' => $action_by
				];
			}else{
				$history = [
					'reference_number' => $reference_number,
					'item_code' => $inventory_query->pluck('item_code')->first(),
					'capsule_action_types_id' => $capsule_type,
					'locations_id' => $locations_id,
					'from_machines_id' => $inventory_query->pluck('gasha_machines_id')->first(),
					'from_sub_locations_id' => $inventory_query->pluck('sub_locations_id')->first(),
					'qty' => $adjustment_qty,
					'created_at' => $time_stamp,
					'created_by' => $action_by
				];
			}
			
			DB::table('history_capsules')->insert($history);

			CRUDBooster::redirect(CRUDBooster::mainpath(), 'Your form submitted succesfully.',"success");
		}
	}