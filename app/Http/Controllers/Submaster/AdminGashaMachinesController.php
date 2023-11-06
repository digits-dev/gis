<?php namespace App\Http\Controllers\Submaster;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Models\Submaster\Locations;
	use App\Models\Submaster\GashaMachines;
	use App\Models\Submaster\Counter;
	use Excel;
	class AdminGashaMachinesController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "gasha_machines";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Serial Number","name"=>"serial_number"];
			//$this->col[] = ["label"=>"Description","name"=>"description"];
			$this->col[] = ["label"=>"Location","name"=>"location_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"No Of Token","name"=>"no_of_token"];
			$this->col[] = ["label"=>"Machine Status","name"=>"machine_statuses_id","join"=>"statuses,status_description"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Serial Number','name'=>'serial_number','type'=>'text','width'=>'col-sm-10','readonly'=>true];
			//$this->form[] = ['label'=>'Description','name'=>'description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Location','name'=>'location_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'locations,location_name'];
			$this->form[] = ['label'=>'No Of Token','name'=>'no_of_token','type'=>'number','validation'=>'required|integer|min:1|max:9','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Machine Status','name'=>'machine_statuses_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'statuses,status_description','datatable_where'=>"status_description = 'GOOD' OR status_description = 'DEFECTIVE'"];
			$this->form[] = ['label'=>'Status','name'=>'status','type'=>'select2','validation'=>'required','width'=>'col-sm-10','dataenum'=>'ACTIVE;INACTIVE','value'=>'ACTIVE'];	
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				$this->index_button[] = ["label"=>"Add Machine","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-machine'),"color"=>"success"];
				$this->index_button[] = ["label"=>"Upload Machines","icon"=>"fa fa-upload","url"=>CRUDBooster::mainpath('machines-upload'),'color'=>'primary'];
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
			$main_path = CRUDBooster::mainPath();
			$admin_path = CRUDBooster::adminPath();
	        $this->script_js = "
				$('.panel-heading').css({'background-color':'#dd4b39','color':'#fff'});
				$('.user-footer .pull-right a').on('click', function () {
					const currentMainPath = window.location.origin;
					Swal.fire({
						title: 'Do you want to logout?',
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#d33',
						cancelButtonColor: '#b9b9b9',
						confirmButtonText: 'Logout',
						reverseButtons: true,
					}).then((result) => {
						if (result.isConfirmed) {
							location.assign(`$admin_path/logout`);
						}
					});
				});			
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
			$this->load_js[] = '//cdn.jsdelivr.net/npm/sweetalert2@11';
	        $this->load_js[] = asset("jsHelper/isNumber.js");
	        
	        
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
	        if(CRUDBooster::isSuperadmin()){
				$query->orderBy('gasha_machines.id', 'desc');
			}else {
				$query->where('gasha_machines.location_id', CRUDBooster::myLocationId())
					  ->orderBy('gasha_machines.id', 'desc');
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
			$fields = Request::all();
			// $count_header       = DB::table('gasha_machines')->count();
			// $header_ref         = str_pad($count_header + 1, 7, '0', STR_PAD_LEFT);				
			// $serial_number	    = "GM-".$header_ref;
			$description        = $fields['description'];
			$location           = $fields['location'];
			$location_name      = DB::table('locations')->where('id',$location)->first();
			$no_of_tokens       = $fields['no_of_tokens'];

			$postdata['serial_number']         = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
			$postdata['description']           = $description;
			$postdata['location_id']           = $location;
			$postdata['location_name']         = $location_name->location_name;
			$postdata['no_of_token']           = intval(str_replace(',', '', $no_of_tokens));
			$postdata['machine_statuses_id']   = 1;
			$postdata['status']                = 'ACTIVE';
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
			$gasha_machines = GashaMachines::find($id); 
	        CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_add_success",['reference_number'=>$gasha_machines->serial_number]), 'success');

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
	        $location                  = $postdata['location_id'];
			$location_name             = DB::table('locations')->where('id',$location)->first();
			$postdata['location_name'] = $location_name->location_name;
			$postdata['updated_by']    = CRUDBooster::myId();
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

		public function getAddMachine(){
			if(!CRUDBooster::isCreate() && $this->global_privilege == false) {
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}
			$data = [];

			if(CRUDBooster::isSuperAdmin()){
				$data['locations'] = Locations::active();
			}else{
				$data['locations'] = Locations::activeLocationPerUserPullout(CRUDBooster::myLocationId());
			}
		
			return $this->view("submaster.gasha-machine.add-machine", $data);
		}

		//IMPORT
		public function UploadMachines() {
			$data['page_title']= 'Machines Upload';
			return view('import.gasha-machine-import.gasha-machine-import', $data)->render();
		}

	}