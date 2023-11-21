<?php namespace App\Http\Controllers\Token;

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
	use Carbon\Carbon;

	class AdminCollectRrTokensReceivingController extends \crocodicstudio\crudbooster\controllers\CBController {
		private $collected;
		private $cancelled;
		private $received;

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->collected       =  5;
			$this->cancelled       =  6;
			$this->received        =  8;
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
			if(CRUDBooster::isUpdate()) {
				if(in_array(CRUDBooster::myPrivilegeId(),[1,3,5])){
					$this->addaction[] = ['title'=>'Check Collected Tokens','url'=>CRUDBooster::mainpath('get-edit/[id]'),'icon'=>'fa fa-pencil', 'showIf'=>'[statuses_id] == "'.$this->collected.'"','color'=>'success'];
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
	        $this->load_js[] = asset("jsHelper/isNumber.js");


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
	        if(in_array(CRUDBooster::myPrivilegeId(),[1,2,4,6,7,8])){
				$query->whereNull('collect_rr_tokens.deleted_at')
					  ->orderBy('collect_rr_tokens.statuses_id', 'asc')
					  ->orderBy('collect_rr_tokens.id', 'desc');
			}else if(in_array(CRUDBooster::myPrivilegeId(),[3,5])){
				$query->where('collect_rr_tokens.location_id', CRUDBooster::myLocationId())
					  ->where('collect_rr_tokens.statuses_id',$this->collected)
					  ->whereNull('collect_rr_tokens.deleted_at')
					  ->orderBy('collect_rr_tokens.statuses_id', 'asc')
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
	    	$collected     = DB::table('statuses')->where('id', $this->collected)->value('status_description');
			$cancelled   = DB::table('statuses')->where('id', $this->cancelled)->value('status_description');
			$received      = DB::table('statuses')->where('id', $this->received)->value('status_description');
			if($column_index == 2){
				if($column_value == $collected){
					$column_value = '<span class="label label-info">'.$collected.'</span>';
				}else if($column_value == $cancelled){
					$column_value = '<span class="label label-danger">'.$cancelled.'</span>';
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
			$fields = Request::all();
			$postdata['statuses_id']   = $this->received;
			$postdata['received_qty']  = intval(str_replace(',', '', $fields['received_qty']));
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
			$receivedToken = CollectRrTokens::find($id);
			$tat_add_token = TokenActionType::where('id', 3)->first();
			$qty = $receivedToken->collected_qty;

			//Save Inventory
			TokenInventory::updateOrcreate([
				'locations_id' => $receivedToken->location_id,
			],
			[
				'qty'          => DB::raw("IF(qty IS NULL, '".(int)$qty."', qty + '".(int)$qty."')"),
				'locations_id' => $receivedToken->location_id,
				'created_by'   => CRUDBooster::myId(),
				'created_at'   => date('Y-m-d H:i:s'),
			]);

	        TokenHistory::insert([
				'reference_number' => $receivedToken->reference_number,
				'qty'              => $receivedToken->collected_qty,
				'types_id'         => $tat_add_token->id,
				'locations_id'     => $receivedToken->location_id,
				'created_by'       => CRUDBooster::myId(),
				'created_at'       => date('Y-m-d H:i:s'),
			]);

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

		public function getEdit($id){

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


	}
