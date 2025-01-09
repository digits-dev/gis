<?php namespace App\Http\Controllers\History;

	use Session;
	use Request;
	use DB;
	use crocodicstudio\crudbooster\helpers\CRUDBooster;
	use App\Models\CmsModels\CmsPrivileges;

	class AdminCollectTokenHistoriesController extends \crocodicstudio\crudbooster\controllers\CBController {

	
		private const CANPRINT = [CmsPrivileges::SUPERADMIN, CmsPrivileges::CASHIER];
		private const EXPORTER = [CmsPrivileges::SUPERADMIN, CmsPrivileges::CASHIER, CmsPrivileges::CSA, CmsPrivileges::OPERATIONMANAGER, CmsPrivileges::STOREHEAD, CmsPrivileges::AUDIT, CmsPrivileges::AUDITAPPROVER];

		

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
			$this->table = "collect_token_histories";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label" => "Reference Number", "name" => "reference_number"];
			$this->col[] = ["label" => "Status", "name" => "statuses_id", "join" => "statuses,style"];
			$this->col[] = ["label" => "Location", "name" => "location_id", "join" => "locations,location_name"];
			$this->col[] = ["label" => "Bay", "name" => "bay_id", "join" => "gasha_machines_bay,name"];
			$this->col[] = ["label" => "Collected Qty", "name" => "collected_qty", 'callback_php' => 'number_format($row->collected_qty)'];
			$this->col[] = ["label" => "Received Qty", "name" => "received_qty", 'callback_php' => 'number_format($row->received_qty)'];
			$this->col[] = ["label" => "Variance", "name" => "variance"];
			$this->col[] = ["label" => "Received By", "name" => "received_by", "join" => "cms_users,name"];
			$this->col[] = ["label" => "Received Date", "name" => "received_at"];
			$this->col[] = ["label" => "Created By", "name" => "created_by", "join" => "cms_users,name"];
			$this->col[] = ["label" => "Created Date", "name" => "created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

	
			if (in_array(CRUDBooster::myPrivilegeId(), self::CANPRINT)) {
				$this->index_button[] = ["label" => "Print Token Collection Form", "icon" => "fa fa-print", "url" => CRUDBooster::mainpath('print_token_form'), "color" => "info"];
			}

			if (in_array(CRUDBooster::myPrivilegeId(), self::EXPORTER)) {
				$this->index_button[] = ["label" => "Export Collected Token", "icon" => "fa fa-download", "url" => route('export_collected_token') . '?' . urldecode(http_build_query(@$_GET)), "color" => "success"];
			}

	        
	    }

	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }

	    public function hook_query_index(&$query) {
	        //Your code here
	            
	    }

	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }




	}