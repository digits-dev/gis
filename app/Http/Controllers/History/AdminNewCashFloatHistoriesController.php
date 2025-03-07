<?php

namespace App\Http\Controllers\History;

use App\Models\Submaster\CashFloatHistory;
use App\Models\Submaster\FloatEntry;
use App\Models\Submaster\FloatType;
use App\Models\Submaster\Locations;
use Session;
use Request;
use DB;
use CRUDBooster;

class AdminNewCashFloatHistoriesController extends \crocodicstudio\crudbooster\controllers\CBController
{

	public function cbInit()
	{

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "id";
		$this->limit = "20";
		$this->orderby = "cash_float_histories_id,desc";
		$this->global_privilege = false;
		$this->button_table_action = true;
		$this->button_bulk_action = false;
		$this->button_action_style = "button_icon";
		$this->button_add = false;
		$this->button_edit = false;
		$this->button_delete = false;
		$this->button_detail = false;
		$this->button_show = true;
		$this->button_filter = true;
		$this->button_import = false;
		$this->button_export = false;
		$this->table = "float_history_view";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label" => "Reference Number", "name" => "reference_number"];
		$this->col[] = ["label" => "Token Qty", "name" => "token_qty"];
		$this->col[] = ["label" => "Token Value", "name" => "token_value"];
		$this->col[] = ["label" => "Peso", "name" => "cash_value"];
		$this->col[] = ["label" => "Float Type", "name" => "float_types_id","join"=>"float_types,description"];
		$this->col[] = ["label" => "Locations", "name" => "locations_id","join"=>"locations,location_name"];
		$this->col[] = ["label" => "Entry Date", "name" => "entry_date"];
		$this->col[] = ["label" => "Created By", "name" => "cash_float_histories_id","join"=>"cash_float_histories,created_by"];
		$this->col[] = ["label" => "Created At", "name" => "cash_float_histories_id","join"=>"cash_float_histories,created_at"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		$this->addaction[] = [
			'title' => 'view',
			'url' => CRUDBooster::mainpath('getView/[cash_float_histories_id]'),
			'icon' => 'fa fa-eye',
			'color' => 'primary',
		];
	}

	public function hook_query_index(&$query)
	{
		$query->select("float_history_view.*");
	}

	public function hook_row_index($column_index, &$column_value)
	{
		if ($column_index == 8) { 
			$user = DB::table('cms_users')->where('id', $column_value)->value('name');

			if ($user) {
				$column_value = $user;
			} else {
				$column_value = "Unknown User"; 
			}
		}
	}

	public function getView($id) {
		//Create an Auth
		if(!CRUDBooster::isRead()) {
			CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		}

		$data = [];
		$data['row'] = CashFloatHistory::where('cash_float_histories.id',$id)
			->leftJoin('cms_users', 'cms_users.id', 'cash_float_histories.created_by')
			->select('cash_float_histories.*',
				'cms_users.name as cms_created_by'
			)
			->first();
		$data['page_title'] = 'Detail Cash Float History';
		$data['locations'] = Locations::where('status', 'ACTIVE')->get();
		$data['float_types'] = FloatType::where('status', 'ACTIVE')->get();
		$data['mode_of_payments'] = DB::table('cash_float_history_lines')->where('cash_float_histories_id', $id)
			->leftJoin('mode_of_payments', 'mode_of_payments.id', 'cash_float_history_lines.mode_of_payments_id')
			->select('cash_float_history_lines.*',
				'mode_of_payments.payment_description')
			->where('payment_description', '!=', null)
			->get()
			->toArray();
		$data['mode_of_payments'] = array_map(function($obj) {
			$obj->payment_custom_desc = preg_replace("/[^a-zA-Z]/", '_', $obj->payment_description);
			return $obj;
		}, $data['mode_of_payments']);
		$data['float_entries'] = FloatEntry::where('status', 'ACTIVE')->orderBy('id', 'desc')->get();
		//Please use view method instead view method from laravel
		return $this->view('submaster.cash-float-history.detail-cash-float',$data);
	}

}