<?php namespace App\Http\Controllers\Token;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Reader\Exception;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use Excel;
	use Maatwebsite\Excel\HeadingRowImport;
	use App\Imports\UpdateNoOfTokenImport;

	class AdminCollectRrTokenSalesController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
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
			$this->button_export = true;
			$this->table = "collect_rr_token_lines";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference #","name"=>"collect_rr_token_lines.collected_token_id","join"=>"collect_rr_tokens,reference_number"];
			$this->col[] = ["label"=>"Status","name"=>"collect_rr_tokens.statuses_id","join"=>"statuses,status_description"];
			$this->col[] = ["label"=>"Gasha Machine","name"=>"collect_rr_token_lines.gasha_machines_id","join"=>"gasha_machines,serial_number"];
			$this->col[] = ["label"=>"No of token","name"=>"collect_rr_token_lines.no_of_token"];
			$this->col[] = ["label"=>"Qty","name"=>"collect_rr_token_lines.qty",'callback_php'=>'number_format($row->qty)'];
			$this->col[] = ["label"=>"Variance","name"=>"collect_rr_token_lines.variance"];
			$this->col[] = ["label"=>"Current Cash Value","name"=>"collect_rr_token_lines.current_cash_value"];
			$this->col[] = ["label"=>"Location","name"=>"collect_rr_token_lines.location_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Collected Date","name"=>"collect_rr_token_lines.created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			$this->form = [];
			$this->form[] = ["label"=>"Reference #","name"=>"collected_token_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"collect_rr_tokens,reference_number",'width'=>'col-sm-10'];
			$this->form[] = ["label"=>"Gasha Machine","name"=>"gasha_machines_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"gasha_machines,serial_number",'width'=>'col-sm-10'];
			$this->form[] = ["label"=>"Qty","name"=>"qty","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0",'width'=>'col-sm-10'];
			$this->form[] = ["label"=>"Variance","name"=>"variance","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255",'width'=>'col-sm-10'];
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
			// if(CRUDBooster::getCurrentMethod() == 'getIndex'){
			// 	$this->index_button[] = ["label"=>"Update No of Token","icon"=>"fa fa-upload","url"=>CRUDBooster::mainpath('update-no-of-token'),'color'=>'primary'];
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
			$this->style_css = '
				.panel-heading{
					background-color:#3c8dbc !important;
					color:#fff !important;
				}
				input[name="submit"]{
					background-color:#3c8dbc !important;
					color:#fff !important;
				}
				@media (min-width:729px){
				.panel-default{
						width:40% !important;
						margin:auto !important;
				}
			';

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
			if(in_array(CRUDBooster::myPrivilegeId(),[1,2,6,7,8])){
				$query->whereNull('collect_rr_token_lines.deleted_at')
					  ->orderBy('collect_rr_token_lines.id', 'desc')
					  ->leftJoin('collect_rr_tokens as crt','collect_rr_token_lines.collected_token_id','=','crt.id')
					  ->addSelect(
						'crt.statuses_id'
						);
			}else if(in_array(CRUDBooster::myPrivilegeId(),[4,14])){
				$query->whereNull('collect_rr_token_lines.deleted_at')
						->orderBy('collect_rr_token_lines.id', 'desc')
						->leftJoin('collect_rr_tokens as crt','collect_rr_token_lines.collected_token_id','=','crt.id')
						->whereIn('crt.statuses_id',[6,8])
						->addSelect(
						'crt.statuses_id'
						);
			}else if(in_array(CRUDBooster::myPrivilegeId(),[3,5])){
				$query->where('collect_rr_token_lines.location_id', CRUDBooster::myLocationId())
					  ->whereNull('collect_rr_token_lines.deleted_at')
					  ->orderBy('collect_rr_token_lines.id', 'desc')
					  ->leftJoin('collect_rr_tokens as crt','collect_rr_token_lines.collected_token_id','=','crt.id')
					  ->addSelect(
						'crt.statuses_id'
						);
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

		//IMPORT
		public function UploadNoOfToken() {
			$data['page_title']= 'Update No Of Token in Collect Token Lines';
			return view('import.collect-token.update-no-of-token-lines', $data)->render();
		}

		public function saveNoOfToken(Request $request) {
			$path_excel = $request->file('import_file')->store('temp');
			$path = storage_path('app').'/'.$path_excel;
			$headings = array_filter((new HeadingRowImport)->toArray($path)[0][0]);

			try {
				Excel::import(new UpdateNoOfTokenImport, $path);
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
