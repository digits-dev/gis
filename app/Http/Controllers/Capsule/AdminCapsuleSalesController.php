<?php namespace App\Http\Controllers\Capsule;

use App\Exports\CapsuleSalesBackupExport;
use Session;
use Illuminate\Http\Request;
use DB;
use CRUDBooster;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CapsuleSalesExport;
use App\Models\Capsule\CapsuleSalesBackUp;
use DateTime;

	class AdminCapsuleSalesController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_export = false;
			$this->table = "capsule_sales";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference #","name"=>"reference_number"];
			$this->col[] = ["label"=>"JAN #","name"=>"item_code"];
			$this->col[] = ["label"=>"Item Description","name"=>"item_code","join"=>"items,item_description","join_id"=>"digits_code"];
			$this->col[] = ["label"=>"Gasha Machine Serial Number","name"=>"gasha_machines_id","join"=>"gasha_machines,serial_number"];
			$this->col[] = ["label"=>"Location","name"=>"locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Qty","name"=>"qty"];
			$this->col[] = ["label"=>"Sales Type","name"=>"sales_type_id","join"=>"sales_types,description"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Reference Number','name'=>'reference_number','type'=>'text','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'JAN #','name'=>'item_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Qty','name'=>'qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Location','name'=>'locations_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'locations,location_name'];
			$this->form[] = ['label'=>'Gasha Machine Serial Number','name'=>'gasha_machines_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'gasha_machines,serial_number'];
			$this->form[] = ['label'=>'Sales Type','name'=>'sales_type_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'sales_types,description'];
			$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'cms_users,name'];
			$this->form[] = ['label'=>'Created Date','name'=>'created_at','type'=>'text','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
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
			if (CRUDBooster::getCurrentMethod() == 'getIndex') {
				$this->index_button[] = [
					"title"=>"Export Capsule Sales",
					"label"=>"Export Data",
					"icon"=>"fa fa-upload",
					"color"=>"primary",
					"url"=>"javascript:showSalesExport()",
				];
				$this->index_button[] = [
					"title"=>"Export Capsule Sales History",
					"label"=>"Export Sales History",
					"icon"=>"fa fa-upload",
					"color"=>"primary",
					"url"=>"javascript:showSalesExportWithDate()",
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
				function showSalesExport() {
					$('#modal-sales-export').modal('show');
				}
				function showSalesExportWithDate() {
					$('#modal-sales-export-with-date').modal('show');
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
			<div class='modal fade' tabindex='-1' role='dialog' id='modal-sales-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export Capsule Sales</h4>
						</div>

						<form method='post' target='_blank' action=".route('capsule_sales_export').">
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
			<div class='modal fade' tabindex='-1' role='dialog' id='modal-sales-export-with-date'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export Sales History</h4>
						</div>

						<form method='post' target='_blank' action=".route('capsule_sales_export_with_date').">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export Sales History'/>
								<br/>
								<label>Select Date</label>
								<input type='date' name='date' class='form-control' required/>
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
			$query->where('capsule_sales.status', 'ACTIVE');
	        if (in_array(CRUDBooster::myPrivilegeId(), [1, 2, 4, 6, 7, 8])) {
				$query->whereNull('capsule_sales.deleted_at')
					->orderBy('capsule_sales.id', 'desc');
			} else if (in_array(CRUDBooster::myPrivilegeId(), [3, 5])) {
				$query->where('capsule_sales.locations_id', CRUDBooster::myLocationId())
					->orderBy('capsule_sales.id', 'desc');
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

		public function exportData(Request $request) {
			$filename = $request->input('filename');
			return Excel::download(new CapsuleSalesExport, $filename.'.csv');
		}

		public function exportDataWithDate(Request $request) {
			$filename = $request->input('filename');
			$date = $request->input('date');
			$filename = $filename.'-'.$date;
			return Excel::download(new CapsuleSalesBackupExport($date), $filename.'.csv');
		}

		public function createBackUp() {
			$today = new DateTime();
			$yesterday = $today->modify('-1 day');
			$yesterday = $yesterday->format('Y-m-d');

			$capsule_sales = DB::table('capsule_sales')
				->leftJoin('items', 'items.digits_code', 'capsule_sales.item_code')
				->where('capsule_sales.status', 'ACTIVE')
				->where('capsule_sales.created_at', 'like', "$yesterday%")
				->select(
					'capsule_sales.reference_number',
					'items.digits_code as jan_no',
					'items.digits_code2 as digits_code',
					'items.item_description',
					'gasha_machines.serial_number',
					'locations.location_name',
					'capsule_sales.qty',
					'sales_types.description as sales_type',
					'cms_users.name',
					'capsule_sales.created_at',
				)
				->leftJoin('gasha_machines', 'gasha_machines.id', 'capsule_sales.gasha_machines_id')
				->leftJoin('sales_types', 'sales_types.id', 'capsule_sales.sales_type_id')
				->leftJoin('locations', 'locations.id', 'capsule_sales.locations_id')
				->leftJoin('cms_users', 'cms_users.id', 'capsule_sales.created_by')
				->get()
				->toArray();

			$backup = new CapsuleSalesBackUp([
				'backup_date' => $yesterday,
				'sales_data' => json_encode($capsule_sales ?: [])
			]);

			$backup->save();

		}

	}
