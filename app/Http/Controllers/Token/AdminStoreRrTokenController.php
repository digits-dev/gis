<?php namespace App\Http\Controllers\Token;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Models\Submaster\Locations;
	use App\Models\Token\StoreRrToken;
	use App\Models\Submaster\TokenActionType;
	use App\Models\Token\TokenHistory;
	use App\Models\Token\TokenInventory;
	use App\Models\Submaster\Counter;

	class AdminStoreRrTokenController extends \crocodicstudio\crudbooster\controllers\CBController {
		private $forPrint;
		private $forReceiving;
		private $closed;

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->forPrint         =  2;    
			$this->forReceiving     =  3;
			$this->closed           =  4;      
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
			$this->table = "store_rr_token";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Disburse#","name"=>"disburse_number"];
			$this->col[] = ["label"=>"Status","name"=>"statuses_id","join"=>"statuses,status_description"];
			$this->col[] = ["label"=>"Released Qty","name"=>"released_qty"];
			$this->col[] = ["label"=>"From Location","name"=>"from_locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Received Qty","name"=>"received_qty"];
			//$this->col[] = ["label"=>"Variance Qty","name"=>"variance_qty"];
			$this->col[] = ["label"=>"To Location","name"=>"to_locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Received By","name"=>"received_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Received Date","name"=>"received_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			if(CRUDBooster::getCurrentMethod() == 'getDetail'){
				$this->form = [];
				$this->form[] = ['label'=>'Disburse Number','name'=>'disburse_number','type'=>'text','width'=>'col-sm-10'];
				$this->form[] = ['label'=>'Released Qty','name'=>'released_qty','type'=>'number','width'=>'col-sm-10'];
				$this->form[] = ['label'=>'Received Qty','name'=>'received_qty','type'=>'text','width'=>'col-sm-10'];
				//$this->form[] = ['label'=>'Variance Qty','name'=>'variance_qty','type'=>'text','width'=>'col-sm-10'];
				$this->form[] = ['label'=>'From Locations','name'=>'from_locations_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'locations,location_name'];
				$this->form[] = ['label'=>'To Locations','name'=>'to_locations_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'locations,location_name'];
				$this->form[] = ['label'=>'Statuses','name'=>'statuses_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'statuses,status_description'];
				$this->form[] = ['label'=>'Received Date','name'=>'received_at','type'=>'datetime','width'=>'col-sm-10'];
				$this->form[] = ['label'=>'Received By','name'=>'received_by','type'=>'select2','width'=>'col-sm-10','datatable'=>'cms_users,name'];
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
			if(CRUDBooster::isUpdate()) {
				$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('getRequestForPrint/[id]'),'icon'=>'fa fa-print', "showIf"=>"[statuses_id] == 2"];
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
				$this->index_button[] = ["label"=>"Disburse Token","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('disburse-token'),"color"=>"danger"];
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
			// $(function(){
			// 	$('body').addClass('sidebar-collapse');
			// });
			// $('.user-footer .pull-right a').on('click', function () {
			// 	const currentMainPath = window.location.origin;
			// 	Swal.fire({
			// 		title: 'Do you want to logout?',
			// 		icon: 'warning',
			// 		showCancelButton: true,
			// 		confirmButtonColor: '#d33',
			// 		cancelButtonColor: '#b9b9b9',
			// 		confirmButtonText: 'Logout',
			// 		reverseButtons: true,
			// 	}).then((result) => {
			// 		if (result.isConfirmed) {
			// 			location.assign(`$admin_path/logout`);
			// 		}
			// 	});
			// });			
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
	        //Your code here
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	$forPrint       = DB::table('statuses')->where('id', $this->forPrint)->value('status_description');     
			$forReceiving   = DB::table('statuses')->where('id', $this->forReceiving)->value('status_description');   
			$closed         = DB::table('statuses')->where('id', $this->closed)->value('status_description');  
			if($column_index == 1){
				if($column_value == $forPrint){
					$column_value = '<span class="label label-info">'.$forPrint.'</span>';
				}else if($column_value == $forReceiving){
					$column_value = '<span class="label label-info">'.$forReceiving.'</span>';
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
			$fields = Request::all();

			// $count_header       = DB::table('store_rr_token')->count();
			// $finalCount         = $count_header + 100000;
			// $header_ref         = str_pad($finalCount + 1, STR_PAD_LEFT);		
			// $disburse_number	= "DB-".$header_ref;

			$to_location        = $fields['location'];
			$release_qty        = intval(str_replace(',', '', $fields['release_qty']));

			$checkTokenInventory = DB::table('token_inventories')->where('id',1)->first();

			if(empty($checkTokenInventory)){
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"No token inventory!","danger");
			}

			if(empty($release_qty)){
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"Token required!","danger");
			}

			if(empty($to_location)){
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"Location required!","danger");
			}
			
			if($release_qty > $checkTokenInventory->qty){
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"Token Qty Exceed in Token Inventory!","danger");
			}

			//DB::table('token_inventories')->where('id',1)->decrement('qty', $release_qty);

			$postdata['disburse_number']       = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
			$postdata['from_locations_id']     = $checkTokenInventory->id;
			$postdata['to_locations_id']       = $to_location;
			$postdata['released_qty']          = $release_qty;
			$postdata['statuses_id']           = $this->forPrint;
			$postdata['created_by']            = CRUDBooster::myId();

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {    
			$disburse_token = StoreRrToken::find($id);   
			$qty = $disburse_token->released_qty;
			$location_id = $disburse_token->to_locations_id;
			$tat_add_token = TokenActionType::where('description', 'Disburse')->first();

			//Save Inventory
			// TokenInventory::updateOrcreate([
			// 	'locations_id' => $location_id,
			// ],
			// [
			// 	'qty'          => DB::raw("IF(qty IS NULL, '".(int)$qty."', qty + '".(int)$qty."')"), 
			// 	'locations_id' => $location_id,
			// 	'created_by'   => CRUDBooster::myId(),
			// 	'created_at'   => date('Y-m-d H:i:s'),
			// ]);

			//Save History
	        // TokenHistory::insert([
			// 	'reference_number' => $disburse_token->disburse_number,
			// 	'qty'              => $qty,
			// 	'types_id'         => $tat_add_token->id,
			// 	'locations_id'     => $location_id,
			// 	'created_by'       => CRUDBooster::myId(),
			// 	'created_at'       => date('Y-m-d H:i:s'),
			// ]);
			return CRUDBooster::redirect(CRUDBooster::mainpath('getRequestForPrint/'.$id),"Token disburse!","success");

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

		public function getDisburseToken(){
			$this->cbLoader();
			if(!CRUDBooster::isCreate() && $this->global_privilege == false) {
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}
			$data = [];
			$data['locations'] = Locations::activeDisburseToken();
			return $this->view("token.disburse-token.add-disburse-token", $data);
		}

		public function getRequestForPrint($id){
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}  

			$data = array();
			$data['page_title'] = 'Print';
			$data['disburseToken'] = StoreRrToken::getDatas($id);

			return $this->view("token.disburse-token.disburse-token-print", $data);
		}

		public function forPrintUpdate(){
			$data = Request::all();
			$header_id = $data['header_id'];

			StoreRrToken::where('id',$header_id)
			->update([
				'statuses_id'=> $this->forReceiving,
			]);

			$disburse_token = StoreRrToken::find($header_id);   
			$qty = -1 * abs($disburse_token->released_qty);
			$location_id = $disburse_token->from_locations_id;
			$tat_add_token = TokenActionType::where('id', 2)->first();

			//less in inventory
			DB::table('token_inventories')->where('id',1)->decrement('qty', $disburse_token->released_qty);

			//Save History
	        TokenHistory::insert([
				'reference_number' => $disburse_token->disburse_number,
				'qty'              => $qty,
				'types_id'         => $tat_add_token->id,
				'locations_id'     => $location_id,
				'created_by'       => CRUDBooster::myId(),
				'created_at'       => date('Y-m-d H:i:s'),
			]);
		}

	}