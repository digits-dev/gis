<?php namespace App\Http\Controllers\History;

use App\Exports\TokenSwapHistoryExport;
use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\Models\Capsule\HistoryCapsule;
	use App\Models\Capsule\InventoryCapsuleLine;
use Maatwebsite\Excel\Facades\Excel;

	class AdminSwapHistoriesController extends \crocodicstudio\crudbooster\controllers\CBController {

        public function __construct() {
			// Register ENUM type
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}

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
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "swap_histories";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference #","name"=>"reference_number"];
			$this->col[] = ["label"=>"Token Qty","name"=>"token_value"];
			$this->col[] = ["label"=>"Token Value","name"=>"total_value"];
			$this->col[] = ["label"=>"Change","name"=>"change_value"];
            $this->col[] = ["label"=>"Mode of payment","name"=>"mode_of_payments_id","join"=>"mode_of_payments,payment_description"];
            $this->col[] = ["label"=>"Payment Reference","name"=>"payment_reference"];
			$this->col[] = ["label"=>"Type","name"=>"type_id","join"=>"token_action_types,description"];
			$this->col[] = ["label"=>"Location","name"=>"locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			if((CRUDBooster::isSuperadmin())){
				$this->col[] = ["label"=>"Uuid","name"=>"uuid"];
			}

			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			
			if((CRUDBooster::isSuperadmin()) && (CRUDBooster::getCurrentMethod() == 'getEdit' || CRUDBooster::getCurrentMethod() == 'postEditSave')){
				$this->form[] = array("label"=>"Status","name"=>"status","type"=>"select","dataenum"=>"POSTED;VOID",'required'=>true, 'width'=>'col-sm-5');
			}
			if((CRUDBooster::isSuperadmin()) && (CRUDBooster::getCurrentMethod() == 'getDetail' || CRUDBooster::getCurrentMethod() == 'postEditSave')){
				$this->form = [];
			$this->form[] = ['label'=>'Reference #','name'=>'reference_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Token Qty','name'=>'token_value','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Token Value','name'=>'total_value','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Change','name'=>'change_value','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Mode Of Payments','name'=>'mode_of_payments_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-5','datatable'=>'mode_of_payments,payment_description'];
            $this->form[] = ['label'=>'Payment Reference','name'=>'payment_reference','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Type','name'=>'type_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-5','datatable'=>'type,id','datatable'=>'token_action_types,description'];
            $this->form[] = ['label'=>'Location','name'=>'locations_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'locations,location_name'];
			$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'cms_users,name'];
			$this->form[] = ['label'=>'Created Date','name'=>'created_at','type'=>'date','validation'=>'required','width'=>'col-sm-5'];
			$this->form[] = array("label"=>"Status","name"=>"status","type"=>"select","dataenum"=>"POSTED;VOID",'required'=>true, 'width'=>'col-sm-5');
			}
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
			if (CRUDBooster::isUpdate() && CRUDBooster::isSuperadmin() ) {
				$this->addaction[] = [
					'title'=>'Edit',
					'url' => CRUDBooster::mainpath('requestVoid/[id]'),
					'icon'=>'fa fa-pencil',
					'color' => 'success',
					'showIf' => '[status] != "VOID"'
				];
			}
				$this->addaction[] = [
					'title'=>'Detail Data',
					'url' => CRUDBooster::mainpath('getDetails/[id]'),
					'icon'=>'fa fa-eye',
					'color' => 'primary',
				];
			

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
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
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
			$module_name = CRUDBooster::getCurrentModule()->name;
	        $this->post_index_html = "
			<div class='modal fade' tabindex='-1' role='dialog' id='modal-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export $module_name</h4>
						</div>

						<form method='post' target='_blank' action=".route('swap_histories.export').">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export $module_name'/>
								<br/>
								<div class='row'>
									<div class='col-sm-6'>
										<label>Date From</label>
										<input type='date' name='date_from' class='form-control' required/>
									</div>
									<div class='col-sm-6'>
										<label>Date To</label>
										<input type='date' name='date_to' class='form-control' required/>
									</div>
								</div>
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
	        if(in_array(CRUDBooster::myPrivilegeId(),[1,2,4,6,7,8,14])){
				$query->whereNull('swap_histories.deleted_at')
					->orderBy('swap_histories.id', 'desc');
			}else if(in_array(CRUDBooster::myPrivilegeId(),[3,5])){
				$query->where('swap_histories.locations_id', CRUDBooster::myLocationId())
					->orderBy('swap_histories.id', 'desc');
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
			if($column_index == 6){
				if(!preg_match('/[a-z]/i', $column_value)){
					$column_value  = '';
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
			$histories_status = DB::table('swap_histories')->where('id', $id)->value('status');

			if ($histories_status == 'VOID') {
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"Already Voided!","danger");
			}
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_edit($id) {

			$histories_id = DB::table('swap_histories')->where('id', $id)->first();
			$histories_ref_number = DB::table('swap_histories')->where('id', $id)->value('reference_number');
			$items = DB::table('add_on_movement_histories')->where('reference_number', $histories_ref_number)->select('digits_code', DB::raw('ABS(qty) as qty'))->get();
			$capsule_history = DB::table('history_capsules')->where('reference_number', $histories_ref_number)->get();
			$capsule_type_id = DB::table('capsule_action_types')->where('status', 'ACTIVE')->where('description', 'Void')->value('id');
			$swap_histories = DB::table('swap_histories')->where('reference_number', $histories_ref_number)->get();

			if($capsule_history) {
				foreach ($capsule_history as $key => $value) {
					HistoryCapsule::insert([
						'reference_number' => $value->reference_number,
						'item_code' => $value->item_code,
						'capsule_action_types_id' => $capsule_type_id,
						'locations_id' => $value->locations_id,
						'from_sub_locations_id' => $value->to_sub_locations_id,
						'qty' => $value->qty * -1,
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => CRUDBooster::myId()
					]);
		
					InventoryCapsuleLine::leftJoin('inventory_capsules as ic', 'ic.id', 'inventory_capsule_lines.inventory_capsules_id')
					->where('ic.locations_id', $value->locations_id)
					->where('inventory_capsule_lines.sub_locations_id',  $value->to_sub_locations_id )
					->where('ic.item_code', $value->item_code,)
					->update([
						'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty -  $value->qty"),
						'inventory_capsule_lines.updated_by' => CRUDBooster::myId(),
						'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
					]);

					foreach ($swap_histories  as $key => $value) {
						$token_value = (int)$value->token_value;
						DB::table('token_inventories')
							->where('locations_id', $value->locations_id)
							->update([
								'qty' =>  DB::raw("qty + $token_value"),
							]);
					}
			
				}
		
			}else {

				foreach ($items as $key => $data) {
					DB::table('add_ons')->where('digits_code', $data->digits_code)->where('locations_id',$histories_id->locations_id)->increment('qty', $data->qty);
				}
				
				DB::table('token_inventories')
				->where('locations_id', $histories_id->locations_id)
				->update([
					'qty' =>  DB::raw("qty + $histories_id->token_value"),
				]);
			}

			DB::table('swap_histories')->where('reference_number', $histories_ref_number)->update(['status' => "VOID", 'updated_by' => CRUDBooster::myId(), 'updated_at' =>  date('Y-m-d H:i:s')]);
			DB::table('add_on_movement_histories')->where('reference_number',$histories_ref_number)->update(['status' => 'VOID', 'updated_by' => CRUDBooster::myId(),'updated_at' => date('Y-m-d H:i:s')]);
	
		
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

		public function requestVoid ($id) {
			$data = [];
			$data['page_title'] = 'Void Token Swap History';
			$data['swap_histories'] = DB::table('swap_histories')->where('id', $id)->first();
			$data['addons'] = DB::table('addons_history')->where('token_swap_id', $id)->where('add_ons.locations_id',$data['swap_histories']->locations_id)->leftjoin('add_ons', 'add_ons.digits_code', 'addons_history.digits_code')->select('add_ons.description', 'addons_history.qty' )->get()->toArray();
			$data['defective_returns'] = DB::table('history_capsules')
			->leftJoin('items', 'items.digits_code2', '=', 'history_capsules.item_code')
			->leftJoin('capsule_action_types', 'capsule_action_types.id', '=', 'history_capsules.capsule_action_types_id')
			->where('history_capsules.reference_number', $data['swap_histories']->reference_number)
			->select('history_capsules.qty', 'items.digits_code')
			->get()->toArray();
	
			return view('history.token-swap-void', $data);
		}

		public function getDetails ($id) {
			$data = [];
			$data['page_title'] = 'Detail Token Swap History';
			$data['swap_histories'] = DB::table('swap_histories')->where('id', $id)->first();
			$data['mod_description'] = DB::table('mode_of_payments')->where('id', $data['swap_histories']->mode_of_payments_id)->select('payment_description')->first();
			$data['location_name'] = DB::table('locations')->where('id', $data['swap_histories']->locations_id)->select('location_name')->first();
			$data['addons'] = DB::table('addons_history')->where('token_swap_id', $id)->where('add_ons.locations_id',$data['swap_histories']->locations_id)->leftjoin('add_ons', 'add_ons.digits_code', 'addons_history.digits_code')->select('add_ons.description', 'addons_history.qty' )->get()->toArray();
			return view('history.token-swap-details', $data);
		}

		public function exportSwapHistoryData(Request $request) {
			$filename = $request->filename;
			$date_from = $request->date_from;
			$date_to = $request->date_to;
			$filename = "$filename---$date_from to $date_to";
			$date_to = date('Y-m-d', strtotime($date_to.'+ 1 days'));
			return Excel::download(new TokenSwapHistoryExport($date_from, $date_to), $filename.'.csv');
			// return Excel::download(new GashaMachineExport, $filename.'.csv');
		}

	}