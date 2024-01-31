<?php namespace App\Http\Controllers\Audit;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\Models\Audit\CollectRrTokenLines;
    use App\Models\Audit\CollectRrTokens;

	class AdminCollectTokenApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {
		private const ForApproval  = 9;
		private const ForReceiving = 5;
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
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "collect_rr_token_lines";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Collected Token Id","name"=>"collected_token_id","join"=>"collect_rr_tokens,id"];
			$this->col[] = ["label"=>"Current Cash Value","name"=>"current_cash_value"];
			$this->col[] = ["label"=>"Gasha Machines Id","name"=>"gasha_machines_id","join"=>"gasha_machines,location_name"];
			$this->col[] = ["label"=>"Location Id","name"=>"location_id","join"=>"locations,id"];
			$this->col[] = ["label"=>"No Of Token","name"=>"no_of_token"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Collected Token Id","name"=>"collected_token_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"collected_token,id"];
			//$this->form[] = ["label"=>"Current Cash Value","name"=>"current_cash_value","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Gasha Machines Id","name"=>"gasha_machines_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"gasha_machines,location_name"];
			//$this->form[] = ["label"=>"Location Id","name"=>"location_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"location,id"];
			//$this->form[] = ["label"=>"No Of Token","name"=>"no_of_token","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Qty","name"=>"qty","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Variance","name"=>"variance","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
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

		public function getIndex() {
			$this->cbLoader();
			 if(!CRUDBooster::isView() && $this->global_privilege == false) CRUDBooster::redirect(CRUDBooster::adminPath(),trans('crudbooster.denied_access'));
			 $data = [];
			 $data['page_title'] = 'Collect Token Approval';
			 $forApproval = DB::table('collect_tokens_forapproval_view')->get();
			 $data['items'] = $forApproval;

			 return $this->view('audit.collect-token.collect-token-approval-index',$data);
		}

		public function getViewApproval($id){
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

			$data = array();
			$data['page_title'] = 'View for approval';
			$data['location_id'] = $id;
			$data['items'] = CollectRrTokenLines::detailApprovalBody($id);
			return $this->view("audit.collect-token.collect-token-view-approval", $data);
		}

		public function submitApprovalCc(Request $request){
			$location_id = $request->location_id;

			CollectRrTokens::where([
				'location_id' => $location_id,
				'statuses_id' => self::ForApproval
			])->update([
				'statuses_id' => self::ForReceiving
			]);

			CollectRrTokenLines::where([
				'location_id' => $location_id,
				'line_status' => self::ForApproval
			])->update([
				'line_status' => self::ForReceiving
			]);
			
			$data = ['status'=>'success','msg'=>'Successfully approved!','redirect_url'=>CRUDBooster::adminpath('collect_token_approval')];
			return json_encode($data);
		}

		//COLLECT TOKEN FILE EDIT PROCESS
		public function collectTokenFileEdit(Request $request){
			
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($request->file('collect-token-file'));
			$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
			$data = $sheet->toArray();
			$headerSheet = array_filter($data[0]);
			unset($data[0]);

			if (count($headerSheet) !== 3) {
				return response()->json(['status'=>'error', 'msg'=>'Stockroom edit template mismatch']);
			}
	
			$newArray = [];
			$contArray = [];
			$isDuplicate = [];
			foreach($data as $key => $val){
				$machines = DB::table('gasha_machines')->where('serial_number',$val[0])->where('location_id', $request->location_id)->count();

				$machinesAndNoOfToken = DB::table('gasha_machines')->where('serial_number',$val[0])->where('no_of_token',$val[1])->where('location_id', $request->location_id)->count();
			
				$new_key = $val[0];
				if (array_key_exists($new_key, $isDuplicate)) {
					return response()->json(['status'=>'error', 'msg'=>'Not allowed duplicate Machines! At line:'.($key+1)]);
				}
				$isDuplicate[$new_key] = $val;
				
				if($machines == 0){
					return response()->json(['status'=>'error', 'msg'=>'Machines not found! At line:'.($key+1)]);
				}

				if($machinesAndNoOfToken == 0){
					return response()->json(['status'=>'error', 'msg'=>'Machines and No of token must be equal! At line:'.($key+1)]);
				}
				
				$contArray['machine'] = $val[0];
				$contArray['no_of_token'] = $val[1];
				$contArray['qty'] = $val[2];
				$newArray[] = $contArray;	
			}

			return response()->json([
				'status'=>'success', 
				'msg'=>'File uploaded successfully!',
				'items'=>$newArray,
				'filename'=>$filename
			]);
		}

	}