<?php namespace App\Http\Controllers\Capsule;

use App\Exports\CapsuleMergeExport;
use App\Models\Capsule\CapsuleMerge;
	use App\Models\Capsule\CapsuleMergeLine;
	use App\Models\Capsule\CapsuleSales;
	use App\Models\Capsule\HistoryCapsule;
	use App\Models\Capsule\InventoryCapsule;
	use App\Models\Capsule\InventoryCapsuleLine;
	use App\Models\Submaster\CapsuleActionType;
	use App\Models\Submaster\Counter;
	use App\Models\Submaster\GashaMachines;
	use App\Models\Submaster\Item;
	use App\Models\Submaster\SalesType;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
use Maatwebsite\Excel\Facades\Excel;

	class AdminCapsuleMergesController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_export = false;
			$this->table = "capsule_merges";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference #","name"=>"reference_number"];
			$this->col[] = ["label"=>"From Machine","name"=>"from_machines_id","join"=>"gasha_machines,serial_number"];
			$this->col[] = ["label"=>"To Machine","name"=>"to_machines_id","join"=>"gasha_machines,serial_number"];
			$this->col[] = ["label"=>"Location","name"=>"locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

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
			if (CRUDBooster::getCurrentMethod() == 'getIndex') {
				$this->index_button[] = [
					"title"=>"Export Data",
					"label"=>"Export Data",
					"icon"=>"fa fa-upload",
					"color"=>"primary",
					"url"=>"javascript:showExport()",
				];
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
	        $this->script_js = "
				function showExport() {
					$('#modal-export').modal('show');
				}	
			";


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
	        $this->post_index_html = "
			<div class='modal fade' tabindex='-1' role='dialog' id='modal-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export ".CRUDBooster::getCurrentModule()->name."</h4>
						</div>

						<form method='post' target='_blank' action=".route('capsule_merge_export').">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export ".CRUDBooster::getCurrentModule()->name ." - ".date('Y-m-d H:i:s')."'/>
                            </div>
						</div>
						<div class='modal-footer' align='right'>
                            <button class='btn btn-default' type='button' data-dismiss='modal'>Close</button>
                            <button class='btn btn-primary btn-submit' type='submit'>Submit</button>
                        </div>
                    </form>
					</div>
				</div>
			</div>
			";



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
	        if (in_array(CRUDBooster::myPrivilegeId(), [1, 2, 4, 6, 7, 8, 14])) {
				$query->whereNull('capsule_merges.deleted_at')
					->orderBy('capsule_merges.created_at', 'desc');
			} else if (in_array(CRUDBooster::myPrivilegeId(), [3, 5])) {
				$query->where('capsule_merges.locations_id', CRUDBooster::myLocationId())
					->orderBy('capsule_merges.created_at', 'desc');
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
			$data['page_title'] = 'Create Capsule Merge';
			return view('capsule.capsule-merge-add', $data);
		}

		public function checkMachines(Request $request) {

			$data = $request->all();
			$user_location = CRUDBooster::myLocationId();

            $from_machine = GashaMachines::getMachineByLocation($data['from_machine'], $user_location);
            $to_machine = GashaMachines::getMachineByLocation($data['to_machine'], $user_location);

			$is_existing = $from_machine->exists() && $to_machine->exists();

			if (!$is_existing) {
				return json_encode([
					'missing_from' => !$from_machine->exists(),
					'missing_to' => !$to_machine->exists(),
				]);
			}

			// Getting Inventory Capsule Lines from the machine 'from'
			$icl_from = InventoryCapsuleLine::getInventoryByMachine($from_machine->id);

			// Getting Inventory Capsule Lines from the machine 'to'
			$icl_to = InventoryCapsuleLine::getInventoryByMachine($to_machine->id);

			if ($from_machine->no_of_token != $to_machine->no_of_token) {
				return json_encode([
					'is_tally' => false,
					'from_machine' => $from_machine,
					'to_machine' => $to_machine,
				]);
			}

			return json_encode([
				'gm_list_from' => $icl_from,
				'gm_list_to' => $icl_to,
				'from_machine' => $from_machine,
				'to_machine' => $to_machine
			]);
		}

		public function submitMerge(Request $request) {
			$data = $request->all();
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$my_locations_id = CRUDBooster::myLocationId();
			$machine_from = GashaMachines::where('serial_number', $data['machine_from'])->first();
			$machine_to = GashaMachines::where('serial_number', $data['machine_to'])->first();

			$capsule_action_types_id = CapsuleActionType::where(DB::raw('UPPER(description)'), 'MERGE')
				->where('status', 'ACTIVE')
				->pluck('id')
				->first();

			$sales_types_id = SalesType::where(DB::raw('UPPER(description)'), 'MERGE')
				->where('status', 'ACTIVE')
				->pluck('id')
				->first();

			$is_tally = $machine_from->no_of_token == $machine_to->no_of_token;
			$response = [
				'machine_from' => $machine_from,
				'machine_to' => $machine_to,
				'is_tally' => $is_tally,
			];

			$current_inv = [];

			// returning if no_of_token of machines mismatched
			if (!$is_tally) {
				return json_encode($response);
			}

			// returning if machines location id is not equal to user's location
			if ($machine_from->location_id != $my_locations_id ||
				$machine_to->location_id != $my_locations_id) {
				$response['wrong_location'] = true;
				return json_encode($response);
			}

			foreach ($data['items'] as $key => $item) {
				// getting the current inventory
				$system_inv = InventoryCapsuleLine::where('gasha_machines_id', $machine_from->id)
					->select('inventory_capsule_lines.qty', 'items.digits_code', 'items.item_description')
					->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
					->leftJoin('items', 'items.digits_code2', 'ic.item_code')
					->where('ic.locations_id', $my_locations_id)
					->where('items.digits_code', $item['item_code'])
					->first();

				$data['items'][$key]['item_description'] = $system_inv['item_description'];

				// returning if inputted qty is greater than inventory qty
				if ($item['qty'] > $system_inv->qty) {
					$response['invalid_qty'] = true;
					return json_encode($response);
				}

				$current_inv[$system_inv->digits_code] = $system_inv->qty;
			}

			// generating new ref number
			$reference_number = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);

			// inserting new entry for capsule merge
			$capsule_merges_id = CapsuleMerge::insertGetId([
				'reference_number' => $reference_number,
				'from_machines_id' => $machine_from->id,
				'to_machines_id' => $machine_to->id,
				'locations_id' => $my_locations_id,
				'created_by' => $action_by,
				'created_at' => $time_stamp
			]);

			// looping through nested items from request
			foreach ($data['items'] as $item) {
				$digits_code = Item::where('digits_code', $item['item_code'])
					->pluck('digits_code2')
					->first();
				$inputted_qty = $item['qty'];
				$system_qty = $current_inv[$item['item_code']];
				$sales_qty = $system_qty - $inputted_qty;

				//inserting new entry for capsule merge line
				CapsuleMergeLine::insert([
					'capsule_merges_id' => $capsule_merges_id,
					'item_code' => $item['item_code'],
					'qty' => $inputted_qty,
					'created_by' => $action_by,
					'created_at' => $time_stamp,
				]);

				// updating inventory qty of 'from machine' to 0
				InventoryCapsuleLine::where('gasha_machines_id', $machine_from->id)
					->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
					->where('ic.locations_id', $my_locations_id)
					->where('ic.item_code', $digits_code)
					->update([
						'qty' => 0,
						'inventory_capsule_lines.updated_at' => $time_stamp,
						'inventory_capsule_lines.updated_by' => $action_by,
						'ic.updated_at' => $time_stamp,
						'ic.updated_by' => $action_by,
					]);

				// checking if current inventory for the 'to machine' exists
				$is_existing = InventoryCapsuleLine::where('gasha_machines_id', $machine_to->id)
					->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
					->where('ic.locations_id', $my_locations_id)
					->where('ic.item_code', $digits_code)
					->exists();

				if ($is_existing) {
					// updating the qty if existing
					InventoryCapsuleLine::where('gasha_machines_id', $machine_to->id)
						->leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
						->where('ic.locations_id', $my_locations_id)
						->where('ic.item_code', $digits_code)
						->update([
							'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty + $inputted_qty"),
							'inventory_capsule_lines.updated_by' => $action_by,
							'inventory_capsule_lines.updated_at' => $time_stamp,
						]);
				} else {
					$inventory_capsules_id = InventoryCapsule::where([
						'item_code' => $digits_code,
						'locations_id' => $my_locations_id,
					])->pluck('id')->first();

					if (!$inventory_capsules_id) {
						// inserting a new entry for inventory capsule if not existing
						$inventory_capsules_id = InventoryCapsule::insertGetId([
							'item_code' => $digits_code,
							'locations_id' => $my_locations_id,
							'created_by' => $action_by,
							'created_at' => $time_stamp,
						]);
					}

					// inserting a new entry for inventory capsule lines
					InventoryCapsuleLine::insert([
						'inventory_capsules_id' => $inventory_capsules_id,
						'gasha_machines_id' => $machine_to->id,
						'qty' => $inputted_qty,
						'created_by' => $action_by,
						'created_at' => $time_stamp,
					]);
				}

				if ($item['qty']) {
					HistoryCapsule::insert([
						'reference_number' => $reference_number,
						'item_code' => $digits_code,
						'capsule_action_types_id' => $capsule_action_types_id,
						'qty' => "-$inputted_qty",
						'locations_id' => $my_locations_id,
						'gasha_machines_id' => $machine_from->id,
						'from_machines_id' => $machine_from->id,
						'to_machines_id' => $machine_to->id,
						'created_by' => $action_by,
						'created_at' => $time_stamp,
					]);

					HistoryCapsule::insert([
						'reference_number' => $reference_number,
						'item_code' => $digits_code,
						'capsule_action_types_id' => $capsule_action_types_id,
						'qty' => "$inputted_qty",
						'locations_id' => $my_locations_id,
						'gasha_machines_id' => $machine_from->id,
						'to_machines_id' => $machine_from->id,
						'from_machines_id' => $machine_to->id,
						'created_by' => $action_by,
						'created_at' => $time_stamp,
					]);
				}

				if ($sales_qty) {
					CapsuleSales::insert([
						'reference_number' => $reference_number,
						'item_code' => $item['item_code'],
						'gasha_machines_id' => $machine_from->id,
						'locations_id' => $my_locations_id,
						'qty' => $sales_qty,
						'sales_type_id' => $sales_types_id,
						'created_by' => $action_by,
						'created_at' => $time_stamp
					]);
				}
			}

			$response['items'] = $data['items'];
			$response['success'] = true;
			$response['reference_number'] = $reference_number;
			return json_encode($response);
		}

		public function getDetail($id) {

			if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = [];
			$data['page_title'] = 'Capsule Merge';
			$data['capsule_merge'] = CapsuleMerge::details($id)->first();
			$data['capsule_lines'] = CapsuleMergeLine::details($id)->get();

			return $this->view('capsule.capsule-merge-view',$data);
		}

		public function exportData(Request $request) {
			$filename = $request->input('filename');
			return Excel::download(new CapsuleMergeExport, $filename.'.csv');
		}

	}
