<?php namespace App\Http\Controllers\Capsule;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\Models\Capsule\InventoryCapsule;
	use App\Models\Capsule\InventoryCapsuleLine;
	use App\Models\Capsule\CapsuleSwapHeader;
	use App\Models\Capsule\CapsuleSwapLines;
	use App\Models\Capsule\HistoryCapsule;
	use App\Models\Capsule\CapsuleSales;
	use App\Models\Submaster\GashaMachines;
	use App\Models\Submaster\Counter;
	use App\Models\Submaster\CapsuleActionType;
	use App\Models\Submaster\Item;
	use App\Models\Submaster\SalesType;

	class AdminCapsuleSwapHeadersController extends \crocodicstudio\crudbooster\controllers\CBController {
		private const SWAP = 'SWAP';
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "capsule_swap_headers";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference #","name"=>"reference_number"];
			$this->col[] = ["label"=>"Machine No One","name"=>"machine_no_one"];
			$this->col[] = ["label"=>"Capsule Qty One","name"=>"capsule_qty_one"];
			$this->col[] = ["label"=>"No Of Token One","name"=>"no_of_token_one"];
			$this->col[] = ["label"=>"Machine No Two","name"=>"machine_no_two"];
			$this->col[] = ["label"=>"Capsule Qty Two","name"=>"capsule_qty_two"];
			$this->col[] = ["label"=>"No Of Token Two","name"=>"no_of_token_two"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

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

		}

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after add public static function called
	    | ----------------------------------------------------------------------
	    | @id = last insert id
	    |
	    */
	    public function hook_after_add($id) {

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
			return view('capsule.capsule-swap', $data);
		}

		public function checkMachine(Request $request){
			$fields = $request->all();
			$user_location = CRUDBooster::myLocationId();

			// getting the machine
			$machine = GashaMachines::getMachineByLocation($fields['machine_code'],$user_location);
			if (!$machine) {
				return json_encode(['status' => 'error','message' => 'Machine not found!']);
			}
			//get jan code
			$jan_data = InventoryCapsuleLine::getInventoryByMachine($machine->id);

			return json_encode([
				'machine_data'=> $machine,
				'jan_data'    => $jan_data
			]);
		}

		public function saveSwap(Request $request){
			$fields                = $request->all();

			$machine_one           = $fields['machine_no_one'];
			$capsule_qty_one_total = $fields['capsule_qty_one_total'];
			$no_of_token_one       = $fields['no_of_token_one'];
			$machine_two           = $fields['machine_no_two'];
			$capsule_qty_two_total = $fields['capsule_qty_two_total'];
			$no_of_token_two       = $fields['no_of_token_two'];

			//Machine 1
			$jan_no_one            = $fields['jan_no_one'];
			//Machine 2
			$jan_no_two            = $fields['jan_no_two'];

			$time_stamp            = date('Y-m-d H:i:s');
			$action_by             = CRUDBooster::myId();
			$my_locations_id       = CRUDBooster::myLocationId();

			$machine_one_data      = GashaMachines::where('serial_number',$machine_one)->first();
			$machine_two_data      = GashaMachines::where('serial_number',$machine_two)->first();
			$capsule_action_types_id = CapsuleActionType::where(DB::raw('UPPER(description)'), 'SWAP')->where('status', 'ACTIVE')->pluck('id')->first();
			$sales_types_id = SalesType::where(DB::raw('UPPER(description)'), 'SWAP')->where('status', 'ACTIVE')->pluck('id')->first();

			//match no of token per machine
			if($machine_one_data->no_of_token != $machine_two_data->no_of_token){
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"No match no of token!","danger");
			}

			//match location of user and machine
			if($machine_one_data->location_id && $machine_two_data->location_id != $my_locations_id){
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"Wrong location!","danger");
			}

			if($capsule_qty_one_total == 0 || $capsule_qty_two_total == 0){
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"Not allow zero capsule quantity!","danger");
			}

			$reference_number = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);

			$capsule_swap_id = CapsuleSwapHeader::insertGetId([
				'reference_number' => $reference_number,
				'machine_no_one'   => $machine_one,
				'capsule_qty_one'  => $capsule_qty_one_total,
				'no_of_token_one'  => $machine_one_data->no_of_token,
				'machine_no_two'   => $machine_two,
				'capsule_qty_two'  => $capsule_qty_two_total,
				'no_of_token_two'  => $machine_two_data->no_of_token,
				'location'         => $my_locations_id,
				'created_by'       => $action_by,
				'created_at'       => $time_stamp
			]);

			//MACHINE ONE EXISTING INVENTORY
			$current_inv_machine_one = [];
			 foreach ($jan_no_one as $key => $item) {
				// getting the current inventory
				$system_inv = InventoryCapsuleLine::where('gasha_machines_id', $machine_one_data->id)
					->select('inventory_capsule_lines.qty', 'items.digits_code')
					->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
					->leftJoin('items', 'items.digits_code2', 'ic.item_code')
					->where('ic.locations_id', $my_locations_id)
					->where('items.digits_code', $item['jan_no_one'])
					->first();

				// returning if inputted qty is greater than inventory qty
				if ($item['capsule_qty_one'] > $system_inv->qty) {
					$response['invalid_qty'] = true;
					return json_encode($response);
				}

				$current_inv_machine_one[$system_inv->digits_code] = $system_inv->qty;
			}

			//FROM MACHINE 1 to MACHINE 2 SAVE TO CAPSULE SWAP LINES
			$machineOneInsertLineOut = [];
			$machineOneInsertLineIn = [];
			$machineOneInsertLineOutToMH = [];
			$machineOneInsertLineInToMH = [];
			foreach($jan_no_one as $key => $jan) {
				$inputted_qty_one = $jan['capsule_qty_one'];
				//OUT
				$machineOneInsertLineOut[$key]['capsule_swap_id'] = $capsule_swap_id;
				$machineOneInsertLineOut[$key]['jan_no']          = $jan['jan_no_one'];
				$machineOneInsertLineOut[$key]['from_machine']    = $machine_one_data->id;
				$machineOneInsertLineOut[$key]['to_machine']      = $machine_two_data->id;
				$machineOneInsertLineOut[$key]['qty']             = $inputted_qty_one;
				$machineOneInsertLineOut[$key]['location']        = $my_locations_id;
				$machineOneInsertLineOut[$key]['created_at']      = $time_stamp;

				//HISTORY INSERT
				$item_one = Item::where('digits_code', $jan['jan_no_one'])->first();
				if($inputted_qty_one){
					//OUT
					$machineOneInsertLineOutToMH[$key]['reference_number']        = $reference_number;
					$machineOneInsertLineOutToMH[$key]['item_code']               = $item_one->digits_code2;
					$machineOneInsertLineOutToMH[$key]['capsule_action_types_id'] = $capsule_action_types_id;
					$machineOneInsertLineOutToMH[$key]['locations_id']            = $machine_one_data->location_id;
					$machineOneInsertLineOutToMH[$key]['from_machines_id']        = $machine_one_data->id;
					$machineOneInsertLineOutToMH[$key]['to_machines_id']          = $machine_two_data->id;
					$machineOneInsertLineOutToMH[$key]['qty']                     = -1 * abs($inputted_qty_one);
					$machineOneInsertLineOutToMH[$key]['created_at']              = $time_stamp;
					$machineOneInsertLineOutToMH[$key]['created_by']              = $action_by;
					//IN
					$machineOneInsertLineInToMH[$key]['reference_number']        = $reference_number;
					$machineOneInsertLineInToMH[$key]['item_code']               = $item_one->digits_code2;
					$machineOneInsertLineInToMH[$key]['capsule_action_types_id'] = $capsule_action_types_id;
					$machineOneInsertLineInToMH[$key]['locations_id']            = $machine_two_data->location_id;
					$machineOneInsertLineInToMH[$key]['from_machines_id']        = $machine_two_data->id;
					$machineOneInsertLineInToMH[$key]['to_machines_id']          = $machine_one_data->id;
					$machineOneInsertLineInToMH[$key]['qty']                     = $inputted_qty_one;
					$machineOneInsertLineInToMH[$key]['created_at']              = $time_stamp;
					$machineOneInsertLineInToMH[$key]['created_by']              = $action_by;
				}


				$system_qty = $current_inv_machine_one[$jan['jan_no_one']];
				$sales_qty = $system_qty - $inputted_qty_one;

				//INSERT SALES
				if ($sales_qty) {
					CapsuleSales::insert([
						'reference_number'  => $reference_number,
						'item_code'         => $jan['jan_no_one'],
						'gasha_machines_id' => $machine_one_data->id,
						'locations_id'      => $my_locations_id,
						'qty'               => $sales_qty,
						'sales_type_id'     => $sales_types_id,
						'created_by'        => $action_by,
						'created_at'        => $time_stamp
					]);
				}
			}

			//insert machine one body
			$machineOneMerge = array_merge($machineOneInsertLineOut, $machineOneInsertLineIn);
			CapsuleSwapLines::insert($machineOneMerge);
			//insert history
			$machineOneMergeMovementHistory = array_merge($machineOneInsertLineOutToMH, $machineOneInsertLineInToMH);
			HistoryCapsule::insert($machineOneMergeMovementHistory);

			//FROM MACHINE 2 to MACHINE 1 SAVE TO CAPSULE SWAP LINES
			$machineTwoInsertLineOut = [];
			$machineTwoInsertLineIn = [];
			$machineTwoInsertLineOutToMH = [];
			$machineTwoInsertLineInToMH = [];

			//MACHINE 2 EXISTING INVENTORY
			$current_inv_machine_two = [];
			 foreach ($jan_no_two as $key => $item_two) {
				// getting the current inventory
				$system_inv_two = InventoryCapsuleLine::where('gasha_machines_id', $machine_two_data->id)
					->select('inventory_capsule_lines.qty', 'items.digits_code')
					->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
					->leftJoin('items', 'items.digits_code2', 'ic.item_code')
					->where('ic.locations_id', $my_locations_id)
					->where('items.digits_code', $item_two['jan_no_two'])
					->first();

				// returning if inputted qty is greater than inventory qty
				if ($item_two['capsule_qty_two'] > $system_inv_two->qty) {
					$response['invalid_qty'] = true;
					return json_encode($response);
				}

				$current_inv_machine_two[$system_inv_two->digits_code] = $system_inv_two->qty;
			}

			foreach($jan_no_two as $key => $jan_two) {
				$inputted_qty_two = $jan_two['capsule_qty_two'];
				//out
				$machineTwoInsertLineOut[$key]['capsule_swap_id'] = $capsule_swap_id;
				$machineTwoInsertLineOut[$key]['jan_no']          = $jan_two['jan_no_two'];
				$machineTwoInsertLineOut[$key]['from_machine']    = $machine_two_data->id;
				$machineTwoInsertLineOut[$key]['to_machine']      = $machine_one_data->id;
				$machineTwoInsertLineOut[$key]['qty']             = $inputted_qty_two;
				$machineTwoInsertLineOut[$key]['location']        = $my_locations_id;
				$machineTwoInsertLineOut[$key]['created_at']      = $time_stamp;

				//HISTORY
				$item_two = Item::where('digits_code', $jan_two['jan_no_two'])->first();
				if($inputted_qty_two){
					//OUT
					$machineTwoInsertLineOutToMH[$key]['reference_number']        = $reference_number;
					$machineTwoInsertLineOutToMH[$key]['item_code']               = $item_two->digits_code2;
					$machineTwoInsertLineOutToMH[$key]['capsule_action_types_id'] = $capsule_action_types_id;
					$machineTwoInsertLineOutToMH[$key]['locations_id']            = $machine_two_data->location_id;
					$machineTwoInsertLineOutToMH[$key]['from_machines_id']        = $machine_two_data->id;
					$machineTwoInsertLineOutToMH[$key]['to_machines_id']          = $machine_one_data->id;
					$machineTwoInsertLineOutToMH[$key]['qty']                     = -1 * abs($inputted_qty_two);
					$machineTwoInsertLineOutToMH[$key]['created_at']              = $time_stamp;
					$machineTwoInsertLineOutToMH[$key]['created_by']              = $action_by;
					//IN
					$machineTwoInsertLineInToMH[$key]['reference_number']        = $reference_number;
					$machineTwoInsertLineInToMH[$key]['item_code']               = $item_two->digits_code2;
					$machineTwoInsertLineInToMH[$key]['capsule_action_types_id'] = $capsule_action_types_id;
					$machineTwoInsertLineInToMH[$key]['locations_id']            = $machine_one_data->location_id;
					$machineTwoInsertLineInToMH[$key]['from_machines_id']        = $machine_one_data->id;
					$machineTwoInsertLineInToMH[$key]['to_machines_id']          = $machine_two_data->id;
					$machineTwoInsertLineInToMH[$key]['qty']                     = $inputted_qty_two;
					$machineTwoInsertLineInToMH[$key]['created_at']              = $time_stamp;
					$machineTwoInsertLineInToMH[$key]['created_by']              = $action_by;
				}

				$system_qty_two = $current_inv_machine_two[$jan_two['jan_no_two']];
				$sales_qty_two = $system_qty_two - $inputted_qty_two;

				if ($sales_qty_two) {
					CapsuleSales::insert([
						'reference_number'  => $reference_number,
						'item_code'         => $jan_two['jan_no_two'],
						'gasha_machines_id' => $machine_two_data->id,
						'locations_id'      => $my_locations_id,
						'qty'               => $sales_qty_two,
						'sales_type_id'     => $sales_types_id,
						'created_by'        => $action_by,
						'created_at'        => $time_stamp
					]);
				}

			}

			//insert body machine 2
			$machineTwoMerge = array_merge($machineTwoInsertLineOut, $machineTwoInsertLineIn);
			CapsuleSwapLines::insert($machineTwoMerge);
			//insert history machine 2
			$machineTwoMergeMovementHistory = array_merge($machineTwoInsertLineOutToMH, $machineTwoInsertLineInToMH);
			HistoryCapsule::insert($machineTwoMergeMovementHistory);

			//MACHINE ONE INVENTORY PROCESS
			foreach($jan_no_one as $key => $jan){
				//UPDATE OR INSERT IN INVENTORY
				$inputted_qty_one = $jan['capsule_qty_one'];
				$digits_code = Item::where('digits_code', $jan['jan_no_one'])->pluck('digits_code2')->first();
				//updating inventory qty of from machine 1 to 0
				InventoryCapsuleLine::where('gasha_machines_id', $machine_one_data->id)
				->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
				->where('ic.locations_id', $my_locations_id)
				->where('ic.item_code', $digits_code)
				->update([
					'inventory_capsule_lines.qty' => 0,
					'inventory_capsule_lines.updated_by' => $action_by,
					'inventory_capsule_lines.updated_at' => $time_stamp
				]);

				$is_existing_Machine_one = InventoryCapsuleLine::where('gasha_machines_id', $machine_two_data->id)
					->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
					->where('ic.locations_id', $my_locations_id)
					->where('ic.item_code', $digits_code)
					->exists();

				if ($is_existing_Machine_one) {
					// updating the qty if existing
					InventoryCapsuleLine::where('gasha_machines_id', $machine_two_data->id)
						->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
						->where('ic.locations_id', $my_locations_id)
						->where('ic.item_code', $digits_code)
						->update([
							'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty + $inputted_qty_one"),
							'inventory_capsule_lines.updated_by' => $action_by,
							'inventory_capsule_lines.updated_at' => $time_stamp
						]);

				} else {
					$inventory_capsules_id = InventoryCapsule::where([
						'item_code' => $digits_code,
						'locations_id' => $my_locations_id,
					])->pluck('id')->first();

					if (!$inventory_capsules_id) {
						// inserting a new entry for inventory capsule if not existing
						$inventory_capsules_id = InventoryCapsule::insertGetId([
							'item_code'    => $digits_code,
							'locations_id' => $my_locations_id,
							'created_by'   => $action_by,
							'created_at'   => $time_stamp,
						]);
					}

					// inserting a new entry for inventory capsule lines
					InventoryCapsuleLine::insert([
						'inventory_capsules_id' => $inventory_capsules_id,
						'gasha_machines_id'     => $machine_two_data->id,
						'qty'                   => $inputted_qty_one,
						'created_by'            => $action_by,
						'created_at'            => $time_stamp,
					]);
				}
			}

			//GET SAME JAN NO IN MACHINE 1
			$sameJanNo = [];
			foreach ($jan_no_one as $key => $item) {
				if($item['capsule_qty_one']){
					$sameJanNo[] = $item;
				}
			}

			$checkJanColumn = array_column($sameJanNo, 'jan_no_one');
			//MACHINE TWO INVENTORY PROCESS
			foreach($jan_no_two as $key => $jan_two) {
				//UPDATE OR CREATE INVENTORY
				$digits_code_two = Item::where('digits_code', $jan_two['jan_no_two'])->pluck('digits_code2')->first();
				$inputted_qty_two = $jan_two['capsule_qty_two'];
				$saveSalesQty = CapsuleSales::where('item_code', $jan_two['jan_no_two'])
											->where('gasha_machines_id', $machine_two_data->id)
											->where('reference_number', $reference_number)
											->where('locations_id', $my_locations_id)
											->pluck('qty')->first();
				$existingQty = $inputted_qty_two + $saveSalesQty;
				//updating inventory qty of from machine 2 to 0

				if(in_array($jan_two['jan_no_two'], $checkJanColumn)){
					InventoryCapsuleLine::where('gasha_machines_id', $machine_two_data->id)
					->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
					->where('ic.locations_id', $my_locations_id)
					->where('ic.item_code', $digits_code_two)
					->update([
						'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty - $existingQty"),
						'inventory_capsule_lines.updated_by' => $action_by,
						'inventory_capsule_lines.updated_at' => $time_stamp
					]);
				}else{
					InventoryCapsuleLine::where('gasha_machines_id', $machine_two_data->id)
					->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
					->where('ic.locations_id', $my_locations_id)
					->where('ic.item_code', $digits_code_two)
					->update([
						'inventory_capsule_lines.qty' => 0,
						'inventory_capsule_lines.updated_by' => $action_by,
						'inventory_capsule_lines.updated_at' => $time_stamp
					]);
				}

				$is_existing_Machine_two = InventoryCapsuleLine::where('gasha_machines_id', $machine_one_data->id)
					->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
					->where('ic.locations_id', $my_locations_id)
					->where('ic.item_code', $digits_code_two)
					->exists();

				if ($is_existing_Machine_two) {
					// updating the qty if existing
					InventoryCapsuleLine::where('gasha_machines_id', $machine_one_data->id)
						->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
						->where('ic.locations_id', $my_locations_id)
						->where('ic.item_code', $digits_code_two)
						->update([
							'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty + $inputted_qty_two"),
							'inventory_capsule_lines.updated_by' => $action_by,
							'inventory_capsule_lines.updated_at' => $time_stamp
						]);

				} else {
					$inventory_capsules_id = InventoryCapsule::where([
						'item_code' => $digits_code_two,
						'locations_id' => $my_locations_id,
					])->pluck('id')->first();

					if (!$inventory_capsules_id) {
						// inserting a new entry for inventory capsule if not existing
						$inventory_capsules_id = InventoryCapsule::insertGetId([
							'item_code'    => $digits_code_two,
							'locations_id' => $my_locations_id,
							'created_by'   => $action_by,
							'created_at'   => $time_stamp,
						]);
					}

					// inserting a new entry for inventory capsule lines
					InventoryCapsuleLine::insert([
						'inventory_capsules_id' => $inventory_capsules_id,
						'gasha_machines_id'     => $machine_one_data->id,
						'qty'                   => $inputted_qty_two,
						'created_by'            => $action_by,
						'created_at'            => $time_stamp,
					]);
				}

			}

			$machine_one_after = InventoryCapsuleLine::where('gasha_machines_id', $machine_one_data->id)
				->where('inventory_capsule_lines.qty', '>', '0')
				->leftJoin('inventory_capsules as ic', 'ic.id' , 'inventory_capsule_lines.inventory_capsules_id')
				->leftJoin('items', 'items.digits_code2', 'ic.item_code')
				->select(
					'inventory_capsule_lines.*',
					'items.digits_code as item_code',
					'items.item_description'
				)->get();

			$machine_two_after = InventoryCapsuleLine::where('gasha_machines_id', $machine_two_data->id)
				->where('inventory_capsule_lines.qty', '>', '0')
				->leftJoin('inventory_capsules as ic', 'ic.id' , 'inventory_capsule_lines.inventory_capsules_id')
				->leftJoin('items', 'items.digits_code2', 'ic.item_code')
				->select(
					'inventory_capsule_lines.*',
					'items.digits_code as item_code',
					'items.item_description'
				)->get();

			$response['success'] = true;
			$response['reference_number']  = $reference_number;
			$response['machine_one_after'] = $machine_one_after;
			$response['machine_two_after'] = $machine_two_after;
			$response['redirect_link']     = CRUDBooster::mainpath();
			return json_encode($response);
		}

		public function getDetail($id){
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

			$data = array();
			$data['page_title'] = 'Capsule Swap Details';
			$data['detail_header'] = CapsuleSwapHeader::detail($id);
			$data['detail_body']   = CapsuleSwapLines::detailBody($id);

			return $this->view("capsule.capsule-swap-detail", $data);
		}

	}
