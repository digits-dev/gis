<?php namespace App\Http\Controllers\Token;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\Models\Submaster\Locations;
	use App\Models\Submaster\Counter;
	use App\Models\Token\TokenAdjustment;
	use App\Models\Token\TokenInventory;


	class AdminTokenAdjustmentsController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "token_adjustments";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference #","name"=>"reference_number"];
			$this->col[] = ["label"=>"Location","name"=>"locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Adjustment Qty","name"=>"adjustment_qty"];
			$this->col[] = ["label"=>"Reason","name"=>"reason"];
			$this->col[] = ["label"=>"Before Qty","name"=>"before_qty"];
			$this->col[] = ["label"=>"After Qty","name"=>"after_qty"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Reference #','name'=>'reference_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Location','name'=>'locations_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'locations,location_name'];
			$this->form[] = ['label'=>'Adjustment Qty','name'=>'adjustment_qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Reason','name'=>'reason','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Before Qty','name'=>'before_qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'After Qty','name'=>'after_qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];


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



	    //By the way, you can still create your own method in here... :)

		public function getAdd() {

			$data = [];
			$data['locations'] = TokenInventory::leftJoin('locations', 'locations.id', 'token_inventories.locations_id')
				->select('locations_id as id','location_name','qty')
				->orderBy('location_name','asc')
				->get();

			return view('token.token-adjustment.token-adjustment',$data);
		}

		public function viewAmount(Request $request){
			$user_request = $request->all();
			$location_id = $user_request['location_id'];
			$token_qty = TokenInventory::where('locations_id', $location_id)->pluck('qty')->first();

			return response()->json(['qty'=> $token_qty]);
		}

		public function getTokenInventory(Request $request) {
			$locations_id = $request->get('location_id');
			$action = $request->get('action');
			$adjustment_qty =  $request->get('value');
			$current_inventory = DB::table('token_inventories')
				->where('locations_id', $locations_id)
				->leftJoin('locations', 'locations.id', 'token_inventories.locations_id')
				->get()
				->first();

			$current_inventory->action = $action;
			$current_inventory->adjustment_qty = (integer) $adjustment_qty;
			if ($action == 'add') {
				$current_inventory->new_qty = $current_inventory->qty + $adjustment_qty;
			} else {
				$current_inventory->new_qty = $current_inventory->qty - $adjustment_qty;

			}

			return response()->json($current_inventory);
		}

		public function submitAmount(Request $request){
			$data = $request->all();
			$time_stamp = date('Y-m-d H:i:s');
			$reference_number = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
			$action_by = CRUDBooster::myId();
			$locations_id = $data['locations_id'];
			$action = $data['action'];
			$adjustment_qty = preg_replace("/\D/", '', $data['adjustment_qty_' . $action]);
			$reason = $data['reason_' . $action];
			$inventory_query = DB::table('token_inventories')
				->where('locations_id', $locations_id);
			$before_qty = $inventory_query->pluck('qty')->first();
			if ($action == 'add') {
				$inventory_query->update([
					'qty' => DB::raw("qty + $adjustment_qty"),
					'updated_at' => $time_stamp,
					'updated_by' => $action_by,
				]);
				$token_type = 7;
			} else {
				if ($adjustment_qty > $inventory_query->pluck('qty')->first()){
					return CRUDBooster::redirect(CRUDBooster::mainpath(), 'Deduct Token cannot exceed to token inventory.',"danger");
				}
				$adjustment_qty = "-$adjustment_qty";
				$inventory_query->update([
					'qty' => DB::raw("qty + ($adjustment_qty)"),
					'updated_at' => $time_stamp,
					'updated_by' => $action_by,
				]);
				$token_type = 8;

			}
			$after_qty = $inventory_query->pluck('qty')->first();
			$to_be_inserted = [
				'reference_number' => $reference_number,
				'locations_id' => $locations_id,
				'adjustment_qty' => $adjustment_qty,
				'reason' => $reason,
				'before_qty' => $before_qty,
				'after_qty' => $after_qty,
				'created_by' => $action_by,
				'created_at' => $time_stamp
			];

			DB::table('token_adjustments')->insert($to_be_inserted);

			$history = [
				'reference_number' => $reference_number,
				'qty' => $adjustment_qty,
				'types_id' => $token_type,
				'locations_id' => $locations_id,
				'created_by' => $action_by,
				'created_at' => $time_stamp
			];
			DB::table('token_histories')->insert($history);

			CRUDBooster::redirect(CRUDBooster::mainpath(), 'Your form submitted succesfully.',"success");
		}
	}
