<?php namespace App\Http\Controllers\Capsule;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Models\Capsule\InventoryCapsule;
	use App\Models\Capsule\InventoryCapsuleLine;
	use App\Models\Capsule\CapsuleSwapHeader;
	use App\Models\Capsule\CapsuleSwapLines;
	use App\Models\Capsule\HistoryCapsule;
	use App\Models\Submaster\GashaMachines;
	use App\Models\Submaster\Counter;
	use App\Models\Submaster\CapsuleActionType;
	use App\Models\Submaster\Item;
	
	class AdminCapsuleSwapHeadersController extends \crocodicstudio\crudbooster\controllers\CBController {
		private const SWAP = 'SWAP';
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
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "capsule_swap_headers";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference Number","name"=>"reference_number"];
			$this->col[] = ["label"=>"Machine No One","name"=>"machine_no_one"];
			$this->col[] = ["label"=>"Capsule Qty One","name"=>"capsule_qty_one"];
			$this->col[] = ["label"=>"No Of Token One","name"=>"no_of_token_one"];
			$this->col[] = ["label"=>"Machine No Two","name"=>"machine_no_two"];
			$this->col[] = ["label"=>"Capsule Qty Two","name"=>"capsule_qty_two"];
			$this->col[] = ["label"=>"No Of Token Two","name"=>"no_of_token_two"];
			$this->col[] = ["label"=>"Created  By","name"=>"created_by"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Reference Number","name"=>"reference_number","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Machine No One","name"=>"machine_no_one","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Capsule Qty One","name"=>"capsule_qty_one","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"No Of Token One","name"=>"no_of_token_one","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Machine No Two","name"=>"machine_no_two","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Capsule Qty Two","name"=>"capsule_qty_two","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"No Of Token Two","name"=>"no_of_token_two","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Location","name"=>"location","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Created By","name"=>"created_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Updated By","name"=>"updated_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
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
			$fields                = Request::all();
			$machine_one           = $fields['machine_no_one'];
			$capsule_qty_one_total = $fields['capsule_qty_one_total'];
			$no_of_token_one       = $fields['no_of_token_one'];
			$machine_two           = $fields['machine_no_two'];
			$capsule_qty_two_total = $fields['capsule_qty_two_total'];
			$no_of_token_two       = $fields['no_of_token_two'];

			$machine_one_data      = GashaMachines::where('serial_number',$machine_one)->first();
			$machine_two_data      = GashaMachines::where('serial_number',$machine_two)->first();

			if($machine_one_data->no_of_token != $machine_two_data->no_of_token){
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"No match no of token!","danger");
			}

			if($capsule_qty_one_total == 0 || $capsule_qty_two_total == 0){
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"Not allow zero capsule quantity!","danger");
			}

			$postdata['reference_number'] = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
			$postdata['machine_no_one']   = $machine_one;
			$postdata['capsule_qty_one']  = intval(str_replace(',', '', $capsule_qty_one_total));
			$postdata['no_of_token_one']  = $machine_one_data->no_of_token;
			$postdata['machine_no_two']   = $machine_two;
			$postdata['capsule_qty_two']  = intval(str_replace(',', '', $capsule_qty_two_total));
			$postdata['no_of_token_two']  = $machine_two_data->no_of_token;
			$postdata['location']      = CRUDBooster::myLocationId();
			$postdata['created_by']       = CRUDBooster::myId();
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        $fields       = Request::all(); 

			//Machine 1
			$jan_no_one       = $fields['jan_no_one'];
	        $machine_one      = $fields['machine_no_one'];
			$capsule_qty_one  = $fields['capsule_qty_one'];
			$no_of_token_one  = $fields['no_of_token_one'];

			//Machine 2
			$jan_no_two      = $fields['jan_no_two'];
			$machine_two     = $fields['machine_no_two'];
			$capsule_qty_two = $fields['capsule_qty_two'];
			$no_of_token_two = $fields['no_of_token_two'];

			$capsule_swap      = CapsuleSwapHeader::find($id);
			$machine_one_data  = GashaMachines::where('serial_number',$machine_one)->first();
			$machine_two_data  = GashaMachines::where('serial_number',$machine_two)->first();
			$time_stamp        = date('Y-m-d H:i:s');
			$action_by         = CRUDBooster::myId();

			//FROM MACHINE 1 to MACHINE 2 SAVE TO CAPSULE SWAP LINES
			$machineOneInsertLineOut = [];
			$machineOneInsertLineIn = [];
			foreach($jan_no_one as $key => $jan) {		
				//OUT
				$machineOneInsertLineOut[$key]['capsule_swap_id'] = $id;
				$machineOneInsertLineOut[$key]['jan_no']          = (int)$jan;
				$machineOneInsertLineOut[$key]['from_machine']    = $machine_one_data->id;
				$machineOneInsertLineOut[$key]['to_machine']      = $machine_two_data->id;
				$machineOneInsertLineOut[$key]['qty']             = -1 * abs($capsule_qty_one[$key]);
				$machineOneInsertLineOut[$key]['location']     = CRUDBooster::myLocationId();
				$machineOneInsertLineOut[$key]['created_at']      = date('Y-m-d H:i:s');
				//IN
				$machineOneInsertLineIn[$key]['capsule_swap_id'] = $id;
				$machineOneInsertLineIn[$key]['jan_no']          = (int)$jan;
				$machineOneInsertLineIn[$key]['from_machine']    = $machine_two_data->id;
				$machineOneInsertLineIn[$key]['to_machine']      = $machine_one_data->id;
				$machineOneInsertLineIn[$key]['qty']             = $capsule_qty_one[$key];
				$machineOneInsertLineIn[$key]['location']     = CRUDBooster::myLocationId();
				$machineOneInsertLineIn[$key]['created_at']      = date('Y-m-d H:i:s');
			}
			$machineOneMerge = array_merge($machineOneInsertLineOut, $machineOneInsertLineIn);
			CapsuleSwapLines::insert($machineOneMerge);

			//FROM MACHINE 2 to MACHINE 1 SAVE TO CAPSULE SWAP LINES
			$machineTwoInsertLineOut = [];
			$machineTwoInsertLineIn = [];
			foreach($jan_no_two as $key => $jan_two) {	
				//out		
				$machineTwoInsertLineOut[$key]['capsule_swap_id'] = $id;
				$machineTwoInsertLineOut[$key]['jan_no']          = (int)$jan_two;
				$machineTwoInsertLineOut[$key]['from_machine']    = $machine_two_data->id;
				$machineTwoInsertLineOut[$key]['to_machine']      = $machine_one_data->id;
				$machineTwoInsertLineOut[$key]['qty']             = -1 * abs($capsule_qty_two[$key]);
				$machineTwoInsertLineOut[$key]['location']     = CRUDBooster::myLocationId();
				$machineTwoInsertLineOut[$key]['created_at']      = date('Y-m-d H:i:s');
				//IN
				$machineTwoInsertLineIn[$key]['capsule_swap_id'] = $id;
				$machineTwoInsertLineIn[$key]['jan_no']          = (int)$jan_two;
				$machineTwoInsertLineIn[$key]['from_machine']    = $machine_one_data->id;
				$machineTwoInsertLineIn[$key]['to_machine']      = $machine_two_data->id;
				$machineTwoInsertLineIn[$key]['qty']             = $capsule_qty_two[$key];
				$machineTwoInsertLineIn[$key]['location']     = CRUDBooster::myLocationId();
				$machineTwoInsertLineIn[$key]['created_at']      = date('Y-m-d H:i:s');
			}
			$machineTwoMerge = array_merge($machineTwoInsertLineOut, $machineTwoInsertLineIn);
			CapsuleSwapLines::insert($machineTwoMerge);

			// creating history for the transaction
			// ====> ledger type
			// MACHINE ONE INSERT TO MOVEMENT HISTORY
			$action_type = CapsuleActionType::where(DB::raw('UPPER(description)'), '=', self::SWAP)->first();
			$machineOneInsertLineOutToMH = [];
			$machineOneInsertLineInToMH = [];
			foreach($jan_no_one as $key => $jan) {	
				$item_one = Item::where('digits_code', $jan)->first();
				//OUT		
				$machineOneInsertLineOutToMH[$key]['reference_number']        = $capsule_swap->reference_number;
				$machineOneInsertLineOutToMH[$key]['item_code']               = $item_one->digits_code2;
				$machineOneInsertLineOutToMH[$key]['capsule_action_types_id'] = $action_type->id;
				$machineOneInsertLineOutToMH[$key]['locations_id']            = $machine_one_data->location_id;
				$machineOneInsertLineOutToMH[$key]['from_machines_id']        = $machine_one_data->id;
				$machineOneInsertLineOutToMH[$key]['to_machines_id']          = $machine_two_data->id;
				$machineOneInsertLineOutToMH[$key]['qty']                     = -1 * abs($capsule_qty_one[$key]);
				$machineOneInsertLineOutToMH[$key]['created_at']              = $time_stamp;
				$machineOneInsertLineOutToMH[$key]['created_by']              = $action_by;
				//IN
				$machineOneInsertLineInToMH[$key]['reference_number']        = $capsule_swap->reference_number;
				$machineOneInsertLineInToMH[$key]['item_code']               = $item_one->digits_code2;
				$machineOneInsertLineInToMH[$key]['capsule_action_types_id'] = $action_type->id;
				$machineOneInsertLineInToMH[$key]['locations_id']            = $machine_two_data->location_id;
				$machineOneInsertLineInToMH[$key]['from_machines_id']        = $machine_two_data->id;
				$machineOneInsertLineInToMH[$key]['to_machines_id']          = $machine_one_data->id;
				$machineOneInsertLineInToMH[$key]['qty']                     = $capsule_qty_one[$key];
				$machineOneInsertLineInToMH[$key]['created_at']              = $time_stamp;
				$machineOneInsertLineInToMH[$key]['created_by']              = $action_by;
			}
			$machineOneMergeMovementHistory = array_merge($machineOneInsertLineOutToMH, $machineOneInsertLineInToMH);
			HistoryCapsule::insert($machineOneMergeMovementHistory);

			// MACHINE TWO INSERT TO MOVEMENT HISTORY
			$action_type = CapsuleActionType::where(DB::raw('UPPER(description)'), '=', self::SWAP)->first();
			$machineTwoInsertLineOutToMH = [];
			$machineTwoInsertLineInToMH = [];
			foreach($jan_no_two as $key => $jan_two) {	
				$item_two = Item::where('digits_code', $jan_two)->first();
				//OUT		
				$machineTwoInsertLineOutToMH[$key]['reference_number']        = $capsule_swap->reference_number;
				$machineTwoInsertLineOutToMH[$key]['item_code']               = $item_two->digits_code2;
				$machineTwoInsertLineOutToMH[$key]['capsule_action_types_id'] = $action_type->id;
				$machineTwoInsertLineOutToMH[$key]['locations_id']            = $machine_two_data->location_id;
				$machineTwoInsertLineOutToMH[$key]['from_machines_id']        = $machine_two_data->id;
				$machineTwoInsertLineOutToMH[$key]['to_machines_id']          = $machine_one_data->id;
				$machineTwoInsertLineOutToMH[$key]['qty']                     = -1 * abs($capsule_qty_two[$key]);
				$machineTwoInsertLineOutToMH[$key]['created_at']              = $time_stamp;
				$machineTwoInsertLineOutToMH[$key]['created_by']              = $action_by;
				//IN
				$machineTwoInsertLineInToMH[$key]['reference_number']        = $capsule_swap->reference_number;
				$machineTwoInsertLineInToMH[$key]['item_code']               = $item_two->digits_code2;
				$machineTwoInsertLineInToMH[$key]['capsule_action_types_id'] = $action_type->id;
				$machineTwoInsertLineInToMH[$key]['locations_id']            = $machine_one_data->location_id;
				$machineTwoInsertLineInToMH[$key]['from_machines_id']        = $machine_one_data->id;
				$machineTwoInsertLineInToMH[$key]['to_machines_id']          = $machine_two_data->id;
				$machineTwoInsertLineInToMH[$key]['qty']                     = $capsule_qty_two[$key];
				$machineTwoInsertLineInToMH[$key]['created_at']              = $time_stamp;
				$machineTwoInsertLineInToMH[$key]['created_by']              = $action_by;
			}
			$machineTwoMergeMovementHistory = array_merge($machineTwoInsertLineOutToMH, $machineTwoInsertLineInToMH);
			HistoryCapsule::insert($machineTwoMergeMovementHistory);

		 	//machine 1 less the quantity of stock room inventory 
			foreach($jan_no_one as $key => $jan_one_inv) {
				$item_one = Item::where('digits_code', $jan_one_inv)->first();	
				DB::table('inventory_capsule_lines')->whereNotNull('sub_locations_id')
				->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
				->leftJoin('sub_locations', 'sub_locations.id', 'inventory_capsule_lines.sub_locations_id')
				->where('sub_locations.location_id', $machine_one_data->location_id)
				->where('inventory_capsules.item_code', $item_one->digits_code2)
				->update([
					'inventory_capsule_lines.updated_by' => $action_by,
					'inventory_capsule_lines.gasha_machines_id' => $machine_two_data->id
				]);
			}

			//machine 2 less the quantity of stock room inventory 
			foreach($jan_no_two as $key => $jan_two_inv) {	
				$item_two = Item::where('digits_code', $jan_two_inv)->first();
				DB::table('inventory_capsule_lines')->whereNotNull('sub_locations_id')
				->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
				->leftJoin('sub_locations', 'sub_locations.id', 'inventory_capsule_lines.sub_locations_id')
				->where('sub_locations.location_id', $machine_two_data->location_id)
				->where('inventory_capsules.item_code', $item_two->digits_code2)
				->update([
					'inventory_capsule_lines.updated_by' => $action_by,
					'inventory_capsule_lines.gasha_machines_id' => $machine_one_data->id
				]);
			}


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
			$this->cbLoader();
            if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }
			$data = array();
			$data['page_title'] = 'Create Capsule Swap';
			return view('capsule.capsule-swap');
		}

		public function checkMachine(Request $request){
			$fields = Request::all();
			$user_location = CRUDBooster::myLocationId();
			
			// getting the machine
			$machine = GashaMachines::where('serial_number', $fields['machine_code'])->where('location_id', $user_location)->first();
			if (!$machine) {
				return json_encode(['status' => 'error','message' => 'Machine not found!']);
			}
			//get jan code
			$jan_data = InventoryCapsuleLine::where('gasha_machines_id', $machine->id)
			->leftjoin('inventory_capsules','inventory_capsule_lines.inventory_capsules_id','inventory_capsules.id')
			->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
			->get();

			return json_encode([
				'machine_data'=> $machine,
				'jan_data'    => $jan_data
			]);
		}

	}