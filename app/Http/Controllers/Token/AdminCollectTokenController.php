<?php namespace App\Http\Controllers\Token;

use App\Models\CmsModels\CmsPrivileges;
use App\Models\Submaster\Statuses;
use crocodicstudio\crudbooster\helpers\CRUDBooster;

	class AdminCollectTokenController extends \crocodicstudio\crudbooster\controllers\CBController {

		private const CANCREATE = [CmsPrivileges::SUPERADMIN, CmsPrivileges::CSA];
		private const FORCASHIERTURNOVER = [CmsPrivileges::SUPERADMIN, CmsPrivileges::CASHIER];

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
		

	        if (CRUDBooster::isCreate()){
				if(in_array(CRUDBooster::myPrivilegeId(),self::CANCREATE)){
					$this->index_button[] = ["label"=>"Add Collect Token","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add_collect_token'),"color"=>"success"];
				}

			}
			
			if (in_array(CRUDBooster::myPrivilegeId(), self::FORCASHIERTURNOVER)) {
				$this->addaction[] = [
					'title' => 'Cashier Turnover',
					'url' => CRUDBooster::mainpath('create-do-no/[id]'),
					'icon' => 'fa fa-pencil',
					'color' => 'warning',
					'showIf' => "[statuses_id]=='" . Statuses::FORCASHIERTURNOVER . "'"
				];
			}
	        
	    }

		
	    public function hook_query_index(&$query) {
	       
	            
	    }

		public function AddCollectToken(){

			$data = [];
			$data['page_title'] = 'Collect Token';
			$data['page_icon'] = 'fa fa-circle-o';


			return view("token.collect-token.add-collect-token", $data);
			
		}

		public function getDetail($id){

			$data = [];
			$data['page_title'] = "Collect Token Details";
			$data['page_icon'] = 'fa fa-circle-o';
		

			return view("token.collect-token.detail-collect-token", $data);
		}
  



	}