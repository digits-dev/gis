<?php namespace App\Http\Controllers\Submaster;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Models\Submaster\Locations;

	class AdminGashaMachinesController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->table = "gasha_machines";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Serial Number","name"=>"serial_number"];
			$this->col[] = ["label"=>"Location","name"=>"location_id","join"=>"locations,location_name"];
			$this->col[] = ["label"=>"No Of Token","name"=>"no_of_token"];
			$this->col[] = ["label"=>"Machine Statuses Id","name"=>"machine_statuses_id","join"=>"statuses,status_description"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created At","name"=>"created_at"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by"];
			$this->col[] = ["label"=>"Updated At","name"=>"updated_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Serial Number','name'=>'serial_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Location Id','name'=>'location_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'locations,id'];
			$this->form[] = ['label'=>'No Of Token','name'=>'no_of_token','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Machine Statuses Id','name'=>'machine_statuses_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'statuses,id'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Serial Number","name"=>"serial_number","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Location Id","name"=>"location_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"location,id"];
			//$this->form[] = ["label"=>"No Of Token","name"=>"no_of_token","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Machine Statuses Id","name"=>"machine_statuses_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"machine_statuses,id"];
			//$this->form[] = ["label"=>"Status","name"=>"status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				$this->index_button[] = ["label"=>"Add Machine","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-machine'),"color"=>"success"];
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
			$count_header       = DB::table('gasha_machines')->count();
			$header_ref         = $count_header + 1;			
			$serial_number	    = "GM-".$header_ref;
			$location           = $fields['location'];
			$no_of_tokens       = $fields['no_of_tokens'];

			$postdata['serial_number']         = $serial_number;
			$postdata['location_id']           = $location;
			$postdata['no_of_token']           = $no_of_tokens;
			$postdata['machine_statuses_id']   = 1;
			$postdata['status']                = 'STATUS';
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

		public function getAddMachine(){
			if(!CRUDBooster::isCreate() && $this->global_privilege == false) {
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}
			$data = [];

			$data['locations'] = Locations::active();
			
			return $this->view("Submaster/Gasha-machine.add-machine", $data);
		}


	}