<?php namespace App\Http\Controllers\Audit;

	use App\Exports\CycleCountExport;
	use App\Models\Audit\CycleCount;
    use App\Models\Audit\CycleCountLine;
    use App\Models\Capsule\CapsuleSales;
    use App\Models\Capsule\HistoryCapsule;
    use App\Models\Capsule\InventoryCapsule;
    use App\Models\Capsule\InventoryCapsuleLine;
    use App\Models\CmsModels\CmsModule;
    use App\Models\Submaster\CapsuleActionType;
    use App\Models\Submaster\Counter;
    use App\Models\Submaster\GashaMachines;
    use App\Models\Submaster\Item;
    use App\Models\Submaster\Locations;
    use App\Models\Submaster\SalesType;
	use App\Models\Submaster\SubLocations;
	use Carbon\Carbon;
	use Illuminate\Support\Facades\File;
	use Excel;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Reader\Exception;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\IOFactory;
    use Session;
    use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\Jobs\CycleCountImportJob;
	use Illuminate\Http\UploadedFile;

	class AdminCycleCountsController extends \crocodicstudio\crudbooster\controllers\CBController {
		private $forApproval;
		private $closed;
        private const CYCLE_COUNT_ACTION = 'Cycle Count';
        private const CYCLE_SALE_TYPE = 'CYCLE COUNT';
        private const STOCK_ROOM = 'STOCK ROOM';

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->forApproval =  9;
			$this->closed      =  4;
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "reference_number";
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
			$this->table = "cycle_counts";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference #","name"=>"reference_number"];
			$this->col[] = ["label"=>"Location","name"=>"locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
            $this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Status","name"=>"header_status","join"=>"statuses,status_description"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Reference #','name'=>'reference_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Locations','name'=>'locations_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'locations,location_name'];
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
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('get-edit/[id]'),'icon'=>'fa fa-pencil', 'showIf'=>'[header_status] == "'.$this->forApproval.'"','color'=>'success'];	
			}

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
            if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				if(in_array(CRUDBooster::myPrivilegeId(),[1,4,14])){
					$this->index_button[] = ["label"=>"Count (Floor)","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add'),"color"=>"success"];
                    $this->index_button[] = ["label"=>"Count (Stock Room)","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-cycle-count-sr'),"color"=>"success"];
					$this->index_button[] = [
						"title"=>"Export Data",
						"label"=>"Export Data",
						"icon"=>"fa fa-upload",
						"color"=>"primary",
						"url"=>"javascript:showExport()",
					];
				}
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
            $this->script_js = "
				$(function(){
					$('body').addClass('sidebar-collapse');
					$('#table_dashboard').on('cut copy paste', function (e) {
						e.preventDefault();
						return false;
					});
				});

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

						<form method='post' target='_blank' action=".route('cycle_count_export').">
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
	    	$forApproval = DB::table('statuses')->where('id', $this->forApproval)->value('status_description');     
			$closed      = DB::table('statuses')->where('id', $this->closed)->value('status_description');   
			  
			if($column_index == 5){
				if($column_value == $forApproval){
					$column_value = '<span class="label label-warning">'.$forApproval.'</span>';
				}else if($column_value == $closed){
					$column_value = '<span class="label label-success">'.$closed.'</span>';
				}
			}
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
            $this->cbLoader();
			if(!CRUDBooster::isCreate() && $this->global_privilege == false) {
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}

			$data = [];
			$data['page_title'] = 'Cycle Count - Floor';
			$data['locations'] = Locations::activeDisburseToken();
			$data['gasha_machines'] = GashaMachines::activeMachines();
			$data['dateTime'] = Carbon::now()->format('Y-m-d H:i:s');

			return $this->view("audit.cycle-count.add-cycle-count", $data);
        }

		public function getDetail($id){

			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

			$data = array();
			$data['page_title'] = 'Cycle Count(Capsule) Details';

			$data['detail_header'] = CycleCount::detail($id);
			$data['detail_body']   = CycleCountLine::detailBody($id);

			return $this->view("audit.cycle-count.detail-cycle-count", $data);
		}

		public function getEdit($id){

			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

			$data = array();
			$data['page_title'] = 'Edit Cycle Count(Capsule)';

			$data['detail_header'] = CycleCount::detail($id);
			$data['detail_body']   = CycleCountLine::editDetailBody($id);
			$data['cycle_count_type'] = CycleCountLine::where('cycle_counts_id',$id)->first()->cycle_count_type;
			return $this->view("audit.cycle-count.edit-cycle-count", $data);
		}

        public function getAddCycleCountStockRoom() {
            $this->cbLoader();
			if(!CRUDBooster::isCreate() && $this->global_privilege == false) {
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}
			$data = [];
			$data['page_title'] = 'Cycle Count - Stock Room';
			$data['locations'] = Locations::activeDisburseToken();
			$data['gasha_machines'] = GashaMachines::activeMachines();
			$data['dateTime'] = Carbon::now()->format('Y-m-d H:i:s');

            return $this->view("audit.cycle-count.add-cycle-count-stock-room", $data);
        }

        public function submitCycleCountFloor(Request $request){
			$excelFile = [];
			$cycleCountFloorRef = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
			// $capsuleSalesRef = Counter::getNextReference(CmsModule::getModuleByName('Capsule Sales')->id);
            $qty = $request->qty;
			foreach($request->item_code as $key_machine => $item_value){
                foreach($item_value as $key_item => $value){
					array_push($excelFile, $value);
                    $machine_id = GashaMachines::getMachineByLocation($key_machine,$request->location_id)->id;
                    $item = Item::where('digits_code',$value)->first();
                    $fqty = str_replace(',', '', (int)$qty[$key_machine][$key_item]);
                    $capsuleHeader = [
                        'reference_number' => $cycleCountFloorRef,
                        'locations_id'     => $request->location_id
                    ];

                    $capsuleInventory = InventoryCapsule::where('item_code',$item->digits_code2)
                        ->where('locations_id',$request->location_id)->first();

                    $capsuleInventoryLine = InventoryCapsuleLine::where([
                        'inventory_capsules_id'=>$capsuleInventory->id,
                        'gasha_machines_id'    => $machine_id,
                        'sub_locations_id'     => null
                    ])->first();

                    $capsule = CycleCount::firstOrCreate($capsuleHeader,[
                        'reference_number' => $cycleCountFloorRef,
						'header_status'    => $this->forApproval,
                        'locations_id'     => $request->location_id,
                        'total_qty'        => $request->quantity_total,
                        'created_by'       => CRUDBooster::myId(),
                        'created_at'       => date('Y-m-d H:i:s')
                    ]);

                    $capsuleLines = new CycleCountLine([
						'status'            => $this->forApproval,
                        'cycle_counts_id'   => $capsule->id,
                        'digits_code'       => $value,
                        'gasha_machines_id' => $machine_id,
                        'qty'               => $fqty,
                        'variance'          => ($fqty - (int)$capsuleInventoryLine->qty),
                        'created_at'        => date('Y-m-d H:i:s'),
						'cycle_count_type'  => "FLOOR"
                    ]);

                    $capsuleLines->save();

                    // HistoryCapsule::insert([
                    //     'reference_number' => $capsule->reference_number,
                    //     'item_code' => $item->digits_code2,
                    //     'capsule_action_types_id' => CapsuleActionType::getByDescription(self::CYCLE_COUNT_ACTION)->id,
                    //     'gasha_machines_id' => $machine_id,
                    //     'locations_id' => $request->location_id,
                    //     'from_machines_id' => $machine_id,
                    //     'qty' => ($fqty - $capsuleInventoryLine->qty),
                    //     'created_by' => CRUDBooster::myId(),
                    //     'created_at' => date('Y-m-d H:i:s')
                    // ]);

                    // if(!empty($capsuleInventoryLine) || !is_null($capsuleInventoryLine)){
                    //     InventoryCapsuleLine::where([
                    //         'inventory_capsules_id' => $capsuleInventory->id,
                    //         'gasha_machines_id'=> $machine_id
                    //     ])->update([
                    //         'qty' => $fqty,
                    //         'updated_by' => CRUDBooster::myId(),
                    //         'updated_at' => date('Y-m-d H:i:s')
                    //     ]);

                    //     //generate capsule sales
                    //     CapsuleSales::insert([
                    //         'reference_number' => $capsule->reference_number,
                    //         'item_code' => $value,
                    //         'gasha_machines_id' => $machine_id,
                    //         'sales_type_id' => SalesType::getByDescription(self::CYCLE_SALE_TYPE)->id,
                    //         'locations_id' => $request->location_id,
                    //         'qty' =>  abs($fqty - $capsuleInventoryLine->qty),
                    //         'created_by' => CRUDBooster::myId(),
                    //         'created_at' => date('Y-m-d H:i:s')
                    //     ]);
                    // }else{
                    //     InventoryCapsuleLine::insert([
                    //         'inventory_capsules_id' => $capsuleInventory->id,
                    //         'gasha_machines_id'=> $machine_id,
                    //         'qty' => $fqty,
                    //         'updated_by' => CRUDBooster::myId(),
                    //         'updated_at' => date('Y-m-d H:i:s')
                    //     ]);
                    // }
                }

			}
			//PROCESS NOT INCLUDED IN FILE
			$notIncludedInExcel = InventoryCapsule::leftJoin('items', 'inventory_capsules.item_code', 'items.digits_code2')
			->leftJoin('inventory_capsule_view', 'inventory_capsules.id', 'inventory_capsule_view.inventory_capsules_id')
			->leftJoin('inventory_capsule_lines', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
			->whereNotIn('items.digits_code', $excelFile)
			->where('inventory_capsule_lines.qty','>',0)
			->where('inventory_capsules.locations_id', $request->location_id)
			->whereNotNull('inventory_capsule_lines.gasha_machines_id')
			->select('items.digits_code','items.digits_code2','items.item_description','machine_capsule_qty','inventory_capsule_lines.gasha_machines_id','inventory_capsule_lines.qty')
			->get();
			//dd($notIncludedInExcel->toArray());

			if($notIncludedInExcel){
				foreach($notIncludedInExcel->toArray() as $key_item => $item_value){
					$machine_id = GashaMachines::getMachineByLocation($key_machine,$request->location_id)->id;
					$fqty = $item_value['qty'];
					$capsuleHeader = [
						'reference_number' => $cycleCountFloorRef,
						'locations_id' => $request->location_id
					];    
	
					$capsule = CycleCount::firstOrCreate($capsuleHeader,[
                        'reference_number' => $cycleCountFloorRef,
						'header_status'    => $this->forApproval,
                        'locations_id'     => $request->location_id,
                        'total_qty'        => $request->quantity_total,
                        'created_by'       => CRUDBooster::myId(),
                        'created_at'       => date('Y-m-d H:i:s')
                    ]);

                    $capsuleLines = new CycleCountLine([
						'status'            => $this->forApproval,
                        'cycle_counts_id'   => $capsule->id,
                        'digits_code'       => $item_value['digits_code'],
                        'gasha_machines_id' => $item_value['gasha_machines_id'],
                        'qty'               => 0,
                        'variance'          => -1 * abs($fqty),
                        'created_at'        => date('Y-m-d H:i:s'),
						'cycle_count_type'  => "FLOOR"
                    ]);
	
					$capsuleLines->save();
				}
			}

            CRUDBooster::redirect(CRUDBooster::mainpath(),'Success! Cycle count has been created','success ')->send();
		}

        public function submitCycleCountStockRoom(Request $request){
            $cycleCountFloorRef = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
            $qty = $request->qty;
			foreach($request->item_code as $key_item => $item_value){
                $sublocation_id = SubLocations::where('location_id',$request->location_id)->where('description',self::STOCK_ROOM)->first();
                $item = Item::where('digits_code',$item_value)->first();
                $fqty = str_replace(',', '', $qty[$key_item]);
                $capsuleHeader = [
                    'reference_number' => $cycleCountFloorRef,
                    'locations_id' => $request->location_id
                ];

                $capsuleInventory = InventoryCapsule::where('item_code',$item->digits_code2)
                ->where('locations_id',$request->location_id)->first();

                $capsuleInventoryLine = InventoryCapsuleLine::where([
                    'inventory_capsules_id'=>$capsuleInventory->id,
                    'sub_locations_id'=> $sublocation_id->id,
                    'gasha_machines_id'=> null
                ])->first();

                $capsule = CycleCount::firstOrCreate($capsuleHeader,[
                    'reference_number' =>$cycleCountFloorRef,
                    'locations_id' => $request->location_id,
                    'sub_locations_id' => $sublocation_id->id,
                    'total_qty' => $request->quantity_total,
                    'created_by' => CRUDBooster::myId(),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $capsuleLines = new CycleCountLine([
                    'cycle_counts_id' => $capsule->id,
                    'digits_code' => $item_value,
                    'qty' => $fqty,
                    'variance' => ($fqty - $capsuleInventoryLine->qty),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $capsuleLines->save();

                HistoryCapsule::insert([
                    'reference_number' => $capsule->reference_number,
                    'item_code' => $item->digits_code2,
                    'capsule_action_types_id' => CapsuleActionType::getByDescription(self::CYCLE_COUNT_ACTION)->id,
                    'locations_id' => $request->location_id,
                    'from_sub_locations_id' => $sublocation_id->id,
                    'qty' => ($fqty - $capsuleInventoryLine->qty),
                    'created_by' => CRUDBooster::myId(),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                if(!empty($capsuleInventoryLine) || !is_null($capsuleInventoryLine)){
                    InventoryCapsuleLine::where([
                        'inventory_capsules_id' => $capsuleInventory->id,
                        'sub_locations_id'=> $sublocation_id->id
                    ])->update([
                        'qty' => $fqty,
                        'updated_by' => CRUDBooster::myId(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                else{
                    InventoryCapsuleLine::insert([
                        'inventory_capsules_id' => $capsuleInventory->id,
                        'sub_locations_id'=> $sublocation_id->id,
                        'qty' => $fqty,
                        'updated_by' => CRUDBooster::myId(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

			}
            CRUDBooster::redirect(CRUDBooster::mainpath(),'Success! Cycle count has been created','success ')->send();
        }

        public function getMachine(Request $request) {
			$machines = GashaMachines::getMachineByLocation($request->machine_code,$request->get('location_id'));
			$machine = GashaMachines::where('serial_number', $request->machine_code)->first();
			$item = Item::where('digits_code', $request->item_code)->first();
			$inventory_lines = InventoryCapsuleLine::leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
				->select('items.*', 'inventory_capsule_lines.qty')
				->where('inventory_capsules.locations_id', $request->get('location_id'))
				->where('inventory_capsule_lines.gasha_machines_id', $machine->id)
				->where('inventory_capsule_lines.qty', '>', 0)
				->leftJoin('items', 'items.digits_code2', 'inventory_capsules.item_code')
				->get();
			return json_encode([
				'machines' => $machines,
				'items' => $inventory_lines,
				'machine' => $machine,
			]);
		}

		public function checkInventoryQty(Request $request){
            $capsuleInventory = InventoryCapsule::getByLocation($request->get('location_id'));
            return json_encode(['capsuleInventory' => $capsuleInventory]);
		}

        public function validateMachineItems(Request $request){

			$gasha_machines = GashaMachines::getMachineByLocation($request->machine_code,$request->get('location_id'));
			$inventory_capsule_lines = InventoryCapsuleLine::get();
			$inventory_capsules = InventoryCapsule::get();
			$list_of_ic = $inventory_capsules->whereIn('id', InventoryCapsuleLine::getCapsuleNotZeroQty($gasha_machines->id));
			$validateQty = $request->qty > InventoryCapsuleLine::getMachineInv($gasha_machines->id)->qty;

			return response()->json([
				'gasha_machine' => $gasha_machines->id,
				'qty' => $validateQty,
				'list_of_gm' => $inventory_capsule_lines->where('gasha_machines_id', $gasha_machines->id)->where('qty', '>', 0),
				'list_of_ic' => $list_of_ic,
			]);

		}

		public function checkItem(Request $request){

			$return_inputs = $request->all();

			$item = Item::where('digits_code', $return_inputs['item_code'])->first();
			$machine = GashaMachines::where('serial_number', $return_inputs['gm'])->first();

			if(!$item){
				return json_encode(['missing'=>true]);
			}else if($item->no_of_tokens != $machine->no_of_token){
				return json_encode(['mismatch_token'=>true, 'item'=>$item, 'machine'=>$machine]);
			}

			return json_encode(['item'=>$item, 'machine'=>$machine]);
		}

        public function checkStockRoomInventoryQty(Request $request){
            $capsuleInventory = InventoryCapsule::getInventoryByLocation($request->location_id);
            return json_encode(['capsuleInventory' => $capsuleInventory]);
		}

		public function getDownload($id) {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '4000M');
			$capsuleInventoryData = InventoryCapsule::getInventoryByLocation($id);
			
			$data_array [] = array(
				"Item Code",
				"Item Description",
				"Quantity",
			);

			//foreach($capsuleInventoryData as $data_item){
				$data_array[] = array(
					'Item Code' => '',
					'Item Description' => '',
					'Quantity' => '',
					
				);
			//}
			$this->ExportExcel($data_array);
			
		}

		public function ExportExcel($data){
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '4000M');
			try {
				$spreadSheet = new Spreadsheet();
				$spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
				$spreadSheet->getActiveSheet()->fromArray($data);
				$Excel_writer = new Xlsx($spreadSheet);
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="cycle-count-stockroom.xlsx"');
				header('Cache-Control: max-age=0');
				ob_end_clean();
				$Excel_writer->save('php://output');
				exit();
			} catch (Exception $e) {
				return;
			}
		}

		public function getExportfloor($id) {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '4000M');
			$capsuleInventoryData = InventoryCapsule::getInventoryByLocation($id);
			
			$data_array [] = array(
				"Machine",
				"Item Code",
				"Item Description",
				"Quantity",
			);

			//foreach($capsuleInventoryData as $data_item){
				$data_array[] = array(
					'Machine'          => '',
					'Item Code'        => '',
					'Item Description' => '',
					'Quantity'         => '',
					
				);
			//}
			$this->ExportExcelFloor($data_array);
		}

		public function ExportExcelFloor($data){
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '4000M');
			try {
				$spreadSheet = new Spreadsheet();
				$spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
				$spreadSheet->getActiveSheet()->fromArray($data);
				$Excel_writer = new Xlsx($spreadSheet);
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="cycle-count-floor.xlsx"');
				header('Cache-Control: max-age=0');
				ob_end_clean();
				$Excel_writer->save('php://output');
				exit();
			} catch (Exception $e) {
				return;
			}
		}

		//Store File
		public function storeFile(Request $request){
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($request->file('cycle-count-file'));
			$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
			$data = $sheet->toArray();
			unset($data[0]);

			$newArray = [];
			$contArray = [];
			foreach($data as $val){
				$contArray['digits_code'] = $val[0];
				$contArray['item_description'] = $val[1];
				$contArray['quantity'] = $val[2];
				$newArray[] = $contArray;
			}
			$allJanNo = $item = Item::get()->toArray();

			$getQtyData = [];
			foreach($allJanNo as $key => $val){
				$i = array_search($val['digits_code'], array_column($newArray,'digits_code'));
				if($i !== false){
					$val['quantity'] = $newArray[$i];
					$getQtyData[] = $val;
				}else{
					$val['quantity'] = 0;
					$getQtyData[] = $val;
				}
			}
			$finalConData = [];
			$finalData = [];
			foreach($getQtyData as $fData => $fVal){
				$finalConData['digits_code'] = $fVal['digits_code'];
				$finalConData['digits_code2'] = $fVal['digits_code2'];
				$finalConData['item_description'] = $fVal['item_description'];
				$finalConData['quantity'] = $fVal['quantity'] ? $fVal['quantity']['quantity'] : $fVal['quantity'];
				$finalData[] = $finalConData;
			}

			foreach($request->files as $file){
				$name = time().rand(1,50) .'-'. $file->getClientOriginalName();
				$filename = $name;
				$file->move('cycle-count-files',$filename);
			}
			return response()->json([
				'status'=>'success', 
				'msg'=>'File uploaded successfully!',
				'files'=>$finalData,
				'filename'=>$filename
			]);
		}

		//Import Cycle Count using JOB
		public function importCycleCount(Request $request){
			$file = $request->filename;
			$path = public_path('cycle-count-files/'.$file);
			$data = [];
			$data['file'] = $path;
			$data['location_id'] = $request->location_id;
			$data['quantity_total'] = $request->quantity_total;
			dispatch(new CycleCountImportJob($data));
			return back()->withStatus(__('Operations successfully queued and will be imported soon.'));
		}

		//Delete File
		public function deleteFile(Request $request){
			unlink(public_path('cycle-count-files/'.$request->filename));
			return json_encode(['status'=>'success','message'=>'Reset successfully!']);
		}

		public function exportData(Request $request) {
			$filename = $request->input('filename');
			return Excel::download(new CycleCountExport, $filename.'.csv');
		}

		//CYCLE COUNT FLOOR FILE PROCESS
		public function storeFileFloor(Request $request){
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($request->file('cycle-count-file'));
			$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
			$data = $sheet->toArray();
			unset($data[0]);
			
			$newArray = [];
			$contArray = [];
			
			foreach($data as $key => $val){
				$machines = DB::table('gasha_machines')->where('serial_number',$val[0])->where('location_id', $request->location_id)->count();
				$allJanNo = Item::where('digits_code',$val[1])->count();
				
				if($machines == 0){
					return response()->json(['status'=>'error', 'msg'=>'Machines not found! At line:'.($key+1)]);
				}
				
				if($allJanNo == 0){
					return response()->json(['status'=>'error', 'msg'=>'Jan No not found! At line:'.($key+1)]);
				}

				$contArray['machine'] = $val[0];
				$contArray['item_code'] = $val[1];
				$contArray['item_description'] = $val[2];
				$contArray['qty'] = $val[3];
				$newArray[] = $contArray;
				
			}
			
			// foreach($request->files as $file){
			// 	$name = time().rand(1,50) .'-'. $file->getClientOriginalName();
			// 	$filename = $name;
			// 	$file->move('cycle-count-files',$filename);
			// }

			return response()->json([
				'status'=>'success', 
				'msg'=>'File uploaded successfully!',
				'items'=>$newArray,
				'filename'=>$filename
			]);
		}

		//CYCLE COUNT FLOOR FILE PROCESS
		public function storeFileEdit(Request $request){
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($request->file('cycle-count-file'));
			$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
			$data = $sheet->toArray();
			unset($data[0]);
			
			$newArray = [];
			$contArray = [];
			
			foreach($data as $key => $val){
				$machines = DB::table('gasha_machines')->where('serial_number',$val[0])->where('location_id', $request->locations_id)->count();
				$allJanNo = Item::where('digits_code',$val[1])->count();

				if($request->cycle_count_type == "FLOOR"){
					if($machines == 0){
						return response()->json(['status'=>'error', 'msg'=>'Machines not found! At line:'.($key+1)]);
					}
					
					if($allJanNo == 0){
						return response()->json(['status'=>'error', 'msg'=>'Jan No not found! At line:'.($key+1)]);
					}
				}

				$contArray['machine'] = $val[0];
				$contArray['item_code'] = $val[1];
				$contArray['qty'] = $val[2];
				$newArray[] = $contArray;
				
			}

			return response()->json([
				'status'=>'success', 
				'msg'=>'File uploaded successfully!',
				'items'=>$newArray,
				'filename'=>$filename
			]);
		}

		//PROCESS EDIT CYCLE COUNT
		public function editCycleCount(Request $request){
			$cycleCountId   = $request->cycle_count_id;
			$cycleCountType = $request->cycle_count_type;
			$location_id   = $request->locations_id;
			$jan_code       = $request->item_code;
			$qty            = $request->qty;
			$machine        = $request->machine;
			
			$cycleCountHeader = CycleCount::where('id',$cycleCountId)->first();
			if($cycleCountType == "FLOOR"){
				foreach($jan_code as $key => $value){
					$machine_id = GashaMachines::getMachineByLocation($machine[$key],$location_id)->id;
                    $item = Item::where('digits_code',$value)->first();
                    $fqty = str_replace(',', '', (int)$qty[$key]);
	
                    $is_existing_machine_line = CycleCountLine::leftJoin('cycle_counts as cc', 'cc.id', 'cycle_count_lines.cycle_counts_id')
					->where('cycle_count_lines.cycle_counts_id',$cycleCountId)
					->where('gasha_machines_id', $machine_id)
					->where('cc.locations_id', $location_id)
					->where('cycle_count_lines.qty','>',0)
					->where('cycle_count_lines.status',9)
					->where('cycle_count_lines.cycle_count_type',$cycleCountType)
					->where('cycle_count_lines.digits_code', $value)
					->exists();
					if ($is_existing_machine_line) {
						// updating the qty if existing
						CycleCountLine::leftJoin('cycle_counts as cc', 'cc.id', 'cycle_count_lines.cycle_counts_id')
						->where('cycle_count_lines.cycle_counts_id',$cycleCountId)
						->where('gasha_machines_id', $machine_id)
						->where('cc.locations_id', $location_id)
						->where('cycle_count_lines.qty','>',0)
						->where('cycle_count_lines.status',9)
						->where('cycle_count_lines.cycle_count_type',$cycleCountType)
						->where('cycle_count_lines.digits_code', $value)
							->update([
								'cycle_count_lines.qty' => $fqty
							]);
	
					} else {
						$capsuleInventory = InventoryCapsule::where('item_code',$item->digits_code2)
                        ->where('locations_id',$location_id)->first();

						$capsuleInventoryLine = InventoryCapsuleLine::where([
							'inventory_capsules_id'=>$capsuleInventory->id,
							'gasha_machines_id'    => $machine_id,
							'sub_locations_id'     => null
						])->first();

						$capsuleLines = new CycleCountLine([
							'status'            => $this->forApproval,
							'cycle_counts_id'   => $cycleCountHeader->id,
							'digits_code'       => $value,
							'gasha_machines_id' => $machine_id,
							'qty'               => $fqty,
							'variance'          => ($fqty - $capsuleInventoryLine->qty),
							'created_at'        => date('Y-m-d H:i:s'),
							'cycle_count_type'  => "FLOOR"
						]);
	
						$capsuleLines->save();
					}
				}
			}else{
				foreach($jan_code as $key => $value){
					$sublocation_id = SubLocations::where('location_id',$location_id)->where('description',self::STOCK_ROOM)->first();
                    $item = Item::where('digits_code',$value)->first();
                    $fqty = str_replace(',', '', (int)$qty[$key]);
	
                    $is_existing_stockroom_line = CycleCountLine::leftJoin('cycle_counts as cc', 'cc.id', 'cycle_count_lines.cycle_counts_id')
					->where('cycle_count_lines.cycle_counts_id',$cycleCountId)
					->where('cc.locations_id', $location_id)
					->where('cycle_count_lines.qty','>',0)
					->where('cycle_count_lines.status',9)
					->where('cycle_count_lines.cycle_count_type',$cycleCountType)
					->where('cycle_count_lines.digits_code', $value)
					->exists();
					if ($is_existing_stockroom_line) {
						// updating the qty if existing
						CycleCountLine::leftJoin('cycle_counts as cc', 'cc.id', 'cycle_count_lines.cycle_counts_id')
						->where('cycle_count_lines.cycle_counts_id',$cycleCountId)
						->where('cc.locations_id', $location_id)
						->where('cycle_count_lines.qty','>',0)
						->where('cycle_count_lines.status',9)
						->where('cycle_count_lines.cycle_count_type',$cycleCountType)
						->where('cycle_count_lines.digits_code', $value)
							->update([
								'cycle_count_lines.qty' => $fqty
							]);
	
					} else {
						$capsuleInventory = InventoryCapsule::where('item_code',$item->digits_code2)
						->where('locations_id',$location_id)->first();
			
						$capsuleInventoryLine = InventoryCapsuleLine::where([
							'inventory_capsules_id' => $capsuleInventory->id,
							'sub_locations_id'      => $sublocation_id->id,
							'gasha_machines_id'     => null
						])->first();

						$capsuleLines = new CycleCountLine([
							'status'           => $this->forApproval,
							'cycle_counts_id'  => $cycleCountHeader->id,
							'digits_code'      => $value,
							'qty'              => $fqty,
							'variance'         => ($fqty - $capsuleInventoryLine->qty),
							'created_at'       => date('Y-m-d H:i:s'),
							'cycle_count_type' => "STOCK ROOM"
						]);
	
						$capsuleLines->save();
					}
				}
			}
			CRUDBooster::redirect(CRUDBooster::mainpath(),'Success! Cycle count has been updated!','success ')->send();
		}

		public function getExportedit($id) {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '4000M');
			$refNo = CycleCount::where('id',$id)->first();
			$cycleCountLinesData = CycleCountLine::leftjoin('gasha_machines','cycle_count_lines.gasha_machines_id','=','gasha_machines.id')
								  ->select('cycle_count_lines.id AS ccl_id',
								  		   'cycle_count_lines.*',
								  		   'gasha_machines.serial_number AS machine')
								  ->where('cycle_counts_id',$id)
								  ->where('cycle_count_lines.status',9)
								  ->where('cycle_count_lines.qty','>',0)
								  ->get();
			
			$data_array [] = array(
				"Machine",
				"Item Code",
				"Qty"
			);

			foreach($cycleCountLinesData as $data_item){
				$data_array[] = array(
					'Machine'   => $data_item->machine,
					'Item Code' => $data_item->digits_code,
					'Qty'       => $data_item->qty
					
				);
			}
			$this->ExportExcelEdit($data_array,$refNo->reference_number);
		}

		public function ExportExcelEdit($data,$refNo){
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '4000M');
			try {
				$spreadSheet = new Spreadsheet();
				$spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
				$spreadSheet->getActiveSheet()->fromArray($data);
				$Excel_writer = new Xlsx($spreadSheet);
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="cycle-count-edit-'.$refNo.'.xlsx"');
				header('Cache-Control: max-age=0');
				ob_end_clean();
				$Excel_writer->save('php://output');
				exit();
			} catch (Exception $e) {
				return;
			}
		}
	}
