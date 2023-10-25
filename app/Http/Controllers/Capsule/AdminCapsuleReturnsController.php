<?php namespace App\Http\Controllers\Capsule;

	use App\Models\Capsule\CapsuleReturn;
	use App\Models\Capsule\CapsuleSales;
	use App\Models\Capsule\HistoryCapsule;
	use App\Models\Capsule\InventoryCapsule;
	use App\Models\Capsule\InventoryCapsuleLine;
	use App\Models\Submaster\CapsuleActionType;
	use App\Models\Submaster\Counter;
	use App\Models\Submaster\GashaMachines;
	use App\Models\Submaster\Locations;
	use App\Models\Submaster\SalesType;
	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminCapsuleReturnsController extends \crocodicstudio\crudbooster\controllers\CBController {

		public static function myLocationId()
		{
			return Session::get('location_id');
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "capsule_returns";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference#","name"=>"reference_number"];
			$this->col[] = ["label"=>"Item Code","name"=>"item_code"];
			$this->col[] = ["label"=>"Qty","name"=>"qty"];
			$this->col[] = ["label"=>"Sub Locations","name"=>"sub_locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Gasha Machine Serial Number","name"=>"gasha_machines_id","join"=>"gasha_machines,serial_number"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Item Code','name'=>'item_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Qty','name'=>'qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Sub Locations Id','name'=>'sub_locations_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'locations,location_name'];
			$this->form[] = ['label'=>'Gasha Machines Id','name'=>'gasha_machines_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'gasha_machines,location_name'];
			$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Item Code","name"=>"item_code","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Qty","name"=>"qty","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Sub Locations Id","name"=>"sub_locations_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"sub_locations,id"];
			//$this->form[] = ["label"=>"Gasha Machines Id","name"=>"gasha_machines_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"gasha_machines,location_name"];
			//$this->form[] = ["label"=>"Created By","name"=>"created_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			# OLD END FORM

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

		public function getAdd() {
			//Create an Auth
			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['user_location_id'] = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();
			$data['page_title'] = 'Add Data';
			$data['stockroom'] = Locations::find(self::myLocationId());
			//Please use view method instead view method from laravel
			return $this->view('capsule.capsule-return',$data);
		}

		public function submitCapsuleReturn(Request $request){
			
			$return_inputs = Request::all();

			$gasha_machines = GashaMachines::where('serial_number', $return_inputs['gasha_machine'])->first();
			$inventory_capsule_lines = InventoryCapsuleLine::get();
			$validateGM = $inventory_capsule_lines->where('gasha_machines_id', $gasha_machines->id)->first();
			$inventory_capsule = InventoryCapsule::get();
			// $validateQty = $return_inputs['qty'] > $validateGM->qty;
			// $qty = $return_inputs['qty'];

			$filteredData = [];

			foreach ($return_inputs as $key => $value) {
				if (strpos($key, 'qty_') === 0) {
					$newKey = substr($key, 4); // Remove "qty_" prefix
					$filteredData[$newKey] = $value;
				}
			}

			$capsule_return_rn = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
			$sales_rn = Counter::getNextReference(DB::table('cms_moduls')->where('name', 'Capsule Sales')->first()->id);

			foreach($filteredData as $key=>$value){

				$capsule = new CapsuleReturn([
					'reference_number' =>$capsule_return_rn,
					'item_code' => $inventory_capsule->where('item_code', $key)->first()->item_code,
					'qty' => (int) str_replace(',', '', $value),
					'sub_locations_id' => $return_inputs['stock_room'],
					'gasha_machines_id' => $gasha_machines->id,
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s')
				]);
	
				$capsule->save();

				HistoryCapsule::insert([
					'reference_number' => $capsule->reference_number,
					'item_code' => $capsule->item_code,
					'capsule_action_types_id' => CapsuleActionType::where('description', 'Return')->first()->id,
					'gasha_machines_id' => $capsule->gasha_machines_id,
					'locations_id' => $return_inputs['stock_room'],
					'qty' => $capsule->qty,
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s')
				]);

				// Gasha Machine

				$current_capsule_value = InventoryCapsuleLine::where('inventory_capsules_id', $inventory_capsule->where('item_code', $key)->first()->id)
				->where('gasha_machines_id', $capsule->gasha_machines_id)->where('sub_locations_id', null)->first()->qty;

				InventoryCapsuleLine::where('inventory_capsules_id', $inventory_capsule->where('item_code', $key)->first()->id)
					->where('gasha_machines_id', $capsule->gasha_machines_id)->where('sub_locations_id', null)
					->update([
						// 'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty - $capsule->qty"),
						'inventory_capsule_lines.qty' => 0,
						'updated_by' => CRUDBooster::myId()
				]);

				// Stockroom
				DB::table('inventory_capsule_lines')->whereNotNull('sub_locations_id')
					->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
					->leftJoin('sub_locations', 'sub_locations.id', 'inventory_capsule_lines.sub_locations_id')
					->where('inventory_capsules_id', $inventory_capsule->where('item_code', $key)->first()->id)
					->where('inventory_capsules.item_code', $capsule->item_code)
					->update([
						'inventory_capsule_lines.updated_by' => CRUDBooster::myId(),
						'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty + $capsule->qty")
				]);

				CapsuleSales::insert([
					'reference_number' => $sales_rn,
					'item_code' => $capsule->item_code,
					'gasha_machines_id' => $capsule->gasha_machines_id,
					'sales_type_id' => SalesType::where('description', 'PULLOUT')->first()->id,
					'locations_id' => $gasha_machines->location_id,
					'qty' =>  abs($capsule->qty - $current_capsule_value),
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s')
				]);
			}


			
			// $capsule = new CapsuleReturn([
			// 	'reference_number' => Counter::getNextReference(CRUDBooster::getCurrentModule()->id),
			// 	'item_code' => $inventory_capsule->where('id', $validateGM->inventory_capsules_id)->first()->item_code,
			// 	'qty' => $value,
			// 	'sub_locations_id' => $return_inputs['stock_room'],
			// 	'gasha_machines_id' => $gasha_machines->id,
			// 	'created_by' => CRUDBooster::myId(),
			// 	'created_at' => date('Y-m-d H:i:s')
			// ]);

			// $capsule->save();


			// HistoryCapsule::insert([
			// 	'reference_number' => $capsule->reference_number,
			// 	'item_code' => $inventory_capsule->where('id', $validateGM->inventory_capsules_id)->first()->item_code,
			// 	'capsule_action_types_id' => CapsuleActionType::where('description', 'Return')->first()->id,
			// 	'gasha_machines_id' => $gasha_machines->id,
			// 	'locations_id' => $return_inputs['stock_room'],
			// 	'qty' => $return_inputs['qty'],
			// 	'created_by' => CRUDBooster::myId(),
			// 	'created_at' => date('Y-m-d H:i:s')
			// ]);

			// InventoryCapsuleLine::where('gasha_machines_id', $gasha_machines->id)->update([
			// 	'qty' => $inventory_capsule_lines->where('gasha_machines_id', $gasha_machines->id)->first()->qty - $return_inputs['qty'],
			// 	'updated_by' => CRUDBooster::myId()
			// ]);

			// DB::table('inventory_capsule_lines')->whereNotNull('sub_locations_id')
			// 	->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
			// 	->leftJoin('sub_locations', 'sub_locations.id', 'inventory_capsule_lines.sub_locations_id')
			// 	->where('inventory_capsules_id', $gasha_machines->id)
			// 	->where('inventory_capsules.item_code', $inventory_capsule->where('id', $validateGM->inventory_capsules_id)->first()->item_code)
			// 	->update([
			// 		'inventory_capsule_lines.updated_by' => CRUDBooster::myId(),
			// 		'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty + $qty")
			// ]);

			// // DB::table('inventory_capsule_lines')->whereNotNull('sub_locations_id')
			// // ->leftJoin('sub_locations', 'sub_locations.id', 'inventory_capsule_lines.sub_locations_id')
			// // ->where('sub_locations.location_id', $gasha_machines)
			// // ->update([
			// // 	'updated_by' => $action_by,
			// // 	'qty' => DB::raw("qty + $qty")
			// // ]);


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
				'gasha_machine'=>$validateGM->gasha_machines_id,
				'qty' => $validateQty,
				'list_of_gm' => $inventory_capsule_lines->where('gasha_machines_id', $gasha_machines->id)->where('qty', '>', 0),
				'list_of_ic' => $list_of_ic,
			]);

		}
	    //By the way, you can still create your own method in here... :) 


	}