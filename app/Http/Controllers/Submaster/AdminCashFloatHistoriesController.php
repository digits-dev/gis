<?php namespace App\Http\Controllers\Submaster;

use App\Models\Submaster\CashFloatHistory;
use App\Models\Submaster\CashFloatHistoryLine;
	use App\Models\Submaster\FloatEntry;
	use App\Models\Submaster\FloatType;
	use App\Models\Submaster\Locations;
	use App\Models\Submaster\ModeOfPayment;
	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminCashFloatHistoriesController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "cash_float_histories";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference Number","name"=>"id","join"=>"float_history_view,reference_number","join_id"=>"cash_float_histories_id"];
			$this->col[] = ["label"=>"Token Qty","name"=>"id","join"=>"float_history_view,token_qty","join_id"=>"cash_float_histories_id"];
			$this->col[] = ["label"=>"Token Value","name"=>"id","join"=>"float_history_view,token_value","join_id"=>"cash_float_histories_id"];
			$this->col[] = ["label"=>"Peso","name"=>"id","join"=>"float_history_view,cash_value","join_id"=>"cash_float_histories_id"];
			$this->col[] = ["label"=>"Float Type","name"=>"float_types_id","join"=>"float_types,description"];
			$this->col[] = ["label"=>"Location","name"=>"locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Entry Date","name"=>"entry_date"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created At","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			// $this->form[] = ['label'=>'Location','name'=>'locations_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'locations,location_name'];
			// $this->form[] = ['label'=>'Float Type','name'=>'float_types_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'float_types,description'];
			// $this->form[] = ['label'=>'Status','name'=>'status','type'=>'select','validation'=>'required','width'=>'col-sm-5','dataenum'=>'ACTIVE;INACTIVE'];
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
	        if(CRUDBooster::isSuperadmin()){
				$query->whereNull('cash_float_histories.deleted_at')
					  ->orderBy('cash_float_histories.id', 'desc');
			}else if(in_array(CRUDBooster::myPrivilegeId(),[3])){
				$query->where('cash_float_histories.created_by', CRUDBooster::myId())
					  ->orderBy('cash_float_histories.id', 'desc');
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
			$return_inputs = Request::all();

			$postdata['locations_id'] = $return_inputs['location_id'];
			$postdata['float_types_id'] = $return_inputs['float_id'];
			$postdata['created_at'] = date('Y-m-d H:i:s');
			$postdata['created_by'] = CRUDBooster::myId();
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
			$return_inputs = Request::all();

			$cfl_count = count($return_inputs['mode_of_payment']);

			for($i=0; $i<$cfl_count; $i++){
				CashFloatHistoryLine::insert([
					'cash_float_histories_id' => $id,
					'mode_of_payments_id' => $return_inputs['mode_of_payment'][$i],
					'float_entries_id' => $return_inputs['float_entry'][$i],
					'qty' => $return_inputs['qty'][$i],
					'value' => $return_inputs['value'][$i]
				]);
			}

			$postdata['created_at'] = date('Y-m-d H:i:s');
			$postdata['created_by'] = CRUDBooster::myId();
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
			$postdata['updated_at'] = date('Y-m-d H:i:s');
			$postdata['updated_by'] = CRUDBooster::myId();
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
			$data['locations'] = Locations::where('status', 'ACTIVE')->get();
			$data['float_types'] = FloatType::where('status', 'ACTIVE')->get();
			$data['mode_of_payments'] = ModeOfPayment::where('status', 'ACTIVE')->get();
			$data['float_entries'] = FloatEntry::where('status', 'ACTIVE')->get();

			//Please use view method instead view method from laravel
			return $this->view('submaster.cash-float-history.add-cash-float',$data);
		}

		public function getDetail($id) {
			//Create an Auth
			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = [];
			$data['row'] = CashFloatHistory::find($id);
			$data['page_title'] = 'Detail Cash Float History';
			$data['locations'] = Locations::where('status', 'ACTIVE')->get();
			$data['float_types'] = FloatType::where('status', 'ACTIVE')->get();
			$data['mode_of_payments'] = ModeOfPayment::where('status', 'ACTIVE')->get();
			$data['float_entries'] = FloatEntry::where('status', 'ACTIVE')->get();
	
			//Please use view method instead view method from laravel
			return $this->view('submaster.cash-float-history.detail-cash-float',$data);
		}

		public function viewFloatHistory($id){
			$data =[];
	
			$data['cash_float_history'] = DB::table('cash_float_histories')
				->where('cash_float_histories.id', $id)
				->leftJoin('float_history_view', 'float_history_view.cash_float_histories_id', 'cash_float_histories.id')
				->leftJoin('float_types', 'float_types.id', 'cash_float_histories.float_types_id')
				->select(
					'cash_float_histories.id',
					'float_history_view.entry_date',
					'float_history_view.cash_value',
					'float_history_view.token_value',
					'float_types.description',
				)
				->first();
	
			$data['cash_float_history_lines'] = DB::table('cash_float_history_lines')
				->where('cash_float_history_lines.cash_float_histories_id', $id)
				->leftJoin('float_entries', 'float_entries.id', 'cash_float_history_lines.float_entries_id')
				->leftJoin('mode_of_payments','mode_of_payments.id','cash_float_history_lines.mode_of_payments_id')
				->select(
					'cash_float_history_lines.qty as line_qty',
					'cash_float_history_lines.value as line_value',
					'float_entries.description as entry_description',
					'float_entries.value as entry_value',
					'mode_of_payments.payment_description as payment_description',
				)
				->get()
				->toArray();
	
			
			
			return response()->json($data);
		}
	}
