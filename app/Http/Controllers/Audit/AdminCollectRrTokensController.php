<?php namespace App\Http\Controllers\Audit;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Models\Submaster\Locations;
	use App\Models\Submaster\TokenActionType;
	use App\Models\Token\TokenHistory;
	use App\Models\Token\TokenInventory;
	use App\Models\Submaster\GashaMachines;
	use App\Models\Audit\CollectRrTokens;
	use App\Models\Audit\CollectRrTokenLines;
	use App\Models\Submaster\Counter;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Reader\Exception;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use Carbon\Carbon;

	class AdminCollectRrTokensController extends \crocodicstudio\crudbooster\controllers\CBController {
		private $forApproval;
		private $collected;
		private $forChecking;
		private $received;
		private $cancelled;

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->forApproval  =  9;
			$this->collected    =  5;    
			$this->forChecking  =  6;
			$this->received     =  8;   
			$this->cancelled    =  6;      
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
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "collect_rr_tokens";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference Number","name"=>"reference_number"];
			$this->col[] = ["label"=>"Status","name"=>"statuses_id","join"=>"statuses,status_description"];
			$this->col[] = ["label"=>"Location","name"=>"location_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Collected Qty","name"=>"collected_qty",'callback_php'=>'number_format($row->collected_qty)'];
			$this->col[] = ["label"=>"Received Qty","name"=>"received_qty",'callback_php'=>'number_format($row->received_qty)'];
			$this->col[] = ["label"=>"Variance","name"=>"variance"];
			$this->col[] = ["label"=>"Received By","name"=>"received_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Received Date","name"=>"received_at"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			// $this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			// $this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Reference Number','name'=>'reference_number','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Location','name'=>'location_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'location,id'];
			$this->form[] = ['label'=>'Collected Qty','name'=>'collected_qty','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Received Qty','name'=>'received_qty','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Received By','name'=>'received_by','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Received At','name'=>'received_at','type'=>'datetime','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Updated By','name'=>'updated_by','type'=>'text','width'=>'col-sm-10'];
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
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('collect-token-edit/[id]'),'icon'=>'fa fa-pencil', 'showIf'=>'[statuses_id] == "'.$this->forApproval.'"','color'=>'success'];	
				if(in_array(CRUDBooster::myPrivilegeId(),[1,4,14])){
					$this->addaction[] = ['title'=>'Cancel Request',
					'url'=>CRUDBooster::mainpath('collect-token-cancel/[id]'),
					'icon'=>'fa fa-trash', 
					"showIf"=>"[statuses_id] == $this->forApproval",
					'confirmation'=>'yes',
					'confirmation_title'=>'Confirm Cancel',
					'confirmation_text'=>'Are you sure to Cancel this request?','color'=>'danger'];
				}
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
				if(in_array(CRUDBooster::myPrivilegeId(),[1,3,4,5,6,11,14])){
					$this->index_button[] = ["label"=>"Add Collect Token","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-collect-token'),"color"=>"success"];
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
	        $this->script_js = '
				$(function(){
					$("body").addClass("sidebar-collapse");
					$("#table_dashboard").on("cut copy paste", function (e) {
						e.preventDefault();
						return false;
					});
				});
			';
			


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
			//$this->load_js[] = asset("jsHelper/isNumber.js");
	        
	        
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
			if(in_array(CRUDBooster::myPrivilegeId(),[1,4,14])){
				$query->whereNull('collect_rr_tokens.deleted_at')
					  ->orderBy('collect_rr_tokens.id', 'desc');
			}else if(in_array(CRUDBooster::myPrivilegeId(),[3,5,6,11,12])){
				$query->where('collect_rr_tokens.location_id', CRUDBooster::myLocationId())
					  ->whereNull('collect_rr_tokens.deleted_at')
					  ->orderBy('collect_rr_tokens.id', 'desc');
			}
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	
			$forApproval  = DB::table('statuses')->where('id', $this->forApproval)->value('status_description');          
	    	$collected    = DB::table('statuses')->where('id', $this->collected)->value('status_description');     
			$forChecking  = DB::table('statuses')->where('id', $this->forChecking)->value('status_description');   
			$received     = DB::table('statuses')->where('id', $this->received)->value('status_description'); 
			$cancel    = DB::table('statuses')->where('id', $this->cancelled)->value('status_description');  
			if($column_index == 2){
				if($column_value == $forApproval){
					$column_value = '<span class="label label-warning">'.$forApproval.'</span>';
				}else if($column_value == $cancel){
					$column_value = '<span class="label label-danger">'.$cancel.'</span>';
				}else if($column_value == $collected){
					$column_value = '<span class="label label-info">'.$collected.'</span>';
				}else if($column_value == $forChecking){
					$column_value = '<span class="label label-info">'.$forChecking.'</span>';
				}else if($column_value == $received){
					$column_value = '<span class="label label-success">'.$received.'</span>';
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
	       $fields = Request::all();

		//    $count_header                 = DB::table('collect_rr_tokens')->count();
		//    $header_ref                   = str_pad($count_header + 1, 7, '0', STR_PAD_LEFT);		
		//    $reference_number             = "CT-".$header_ref;	
		   $location_id                  = $fields['location_id'];
		   $collected_qty                = intval(str_replace(',', '', $fields['quantity_total']));

		   if($collected_qty == 0){
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"Not allow zero quantity!","danger");
		   }
		   
		   $postdata['reference_number'] = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
		   $postdata['statuses_id']      = $this->forApproval;
		   $postdata['location_id']      = $location_id;
		   $postdata['variance']         = 'No';
		   $postdata['collected_qty']    = intval(str_replace(',', '', $collected_qty));
		   $postdata['created_by']       = CRUDBooster::myId();
	
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
			$fields                  = Request::all();

			$dataLines               = array();
			$header                  = DB::table('collect_rr_tokens')->where(['created_by' => CRUDBooster::myId()])->orderBy('id','desc')->first();
			$location_id             = $fields['location_id'];
		
			$gm_serials              = $fields['gasha_machines_id'];
			$gm_ids                  = [];
			$gm_tokens               = [];
			$gasha_machines_no_token = [];
			$variances                = [];

			foreach($gm_serials as $gm){
				$machine = DB::table('gasha_machines')->where('serial_number',$gm)->first();
				$gm_ids[] = $machine->id;
				$gm_tokens[] = $machine->no_of_token;
			}
			$qty 	           = $fields['qty'];

			foreach($gm_tokens as $key => $var){
				$variances[] = fmod(intval(str_replace(',', '', $qty[$key])),$var);
				
			}
			foreach($variances as $variance){
				if(intval($variance) != 0){
					CollectRrTokens::where(['id'=>$id])
					->update([
								'variance'      => 'Yes'
							]);
				}
				
				// else{
				// 	CollectRrTokens::where(['id'=>$id])
				// 	->update([
				// 				'variance'      => 'No'
				// 			]);
				// }
			}
			$current_value = DB::table('token_conversions')->where('status','ACTIVE')->first();

			for($x=0; $x < count((array)$gm_serials); $x++) {	
				$dataLines[$x]['line_status']        = $this->forApproval;	
				$dataLines[$x]['collected_token_id'] = $id;
				$dataLines[$x]['gasha_machines_id']  = $gm_ids[$x];
				$dataLines[$x]['no_of_token']        = $gm_tokens[$x];
				$dataLines[$x]['qty']                = intval(str_replace(',', '', $qty[$x]));
				$dataLines[$x]['variance']           = fmod(intval(str_replace(',', '', $qty[$x])),$gm_tokens[$x]);
				$dataLines[$x]['location_id']        = $location_id;
				$dataLines[$x]['current_cash_value'] = $current_value->current_cash_value;
				$dataLines[$x]['created_at']         = date('Y-m-d H:i:s');
			}

			//save histories
			$tat_add_token = TokenActionType::where('id', 6)->first();
			TokenHistory::insert([
				'reference_number' => $header->reference_number,
				'qty'              => -1 * abs($header->collected_qty),
				'types_id'         => $tat_add_token->id,
				'locations_id'     => $header->location_id,
				'created_by'       => CRUDBooster::myId(),
				'created_at'       => date('Y-m-d H:i:s'),
			]);

			DB::beginTransaction();
			try {
				CollectRrTokenLines::insert($dataLines);
				DB::commit();
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_add_success",['reference_number'=>$header->reference_number]), 'success');
				
			} catch (\Exception $e) {
				DB::rollback();
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
			}

			CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_add_success",['reference_number'=>$header->reference_number]), 'success');

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
			$postdata['statuses_id']   = $this->closed;
			$postdata['received_qty']  = $postdata['received_qty'];
			$postdata['received_by']   = CRUDBooster::myId();
			$postdata['received_at']   = date('Y-m-d H:i:s');
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

		public function getAddCollectToken(){
			$this->cbLoader();
			if(!CRUDBooster::isCreate() && $this->global_privilege == false) {
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}
			
			$data = [];
			$data['page_title'] = 'Collect Token';
			$user = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();
			
			if(in_array(CRUDBooster::myPrivilegeId(),[1,4,14])){
				$data['locations'] = Locations::activeDisburseToken();
			}else{
				$data['locations'] = Locations::activeLocationPerUserCollectToken(CRUDBooster::myLocationId());
			}
		
			$data['gasha_machines'] = GashaMachines::activeMachines();
			$data['dateTime'] = Carbon::now()->format('F d, Y g:i A');

			return $this->view("audit.collect-token.add-collect-token", $data);
		}

		public function getOptionMachines(Request $request){
			$data = Request::all();	
		
			$gasha_machines = DB::table('gasha_machines')
							->select('gasha_machines.*',
							         'gasha_machines.id as served_id',)
							->where('status','ACTIVE')
							->get();
	
			return($gasha_machines);
		}

		public function getDetail($id){
			
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

			$data = array();
			$data['page_title'] = 'Collected Token Details';

			$data['detail_header'] = CollectRrTokens::detail($id);
			$data['detail_body']   = CollectRrTokenLines::detailBody($id);
		
			return $this->view("audit.collect-token.detail-collect-token", $data);
		}

		public function getCollectTokenEdit($id){
			
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

			$data = array();
			$data['page_title'] = 'Check Collected Token';

			$data['detail_header'] = CollectRrTokens::detail($id);
			$data['detail_body']   = CollectRrTokenLines::detailBody($id);
		
			return $this->view("audit.collect-token.edit-collect-token", $data);
		}

		public function getMachine(Request $request) {
			$data = Request::all();
			$location_id = $data['location_id'];
		
			$serial_number = $data['item_code'];
			$machines = DB::table('gasha_machines')
				->where('serial_number', $serial_number)
				->where('location_id', $location_id)
				->first();
			return json_encode([
				'machines' => $machines,
			]);
		}

		public function checkInventoryQty(Request $request){
				$data = Request::all();	
				$id = $data['id'];
				$tokenInventory = DB::table('token_inventories')->where('locations_id',$id)->first();
				return json_encode(['tokenInventory' => $tokenInventory]);
		}

		public function getExportedit($id) {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '4000M');
			$refNo = CollectRrTokens::where('collect_rr_tokens.id',$id)->first();

			$collectTokenLinesData = CollectRrTokenLines::leftjoin('gasha_machines','collect_rr_token_lines.gasha_machines_id','=','gasha_machines.id')
								  ->select('collect_rr_token_lines.id AS ccl_id',
								  		   'collect_rr_token_lines.*',
								  		   'gasha_machines.serial_number AS machine',)
								  ->where('collected_token_id',$id)
								  ->where('collect_rr_token_lines.line_status',9)
								  ->get();
			
	
			$data_array [] = array(
				"Machine",
				"Machine no of tokens",
				"Collected tokens",
			);

			foreach($collectTokenLinesData as $data_item){
				$data_array[] = array(
					'Machine'              => $data_item->machine,
					'Machine no of tokens' => $data_item->no_of_token,
					'Collected tokens'     => $data_item->qty,			
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
				header('Content-Disposition: attachment;filename="collect-token-edit-'.$refNo.'.xlsx"');
				header('Cache-Control: max-age=0');
				ob_end_clean();
				$Excel_writer->save('php://output');
				exit();
			} catch (Exception $e) {
				return;
			}
		}
		

		//PROCESS EDIT CYCLE COUNT
		public function editCollectToken(Request $request){
			$fields         = Request::all();
			$collectTokenId = $fields['ct_id'];
			$location_id    = $fields['location_id'];
			$machines        = $fields['machine'];
			$no_of_token    = $fields['no_of_token'];
			$qty            = $fields['qty'];
			$newTotalQty    = $fields['newTotalQty'];
			$collectTokenHeader = CollectRrTokens::where('id',$collectTokenId)->first();

			$variances   = [];
			$insertLines = [];
			foreach($machines as $key => $value){
				$machine = GashaMachines::where('serial_number',$value)->first();
				$fqty = str_replace(',', '', (int)$qty[$key]);
				$is_existing_machine_line = CollectRrTokenLines::leftJoin('collect_rr_tokens as ct', 'ct.id', 'collect_rr_token_lines.collected_token_id')
				->where('collect_rr_token_lines.collected_token_id',$collectTokenId)
				->where('gasha_machines_id', $machine->id)
				->where('ct.location_id', $location_id)
				->where('collect_rr_token_lines.line_status',9)
				->exists();

				//VARIANCES
				$variances[] = fmod(intval($fqty),$machine->no_of_token);

				// if ($is_existing_machine_line) {
					// updating the qty if existing
					CollectRrTokenLines::leftJoin('collect_rr_tokens as ct', 'ct.id', 'collect_rr_token_lines.collected_token_id')
						->where('collect_rr_token_lines.collected_token_id',$collectTokenId)
						->where('gasha_machines_id', $machine->id)
						->where('ct.location_id', $location_id)
						->where('collect_rr_token_lines.line_status',9)
						->update(['collect_rr_token_lines.qty' => $fqty]);

				// }else {
				// 	//insert not exist
				// 	$current_value = DB::table('token_conversions')->where('status','ACTIVE')->first();
				// 	$insertLines[$key]['line_status']        = $this->forApproval;	
				// 	$insertLines[$key]['collected_token_id'] = $collectTokenId;
				// 	$insertLines[$key]['gasha_machines_id']  = $machine->id;
				// 	$insertLines[$key]['no_of_token']        = $machine->no_of_token;
				// 	$insertLines[$key]['qty']                = intval($fqty);
				// 	$insertLines[$key]['variance']           = fmod(intval($fqty),$machine->no_of_token);
				// 	$insertLines[$key]['location_id']        = $location_id;
				// 	$insertLines[$key]['current_cash_value'] = $current_value->current_cash_value;
				// 	$insertLines[$key]['created_at']         = date('Y-m-d H:i:s');
				// }
			}

			//IINSERT NOT EXIST LINES
			//CollectRrTokenLines::insert($insertLines);

			//UPDATE HEADER TOTAL QTY
			CollectRrTokens::where('id',$collectTokenId)->update(['collected_qty' => $newTotalQty]);

			//UPDATE HEADER VARIANCE
			foreach($variances as $variance){
				if(intval($variance) !== 0){
					CollectRrTokens::where('id',$collectTokenId)->update(['variance' => 'Yes']);
				}else{
					CollectRrTokens::where('id',$collectTokenId)->update(['variance' => 'No']);
				}
			}

			CRUDBooster::redirect(CRUDBooster::mainpath(),'Success! Collect Token has been updated!','success ')->send();
		}

		public function cancelCollectToken($id) {
			$reference_number = CollectRrTokens::where('id',$id)->first()->reference_number;
			CollectRrTokens::where('id',$id)->update(['statuses_id' => $this->cancelled]);
			CollectRrTokenLines::where('collected_token_id',$id)->update(['line_status' => $this->cancelled]);
			CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_cancelled_success",['reference_number'=>$reference_number]), 'success');
		}
	}