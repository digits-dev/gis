<?php namespace App\Http\Controllers\Capsule;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\Models\Submaster\GashaMachines;
	use App\Models\Submaster\Item;
	use App\Models\Capsule\CapsuleRefill;
	use App\Models\Capsule\HistoryCapsule;
	use App\Models\Capsule\InventoryCapsule;
	use App\Models\Capsule\InventoryCapsuleLine;
	use App\Models\Submaster\Counter;
	use App\Models\Submaster\CapsuleActionType;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Reader\Exception;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use Excel;
	use Maatwebsite\Excel\HeadingRowImport;
	use App\Imports\CapsulesImport;
use App\Models\Audit\CollectRrTokenLines;
use Carbon\Carbon;

	class AdminCapsuleRefillsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = in_array(CRUDBooster::myPrivilegeId(), [1, 3, 5, 10, 12]);
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "capsule_refills";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
            $this->col[] = ["label"=>"Reference #","name"=>"reference_number"];
            $this->col[] = ["label"=>"Location","name"=>"locations_id","join"=>"locations,location_name"];
            $this->col[] = ["label"=>"Machine","name"=>"gasha_machines_id","join"=>"gasha_machines,serial_number"];
			$this->col[] = ["label"=>"JAN #","name"=>"item_code"];
			$this->col[] = ["label"=>"Qty","name"=>"qty"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'JAN #','name'=>'item_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Machine','name'=>'gasha_machines_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'gasha_machines,serial_number'];
			$this->form[] = ['label'=>'Qty','name'=>'qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Location','name'=>'locations_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'locations,location_name'];
			$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'cms_users,name'];
			$this->form[] = ["label"=>"Created Date","name"=>"created_at"];
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
			// if(CRUDBooster::getCurrentMethod() == 'getIndex'){
			// 	$this->index_button[] = ["label"=>"Upload Capsule","icon"=>"fa fa-upload","url"=>CRUDBooster::mainpath('capsules-upload'),'color'=>'primary'];
			// }


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
	        if (CRUDBooster::isSuperadmin()) {
				$query->whereNull('capsule_refills.deleted_at')
					->orderBy('capsule_refills.id', 'desc');
			} else if (in_array(CRUDBooster::myPrivilegeId(), [3, 5, 12])) {
				$query->where('capsule_refills.locations_id', CRUDBooster::myLocationId())
					->orderBy('capsule_refills.id', 'desc');
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



	    //By the way, you can still create your own method in here... :)

		public function getAdd() {
			//Create an Auth
			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = [];
			$data['page_title'] = 'Add Data';
			$data['items'] = DB::table('items')
				->leftJoin('inventory_capsules', 'inventory_capsules.item_code', 'items.digits_code')
				->leftJoin('inventory_capsule_lines', 'inventory_capsule_lines.inventory_capsules_id', 'inventory_capsules.id')
				->whereNotNull('inventory_capsule_lines.sub_locations_id')
				->get()
				->toArray();

			$data['machines'] = DB::table('gasha_machines')
				->where('status', 'ACTIVE')
				//TODO: get the locations id from the logged session
				->get()
				->toArray();

			return $this->view('capsule.capsule-refill',$data);
		}

		public function submitCapsuleRefill(Request $request) {
			$data = $request->all();

			// restriction for capsule refill if machine is in collect token progress
			$get_serial_number = GashaMachines::where('serial_number', $data['machine_code'])
				->where('location_id', CRUDBooster::myLocationId())
				->first();

			$check_machince_in_collect_token = CollectRrTokenLines::with('collectTokenHeader.getBay','collectTokenHeader.getStatus','machineSerial')
				->where('gasha_machines_id', $get_serial_number->id)
				->where('line_status', '!=', 13)
				->whereDate('created_at', Carbon::now()->format('Y-m-d'))
				->first();

			if (!empty($check_machince_in_collect_token) && in_array($check_machince_in_collect_token->line_status, ['10', '12', '11'])) {
				return response()->json([
					'invalid_capsule_refill' => 'Collect Token is in progress, Please contact your immediate head to VOID the ongoing transaction of 
												 Collect Token for you to proceed in Capsule Refill if this is urgent. If not please wait until the Collect Token is completed.',
					'collect_token_details' => $check_machince_in_collect_token
				]);
			}

			$item_code = $data['item_code'];
			$machine_serial_number = $data['machine_code'];
			$machine = GashaMachines::where('serial_number',$machine_serial_number)->first();
			$item = Item::where('digits_code', $item_code)->first();
			$qty = preg_replace('/,/', '', $data['qty']);
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();

			if ($machine->location_id != CRUDBooster::myLocationId()) $machine = null;

			// checking if item is found
			if (!$item) {
				return json_encode(['is_missing'=>true, 'missing'=>'Item']);
			// checking if machine is found
			} else if (!$machine) {
				return json_encode(['is_missing'=>true, 'missing'=>'Machine']);
			}

			//Update machine number base on item master
			// GashaMachines::where('serial_number',$machine_serial_number)->update(['no_of_token'=>$item->no_of_tokens]);
			// checking if item and machine has the same no. of tokens
			$is_tally = $item->no_of_tokens == $machine->no_of_token;

			//getting the locations_id from where the scanned machine was deployed
			$locations_id = $machine->location_id;

			// getting the current inventory for this item_code and this location
			$current_inventory = InventoryCapsule::where([
				'item_code' => $item->digits_code2,
				'locations_id' => $locations_id
			])->leftJoin(
				'inventory_capsule_view',
				'inventory_capsule_view.inventory_capsules_id',
				'inventory_capsules.id'
			)->first();

			// check if jan code, machine and location is exist
			$isExistWithMorethanZero = InventoryCapsuleLine::where([
				'inventory_capsules.locations_id' => $locations_id,
				'inventory_capsule_lines.gasha_machines_id' => $machine->id
			])
			->where('inventory_capsule_lines.qty', '>', 0)
			->leftJoin(
				'inventory_capsules',
				'inventory_capsule_lines.inventory_capsules_id',
				'inventory_capsules.id'
			)->exists();

			$isExistJanCode = InventoryCapsuleLine::where([
				'inventory_capsules.item_code' => $item->digits_code2,
				'inventory_capsules.locations_id' => $locations_id,
				'inventory_capsule_lines.gasha_machines_id' => $machine->id
			])->where('inventory_capsule_lines.qty', '>', 0)
			->leftJoin(
				'inventory_capsules',
				'inventory_capsule_lines.inventory_capsules_id',
				'inventory_capsules.id'
			)->exists();
		
			// returning if no. of tokens does not match
			if (!$is_tally) {
				return json_encode([
					'item' => $item,
					'machine' => $machine,
					'is_tally' => $is_tally
				]);
			// returning if there is no current inventory
			} else if (!$current_inventory) {
				return json_encode([
					'is_empty' => true,
				]);
			// returning if the inputted qty is greater than the stockroom qty
			} else if ($current_inventory->stockroom_capsule_qty < $qty) {
				return json_encode([
					'is_sufficient' => false,
				]);
			// returning if jan code, machine and location not exist
			}
			if ((!$isExistWithMorethanZero && $isExistJanCode) || ($isExistWithMorethanZero && !$isExistJanCode)) {
				return json_encode([
					'is_not_exist' => true,
				]);
			}
		
			// getting the 'refill' action type
			$action_type = CapsuleActionType::where(DB::raw('UPPER(description)'), '=', 'REFILL')->first();
			// generating a new reference number
			$reference_number = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
			// inserting capsule refill entry
			$capsule = CapsuleRefill::insert([
				'reference_number' => $reference_number,
				'item_code' => $item_code,
				'gasha_machines_id' => $machine->id,
				'created_at' => $time_stamp,
				'created_by' => $action_by,
				'qty' => $qty,
				'locations_id' => $locations_id,
			]);

			//getting the sublocations
			$sub_locations_id = DB::table('sub_locations')
				->where('location_id', $locations_id)
				->where('description', 'STOCK ROOM')
				->pluck('id')
				->first();

			// creating history for the transaction
			// ====> ledger type
			HistoryCapsule::insert([
				'reference_number' => $reference_number,
				'item_code' => $item->digits_code2,
				'capsule_action_types_id' => $action_type->id,
				'locations_id' => $locations_id,
				'from_sub_locations_id' => $sub_locations_id,
				'to_machines_id' => $machine->id,
				'qty' => "-$qty",
				'created_at' => $time_stamp,
				'created_by' => $action_by
			]);

			HistoryCapsule::insert([
				'reference_number' => $reference_number,
				'item_code' => $item->digits_code2,
				'capsule_action_types_id' => $action_type->id,
				'locations_id' => $locations_id,
				'from_machines_id' => $machine->id,
				'to_sub_locations_id' => $sub_locations_id,
				'qty' => $qty,
				'created_at' => $time_stamp,
				'created_by' => $action_by
			]);

			$inventory_capsules_id = $current_inventory->inventory_capsules_id;

			// getting the machines inventory
			$current_inventory_line = InventoryCapsuleLine::where([
				'inventory_capsules_id' => $inventory_capsules_id,
				'gasha_machines_id' => $machine->id,
			]);

			// checking if there is current inventory
			if (!$current_inventory_line->first()) {
				// inserting a new one if not existing
				$inventory_line_id = $current_inventory_line->insertGetId([
					'inventory_capsules_id' => $inventory_capsules_id,
					'gasha_machines_id' => $machine->id,
					'qty' => $qty,
					'created_by' => $action_by,
					'created_at' => $time_stamp,
				]);
			} else {
				// updating if already exists
				$current_inventory_line->update([
					'qty' => DB::raw("qty + $qty"),
					'updated_at' => $time_stamp,
					'updated_by' => $action_by,
				]);
			}

			// updating the quantity of stock room inventory
			DB::table('inventory_capsule_lines')->whereNotNull('sub_locations_id')
				->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
				->leftJoin('sub_locations', 'sub_locations.id', 'inventory_capsule_lines.sub_locations_id')
				->where('sub_locations.location_id', $locations_id)
				->where('sub_locations.description', 'STOCK ROOM')
				->where('inventory_capsules.item_code', $item->digits_code2)
				->update([
					'inventory_capsule_lines.updated_by' => $action_by,
					'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty - $qty")
				]);

			return json_encode(['item'=>$item, 'machine'=>$machine, 'is_tally'=>$is_tally, 'reference_number' => $reference_number]);
			
		}

		public function getPartnerMachine(Request $request) {
			$data = $request->all();
			$item_code = $data['item_code'];

			$item = DB::table('items')
				->where('digits_code', $item_code)
				->first();

			$machines = InventoryCapsule::getMachine($item->digits_code2);

			return json_encode([
				'item' => $item,
				'machines' => $machines,
				$data,
			]);
		}

		//IMPORT
		public function uploadCapsules() {
			$data['page_title']= 'Capsules Upload';
			return view('import.capsules-import.capsules-upload', $data)->render();
		}

		function downloadCapsulesTemplate() {
			$arrHeader = [
				"jan_no"             => "Jan No",
				"machine_serial"     => "Machine Serial",
				"location"           => "Location",
				"qty"                => "Qty",
			];

			$arrData = [
				"jan_no"             => "4570118023759",
				"machine_serial"     => "PH00001",
				"location"           => "GASHAPON.MITSUKOSHI.BGC.RTL",
				"qty"                => "1",
			];

			$spreadsheet = new Spreadsheet();
			$spreadsheet->getActiveSheet()->fromArray(array_values($arrHeader), null, 'A1');
			$spreadsheet->getActiveSheet()->fromArray($arrData, null, 'A2');
			$filename = "capsules-template-".date('Y-m-d');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
			header('Cache-Control: max-age=0');
			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		}

		public function saveUploadCapsules(Request $request) {
			$path_excel = $request->file('import_file')->store('temp');
			$path = storage_path('app').'/'.$path_excel;
			$headings = array_filter((new HeadingRowImport)->toArray($path)[0][0]);

			if (count($headings) !== 4) {
			    CRUDBooster::redirect(CRUDBooster::mainpath(), 'Template column not match, please refer to downloaded template.', 'danger');
			} else {
				try {

					Excel::import(new CapsulesImport, $path);
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Upload Successfully!"), 'success');

				} catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
				    $failures = $e->failures();

				    $error = [];
				    foreach ($failures as $failure) {
				        $line = $failure->row();
				        foreach ($failure->errors() as $err) {
				            $error[] = $err . " on line: " . $line;
				        }
				    }

				    $errors = collect($error)->unique()->toArray();

				}
				CRUDBooster::redirect(CRUDBooster::mainpath(), $errors[0], 'danger');

			}

		}
	}