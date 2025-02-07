<?php namespace App\Http\Controllers\Token;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminTokenOnHandVsBeginningBalReportController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "location_name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = false;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "token_on_hand_vs_beginning_bal_report";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Location Name","name"=>"location_name"];
			$this->col[] = ["label"=>"Total Beg. Balance","name"=>"total_beginning_bal"];
			$this->col[] = ["label"=>"Total Token On Hand","name"=>"total_token_on_hand"];
			$this->col[] = ["label"=>"Variance","name"=>"variance"];
			$this->col[] = ["label"=>"Entry Date","name"=>"generated_date"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created At","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE
		}

	    public function hook_query_index(&$query) {
	        //Your code here
	            
	    }

	}