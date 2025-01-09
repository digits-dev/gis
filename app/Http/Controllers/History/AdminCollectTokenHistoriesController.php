<?php

namespace App\Http\Controllers\History;

use App\Exports\ExportCollectedTokenHistory;
use Session;
use Illuminate\Http\Request;
use DB;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use App\Models\CmsModels\CmsPrivileges;
use App\Models\CmsUsers;
use App\Models\CollectTokenHistory;
use App\Models\Submaster\GashaMachines;
use App\Models\Submaster\GashaMachinesBay;
use App\Models\Submaster\Locations;
use App\Models\Submaster\Statuses;
use Maatwebsite\Excel\Facades\Excel;

class AdminCollectTokenHistoriesController extends \crocodicstudio\crudbooster\controllers\CBController
{

	private const CANPRINT = [CmsPrivileges::SUPERADMIN, CmsPrivileges::CASHIER];
	private const EXPORTER = [CmsPrivileges::SUPERADMIN, CmsPrivileges::CASHIER, CmsPrivileges::CSA, CmsPrivileges::OPERATIONMANAGER, CmsPrivileges::STOREHEAD, CmsPrivileges::AUDIT, CmsPrivileges::AUDITAPPROVER];

	public function cbInit()
	{

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
			$this->index_button[] = ["label" => "Export Collected Token", "icon" => "fa fa-download", "url" => route('export_collected_token_history') . '?' . urldecode(http_build_query(@$_GET)), "color" => "success"];
		}
	}

	public function getDetail($id)
	{
		$data = [];
		$data['page_title'] = 'Collect Token Details';
		$data['page_icon'] = 'fa fa-circle-o';
		$data['collected_tokens_history'] = CollectTokenHistory::with(['collectTokenMessages', 'history_lines.get_item_desc', 'history_lines.get_serial_number'])->find($id);

		return view("token.collect-token.detail-collect-token-history", $data);
	}

	public function actionButtonSelected($id_selected, $button_name)
	{
		//Your code here

	}

	    public function hook_query_index(&$query) {
	        if (in_array(CRUDBooster::myPrivilegeId(), [CmsPrivileges::SUPERADMIN, CmsPrivileges::AUDIT, CmsPrivileges::AUDITAPPROVER])) {
				$query->whereNull('collect_token_histories.deleted_at')
					->orderBy('collect_token_histories.id', 'desc');
			} else if (in_array(CRUDBooster::myPrivilegeId(), [CmsPrivileges::CSA, CmsPrivileges::CASHIER, CmsPrivileges::STOREHEAD])) {
				$query->where('collect_token_histories.location_id', CRUDBooster::myLocationId())
					->whereNull('collect_token_histories.deleted_at')
					->orderBy('collect_token_histories.id', 'desc');
			}
	            
	    }

// PRINT RECEIVE FORM

	public function getPrintForm(Request $request)
	{

		if ($request->ajax()) {

			$collectors = [];
			$bays = [];
			$all_bays = GashaMachines::getMachineWithBay()->where('location_id', CRUDBooster::myLocationId())->pluck('bays')->toArray();
			$collect_tokens = CollectTokenHistory::whereDate('created_at', $request->date)
				->with('history_lines.get_serial_number', 'history_lines.get_item_desc','getCreatedBy', 'getReceivedBy', 'getBay')
				->where('location_id', CRUDBooster::myLocationId())
				->where('statuses_id', '!=', Statuses::FORCASHIERTURNOVER)
				->get()
				->sortBy('bay_id');

			$collect_tokens = $collect_tokens->values();

			$bay_ids = $collect_tokens->pluck('bay_id');

			foreach ($all_bays as $key => $bay) {
				$explodedBays = explode(',', $bay);
				foreach ($explodedBays as $index => $value) {
					$bays[] = $value;
				}
			}

			foreach ($collect_tokens as $collected_token) {
				$exists = false;

				$name = $collected_token->getCreatedBy->name;
				$privilege = $collected_token->getCreatedBy->getPrivilege->name;

				foreach ($collectors as $collector) {
					if ($collector['name'] === $name && $collector['privilege'] === $privilege) {
						$exists = true;
						break;
					}
				}

				if (!$exists) {
					$collectors[] = [
						'name' => $name,
						'privilege' => $privilege
					];
				}
			}

			$missing_bay_ids = GashaMachinesBay::whereIn('id', collect($bays)->diff($bay_ids))->get();

			return response()->json([
				'missing_bays' => $missing_bay_ids,
				'store_name' => Locations::where('id', CRUDBooster::myLocationId())->value('location_name'),
				'date' => $request->date,
				'total_tokens' => $collect_tokens->sum('received_qty'),
				'collect_tokens' => $collect_tokens,
				'collectors' => $collectors,
				'receiver' => CmsUsers::with('getPrivilege')->find(CRUDBooster::myId())
			]);
		}

		$data = [];
		$data['page_title'] = 'Collect Token Form';
		$data['page_icon'] = 'fa fa-circle-o';
		$data['store_name'] = Locations::find(CRUDBooster::myLocationId());

			return view("token.collect-token.print-collecttokenhistory-form", $data);
		}

	public function exportCollectedTokenHistory(Request $request){
		$filter_column = $request->get('filter_column');

		return Excel::download(new ExportCollectedTokenHistory($filter_column), 'Export-Collected-Token- ' . now()->format('Ymd h_i_sa') . '.xlsx');
	}
}
