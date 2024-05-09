<?php namespace App\Http\Controllers\Audit;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
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

	class AdminCycleCountApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {
		private $closed;
		private const CYCLE_COUNT_ACTION = 'Cycle Count';
        private const CYCLE_SALE_TYPE = 'CYCLE COUNT';
        private const STOCK_ROOM = 'STOCK ROOM';

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->closed = 4;
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
			$this->button_add = false;
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "cycle_count_lines";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Cycle Count Type","name"=>"cycle_count_type"];
			$this->col[] = ["label"=>"Cycle Counts Id","name"=>"cycle_counts_id","join"=>"cycle_counts,id"];
			$this->col[] = ["label"=>"Digits Code","name"=>"digits_code"];
			$this->col[] = ["label"=>"Gasha Machines Id","name"=>"gasha_machines_id","join"=>"gasha_machines,location_name"];
			$this->col[] = ["label"=>"Qty","name"=>"qty"];
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
			$this->load_css[] = asset('css/gasha-style.css');
	        
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
			$fields = Request::all();
			dd($fields);

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

		public function getIndex() {
			$this->cbLoader();
			 if(!CRUDBooster::isView() && $this->global_privilege == false) CRUDBooster::redirect(CRUDBooster::adminPath(),trans('crudbooster.denied_access'));
			 $data = [];
			 $data['page_title'] = 'Cycle Count Approval';
			 $forApproval = DB::table('cycle_counts_approval_view')->get();
			 $data['items'] = $forApproval;

			 return $this->view('audit.cycle-count-approval.cycle-count-approval-index',$data);
		}

		public function getViewApproval($id){
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

			$data = array();
			$data['page_title'] = 'View for approval';
			$data['location_id'] = $id;
			$data['items'] = CycleCountLine::detailApprovalBody($id);
			
			$forApproval = [];
			foreach($data['items'] as $val){
				$machine_id = GashaMachines::where('id',$val['gasha_machines_id'])->where('location_id',$id)->first()->id;
                $sublocation_id = SubLocations::where('location_id',$id)->where('description',self::STOCK_ROOM)->first();
				$item = Item::where('digits_code',$val['st_digits_code'])->first();
				$capsuleInventory = InventoryCapsule::where('item_code',$item->digits_code2)
				->where('locations_id',$id)->first();

				$capsuleInventoryLineMfloor = InventoryCapsuleLine::where([
					'inventory_capsules_id'=>$capsuleInventory->id,
					'gasha_machines_id'=> $machine_id,
					'sub_locations_id'=> null
				])->first();

				if($capsuleInventoryLineMfloor->qty != NULL && $val['gasha_machines_id'] != NULL){
					$val['floor_system_qty'] = $capsuleInventoryLineMfloor->qty;
				}else{
					$val['floor_system_qty'] = 0;
				}

				$capsuleInventoryLineMSt = InventoryCapsuleLine::where([
					'inventory_capsules_id'=>$capsuleInventory->id,
					'sub_locations_id'=> $sublocation_id->id,
					'gasha_machines_id'=> null
				])->first();

				$val['st_system_qty'] = $capsuleInventoryLineMSt->qty ? $capsuleInventoryLineMSt->qty : 0;
				
				$forApproval[] = $val;
			}
			$data['forApproval'] = $forApproval;
			return $this->view("audit.cycle-count-approval.view_approval", $data);
		}

		public function submitApprovalCc(Request $request){
			$location_id = $request->st_location_id;
			$typeMachine = 'FLOOR';
			$stockRoomType = 'STOCK ROOM';

			//MACHINE FLOOR PROCESS
			$machineItems = CycleCountLine::getApprovalLinesForProcess($location_id,$typeMachine);
			//REMOVE MORE THAN SYSTEM QTY ITEMS IN MACHINE FLOOR
			$machineFloorData = [];
			foreach($machineItems as $key => $val){
				$machine_id = GashaMachines::where('id',$val->gasha_machines_id)->where('location_id',$location_id)->first()->id;
				$item = Item::where('digits_code',$val->digits_code)->first();

				$capsuleInventory = InventoryCapsule::where('item_code',$item->digits_code2)
				->where('locations_id',$location_id)->first();

				$capsuleInventoryLine = InventoryCapsuleLine::where([
					'inventory_capsules_id'=>$capsuleInventory->id,
					'gasha_machines_id'=> $machine_id,
					'sub_locations_id'=> null
				])->first();
				//dd($machine_id,$capsuleInventory->id,$capsuleInventoryLine->qty);
				if($val->qty <= $capsuleInventoryLine->qty){
					$machineFloorData[] = $val;
				}
			}
			//PROCESS DATA MACHINES
			foreach($machineFloorData as $mKey => $mValue){
				$machine_id = GashaMachines::where('id',$mValue->gasha_machines_id)->where('location_id',$location_id)->first()->id;
				$item = Item::where('digits_code',$mValue->digits_code)->first();

				$capsuleInventory = InventoryCapsule::where('item_code',$item->digits_code2)
				->where('locations_id',$location_id)->first();

				$capsuleInventoryLine = InventoryCapsuleLine::where([
					'inventory_capsules_id'=>$capsuleInventory->id,
					'gasha_machines_id'=> $machine_id,
					'sub_locations_id'=> null
				])->first();

				HistoryCapsule::insert([
					'reference_number' => $mValue->reference_number,
					'item_code' => $item->digits_code2,
					'capsule_action_types_id' => CapsuleActionType::getByDescription(self::CYCLE_COUNT_ACTION)->id,
					'gasha_machines_id' => $machine_id,
					'locations_id' => $location_id,
					'from_machines_id' => $machine_id,
					'qty' => ($mValue->qty - $capsuleInventoryLine->qty),
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s')
				]);

				if(!empty($capsuleInventoryLine) || !is_null($capsuleInventoryLine)){
					InventoryCapsuleLine::where([
						'inventory_capsules_id' => $capsuleInventory->id,
						'gasha_machines_id'=> $machine_id
					])->update([
						'qty' => $mValue->qty,
						'updated_by' => CRUDBooster::myId(),
						'updated_at' => date('Y-m-d H:i:s')
					]);

					//generate capsule sales
					CapsuleSales::insert([
						'reference_number' => $mValue->reference_number,
						'item_code' => $mValue->digits_code,
						'gasha_machines_id' => $machine_id,
						'sales_type_id' => SalesType::getByDescription(self::CYCLE_SALE_TYPE)->id,
						'locations_id' => $location_id,
						'qty' =>  abs($mValue->qty - $capsuleInventoryLine->qty),
						'created_by' => CRUDBooster::myId(),
						'created_at' => date('Y-m-d H:i:s')
					]);
				}else{
					InventoryCapsuleLine::insert([
						'inventory_capsules_id' => $capsuleInventory->id,
						'gasha_machines_id'=> $machine_id,
						'qty' => $mValue->qty,
						'updated_by' => CRUDBooster::myId(),
						'updated_at' => date('Y-m-d H:i:s')
					]);
				}
				CycleCountLine::where([
					'id' => $mValue->ccl_id
				])->update([
					'status' => $this->closed
				]);
				
			}
			//UPDATE HEADER STATUS IF THERE IS NON FOR APPROVAL MACHINE
			$HeaderMachine = CycleCount::leftjoin('cycle_count_lines','cycle_counts.id','=','cycle_count_lines.cycle_counts_id')
										->where('cycle_count_lines.cycle_count_type',$typeMachine)
										->select('cycle_counts.id AS cc_id',
												 'cycle_counts.*',
												 'cycle_count_lines.*')
										->first();
			$checkHeaderLinesMachine = CycleCountLine::where('status',9)->where('cycle_count_type',$typeMachine)->count();
			
			if($checkHeaderLinesMachine === 0){
				CycleCount::where([
					'id' => $HeaderMachine->cc_id
				])->update([
					'header_status' => $this->closed
				]);
			}
			
			//STOCK ROOM PROCESS
			$stockRoomItems = CycleCountLine::getApprovalLinesForProcess($location_id,$stockRoomType);
			//REMOVE MORE THAN SYSTEM QTY ITEMS STOCK ROOM
			// $stockRoomData = [];
			// foreach($stockRoomItems as $key => $val){
			// 	$sublocation_id = SubLocations::where('location_id',$location_id)->where('description',self::STOCK_ROOM)->first();
			// 	$machine_id = GashaMachines::where('id',$val->gasha_machines_id)->where('location_id',$location_id)->first()->id;
			// 	$item = Item::where('digits_code',$val->digits_code)->first();

			// 	$capsuleInventory = InventoryCapsule::where('item_code',$item->digits_code2)
			// 	->where('locations_id',$location_id)->first();

			// 	$capsuleInventoryLine =  InventoryCapsuleLine::where([
			// 		'inventory_capsules_id'=>$capsuleInventory->id,
			// 		'sub_locations_id'=> $sublocation_id->id,
			// 		'gasha_machines_id'=> null
			// 	])->first();

			// 	if($val->qty <= $capsuleInventoryLine->qty){
			// 		$stockRoomData[] = $val;
			// 	}
				
			// }
			//PROCESS DATA STOCKROOM
			foreach($stockRoomItems as $stKey => $stVal){
				$sublocation_id = SubLocations::where('location_id',$location_id)->where('description',self::STOCK_ROOM)->first();
				$item = Item::where('digits_code',$stVal->digits_code)->first();
				$capsuleInventory = InventoryCapsule::where('item_code',$item->digits_code2)
				->where('locations_id',$location_id)->first();

				$capsuleInventoryLine = InventoryCapsuleLine::where([
					'inventory_capsules_id'=>$capsuleInventory->id,
					'sub_locations_id'=> $sublocation_id->id,
					'gasha_machines_id'=> null
				])->first();

				 HistoryCapsule::insert([
					'reference_number' => $stVal->reference_number,
					'item_code' => $item->digits_code2,
					'capsule_action_types_id' => CapsuleActionType::getByDescription(self::CYCLE_COUNT_ACTION)->id,
					'locations_id' => $location_id,
					'from_sub_locations_id' => $sublocation_id->id,
					'qty' => ($stVal->qty - $capsuleInventoryLine->qty),
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s')
				]);

				if(!empty($capsuleInventoryLine) || !is_null($capsuleInventoryLine)){
					InventoryCapsuleLine::where([
						'inventory_capsules_id' => $capsuleInventory->id,
						'sub_locations_id'=> $sublocation_id->id
					])->update([
						'qty' => $stVal->qty,
						'updated_by' => CRUDBooster::myId(),
						'updated_at' => date('Y-m-d H:i:s')
					]);
				}
				else{
					InventoryCapsuleLine::insert([
						'inventory_capsules_id' => $capsuleInventory->id,
						'sub_locations_id'=> $sublocation_id->id,
						'qty' => $stVal->qty,
						'updated_by' => CRUDBooster::myId(),
						'updated_at' => date('Y-m-d H:i:s')
					]);
				}
				CycleCountLine::where([
					'id' => $stVal->ccl_id
				])->update([
					'status' => $this->closed
				]);
				CycleCount::where([
					'id' => $stVal->cc_id
				])->update([
					'header_status' => $this->closed
				]);
			}

			// UPDATE HEADER STATUS IF THERE IS NON FOR APPROVAL STOCKROOM
			// $HeaderStockRoom = CycleCount::leftjoin('cycle_count_lines','cycle_counts.id','=','cycle_count_lines.cycle_counts_id')
			// 							->where('cycle_count_lines.cycle_count_type',$stockRoomType)
			// 							->select('cycle_counts.id AS cc_id',
			// 									 'cycle_counts.*',
			// 									 'cycle_count_lines.*')
			// 							->first();
			// $checkHeaderLinesStockRoom = CycleCountLine::where('status',9)->where('cycle_count_type',$stockRoomType)->count();
			
			// if($checkHeaderLinesStockRoom == 0){
			// 	CycleCount::where([
			// 		'id' => $HeaderStockRoom->cc_id
			// 	])->update([
			// 		'header_status' => $this->closed
			// 	]);
			// }
			
			$data = ['status'=>'success','msg'=>'Successfully approved!','redirect_url'=>CRUDBooster::adminpath('cycle_count_approval')];
			return json_encode($data);
		}
	
	}