<?php

namespace App\Http\Controllers\Token;

use Session;
use Request;
use DB;
use CRUDBooster;

class AdminSodVsBeginningBalController extends \crocodicstudio\crudbooster\controllers\CBController
{

	public function cbInit()
	{

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "id";
		$this->limit = "20";
		$this->orderby = "start_date,desc";
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
		$this->table = "sod_vs_eod_variance_report";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label" => "Location", "name" => "location_name"];
		$this->col[] = ["label" => "System Beg. Balance", "name" => "Token_Bal_SOD"];
		$this->col[] = ["label" => "Token Drawer Qty (SOD)", "name" => "Token_Drawer_SOD"];
		$this->col[] = ["label" => "Token Sealed (SOD)", "name" => "Token_Sealed_SOD"];
		$this->col[] = ["label" => "Variance", "name" => "Variance_2"];
		$this->col[] = ["label" => "Created By (SOD)", "name" => "Created_by_SOD_Name"];
		$this->col[] = ["label" => "Created At (SOD)", "name" => "Created_at_SOD"];
		# END COLUMNS DO NOT REMOVE THIS LINE     
	}

	public function hook_query_index(&$query)
	{
		$query->select('*');
	}
}
