<?php namespace App\Http\Controllers\Audit;

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
    use Carbon\Carbon;
    use Session;
    use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;

	class AdminCycleCountsController extends \crocodicstudio\crudbooster\controllers\CBController {

        private const CYCLE_COUNT_ACTION = 'Cycle Count';
        private const CYCLE_SALE_TYPE = 'CYCLEOUT';

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "reference_number";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "cycle_counts";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference #","name"=>"reference_number"];
			$this->col[] = ["label"=>"Location","name"=>"locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
            $this->col[] = ["label"=>"Created Date","name"=>"created_at"];
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
				if(in_array(CRUDBooster::myPrivilegeId(),[1,4])){
					$this->index_button[] = ["label"=>"Count (Floor)","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add'),"color"=>"success"];
                    $this->index_button[] = ["label"=>"Count (Stock Room)","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-cycle-count-sr'),"color"=>"success"];
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

        public function submitcycleCountFloor(Request $request){

			$cycleCountFloorRef = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
			$capsuleSalesRef = Counter::getNextReference(CmsModule::getModuleByName('Capsule Sales')->id);
            $qty = $request->qty;
			foreach($request->item_code as $key_machine => $item_value){
                foreach($item_value as $key_item => $value){
                    $machine_id = GashaMachines::getMachineByLocation($key_machine,$request->location_id)->id;
                    $item = Item::where('digits_code',$value)->first();
                    $capsuleHeader = [
                        'reference_number' => $cycleCountFloorRef,
                        'locations_id' => $request->location_id
                    ];

                    $capsule = CycleCount::firstOrCreate($capsuleHeader,[
                        'reference_number' =>$cycleCountFloorRef,
                        'locations_id' => $request->location_id,
                        'created_by' => CRUDBooster::myId(),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $capsuleLines = new CycleCountLine([
                        'cycle_counts_id' => $capsule->id,
                        'digits_code' => $value,
                        'gasha_machines_id' => $machine_id,
                        'qty' => $qty[$key_machine][$key_item],
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $capsuleLines->save();

                    HistoryCapsule::insert([
                        'reference_number' => $capsule->reference_number,
                        'item_code' => $item->digits_code2,
                        'capsule_action_types_id' => CapsuleActionType::getByDescription(self::CYCLE_COUNT_ACTION)->id,
                        'gasha_machines_id' => $machine_id,
                        'locations_id' => $request->location_id,
                        'qty' => $qty[$key_machine][$key_item],
                        'created_by' => CRUDBooster::myId(),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                $capsuleInventory = InventoryCapsule::where('item_code',$item->digits_code2)
                    ->where('locations_id',$request->location_id)->first();

                $capsuleInventoryLine = InventoryCapsuleLine::where([
                    'inventory_capsules_id'=>$capsuleInventory->id,
                    'gasha_machines_id'=> $machine_id,
                    'sub_locations_id'=> null
                ])->first();

                if(!empty($capsuleInventoryLine) || !is_null($capsuleInventoryLine)){
                    InventoryCapsuleLine::where([
                        'inventory_capsules_id' => $capsuleInventory->id,
                        'gasha_machines_id'=> $machine_id
                    ])->update([
                        'qty' => $qty[$key_machine][$key_item],
                        'updated_by' => CRUDBooster::myId(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    //generate capsule sales
                    CapsuleSales::insert([
                        'reference_number' => $capsuleSalesRef,
                        'item_code' => $value,
                        'gasha_machines_id' => $machine_id,
                        'sales_type_id' => SalesType::getByDescription(self::CYCLE_SALE_TYPE)->id,
                        'locations_id' => $request->location_id,
                        'qty' =>  abs($qty[$key_machine][$key_item] - $capsuleInventoryLine->qty),
                        'created_by' => CRUDBooster::myId(),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
                else{
                    InventoryCapsuleLine::insert([
                        'inventory_capsules_id' => $capsuleInventory->id,
                        'gasha_machines_id'=> $machine_id,
                        'qty' => $qty[$key_machine][$key_item],
                        'updated_by' => CRUDBooster::myId(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

			}
            CRUDBooster::redirect(CRUDBooster::mainpath(),'Success! Cycle count has been created','success ')->send();
		}

        public function getMachine(Request $request) {
			$machines = GashaMachines::getMachineByLocation($request->machine_code,$request->location_id);
			return json_encode(['machines' => $machines]);
		}

		public function checkInventoryQty(Request $request){
            $capsuleInventory = InventoryCapsule::getByLocation($request->location_id);
            return json_encode(['capsuleInventory' => $capsuleInventory]);
		}

        public function validateMachineItems(Request $request){

			$gasha_machines = GashaMachines::getMachineByLocation($request->machine_code,$request->location_id);
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
	}
