<?php namespace App\Http\Controllers\Token;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Models\Token\PulloutToken;
	use App\Models\Token\TokenHistory;
	use App\Models\Token\TokenInventory;
	use App\Models\Submaster\Counter;
	use App\Models\Submaster\TokenActionType;

	class AdminPulloutTokensController extends \crocodicstudio\crudbooster\controllers\CBController {
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
			$this->title_field = "reference_number";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "pullout_tokens";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference Number","name"=>"reference_number"];
			$this->col[] = ["label"=>"Status","name"=>"statuses_id","join"=>"statuses,status_description"];
			$this->col[] = ["label"=>"Qty","name"=>"qty"];
			$this->col[] = ["label"=>"Location","name"=>"locations_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];


			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
            if(in_array(CRUDBooster::getCurrentMethod(),['getEdit','postEditSave','getDetail'])) {
			    $this->form[] = ['label'=>'Reference Number','name'=>'reference_number','type'=>'text','validation'=>'required|min:1|max:100','width'=>'col-sm-5'];
            }
			$this->form[] = ['label'=>'Qty','name'=>'qty','type'=>'text','validation'=>'required|min:0','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Location','name'=>'locations_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'locations,location_name'];
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
				$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('getPulloutForPrint/[id]'),'icon'=>'fa fa-print', "showIf"=>"[statuses_id] == 2"];
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
	        $this->script_js = '
			$("#qty").attr("onkeypress","inputIsNumber()");
			var addButton = $("#btn_add_new_data")
			addButton.text("Pullout Token")
			$(".panel-heading").css({"background-color":"#dd4b39","color":"#fff"});

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
			$this->load_js[] = asset('jsHelper\isNumber.js');
	        



	        /*
	        | ----------------------------------------------------------------------
	        | Add css style at body
	        | ----------------------------------------------------------------------
	        | css code in the variable
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = '
			.select2-selection__choice{
				font-size:14px !important;
				color:black !important;
				}
				.select2-selection__rendered {
					line-height: 31px !important;
				}
				.select2-container .select2-selection--single {
					height: 35px !important;
				}
				.select2-selection__arrow {
					height: 34px !important;
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
	        //Your code here
			$checkTokenInventory = DB::table('token_inventories')->where('id',1)->first();
			$postdata['reference_number'] = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
			$postdata['statuses_id']      = $this->forPrint;
			$postdata['created_by']       = CRUDBooster::myId();
			$location_id                  = $postdata['locations_id'];
			$token_inventory              = TokenInventory::where('locations_id', $location_id);
			$token_inventory_qty          = $token_inventory->first()->qty;
			$postdata['qty']              = intval(str_replace(',', '', $postdata['qty']));
			$postdata['to_locations_id']  = $checkTokenInventory->id;
			

			$qtyToDeduct = $postdata['qty'];
			if($token_inventory_qty === null){
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"The Token Inventory is empty or unavailable!","danger");
			}else if($qtyToDeduct > $token_inventory_qty){
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"The quantity you're trying to pull out exceeds the available quantity in the Token Inventory!","danger");
			}
			
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after add public static function called
	    | ----------------------------------------------------------------------
	    | @id = last insert id
	    |
	    */
		public function hook_after_add($id) {
			return CRUDBooster::redirect(CRUDBooster::mainpath('getPulloutForPrint/'.$id),"Pullout Token!","success");
			// $refNumber = str_pad($id, 8, "0", STR_PAD_LEFT);

			// DB::table('pullout_tokens')->where('id', $id)->update([
			// 	'reference_number' => 'PT-' . $refNumber
			// ]);

			// $typeId = DB::table('token_action_types')->select('id')->where('description', 'Deduct')->first()->id;
			
			// $tokenInfo = PulloutToken::where('id', $id)->first();
			// $location_id = $tokenInfo->locations_id;
			// $token_inventory = TokenInventory::where('locations_id', $location_id);
			// $qtyToDeduct = $tokenInfo->qty;
			// $token_inventory_qty = $token_inventory->first()->qty;
			// $total_qty = $token_inventory_qty + $qtyToDeduct;
			// TokenInventory::updateOrInsert(['locations_id' => $location_id],
			// 	['qty' => $total_qty,
			// 	'locations_id' => $location_id,
			// 	'updated_by' => CRUDBooster::myId(),
			// 	'updated_at' => date('Y-m-d H:i:s'),
			// 	]
			// );

			// $tokenHistory = new TokenHistory;
			// $tokenHistory->reference_number = $tokenInfo->reference_number;
			// $tokenHistory->qty = $tokenInfo->qty;
			// $tokenHistory->types_id = $typeId;
			// $tokenHistory->locations_id = $tokenInfo->locations_id;
			// $tokenHistory->created_by = $tokenInfo->created_by;
			// $tokenHistory->created_at = $tokenInfo->created_at;
			// $tokenHistory->save();

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

		public function getPulloutForPrint($id){
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}  

			$data = array();
			$data['page_title'] = 'Pullout Print';
			$data['pulloutToken'] = PulloutToken::getDatas($id);

			return $this->view("token.pullout-token.pullout-token-print", $data);
		}

		public function forPrintPulloutUpdate(){
			$data = Request::all();
			$header_id = $data['header_id'];

			PulloutToken::where('id',$header_id)
			->update([
				'statuses_id'=> $this->forReceiving,
			]);

			$pullout_token = PulloutToken::find($header_id);   
			$qty = -1 * abs($pullout_token->qty);
			$location_id = $pullout_token->locations_id;
			$tat_add_token = TokenActionType::where('description', 'Deduct')->first();

			//less in inventory
			//DB::table('token_inventories')->where('id',$location_id)->decrement('qty', $pullout_token->qty);

			//Save History
	        TokenHistory::insert([
				'reference_number' => $pullout_token->reference_number,
				'qty'              => $qty,
				'types_id'         => $tat_add_token->id,
				'locations_id'     => $location_id,
				'created_by'       => CRUDBooster::myId(),
				'created_at'       => date('Y-m-d H:i:s'),
			]);
		}

	}