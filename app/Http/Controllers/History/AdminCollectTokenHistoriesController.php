<?php

namespace App\Http\Controllers\History;

use App\Exports\ExportCollectedTokenHistory;
use App\Models\Audit\CollectRrTokens;
use Session;
use Illuminate\Http\Request;
use DB;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use App\Models\CmsModels\CmsPrivileges;
use App\Models\CmsUsers;
use App\Models\CollectTokenHistory;
use App\Models\PosFrontend\SwapHistory;
use App\Models\Submaster\GashaMachines;
use App\Models\Submaster\GashaMachinesBay;
use App\Models\Submaster\Locations;
use App\Models\Submaster\Statuses;
use App\Models\Token\PulloutToken;
use App\Models\Token\StoreRrToken;
use Carbon\Carbon;
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
		$this->table = "collect_rr_tokens";
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
		$data['collected_tokens'] = CollectRrTokens::with(['lines', 'getBay', 'getLocation', 'getCreatedBy', 'getConfirmedBy', 'getApprovedBy', 'getReceivedBy', 'collectTokenMessages'])->find($id);

		return view("token.collect-token.detail-collect-token-history", $data);
	}

	public function actionButtonSelected($id_selected, $button_name)
	{
		//Your code here

	}

	public function hook_query_index(&$query)
	{
		if (in_array(CRUDBooster::myPrivilegeId(), [CmsPrivileges::SUPERADMIN, CmsPrivileges::AUDIT, CmsPrivileges::AUDITAPPROVER, CmsPrivileges::OPERATIONMANAGER])) {
			$query->whereNull('collect_rr_tokens.deleted_at')
				->where('reference_number', 'LIKE', '%CLTN-%')
				->where('statuses_id', Statuses::COLLECTED)
				->orderBy('collect_rr_tokens.id', 'desc');
		} else if (in_array(CRUDBooster::myPrivilegeId(), [CmsPrivileges::CSA, CmsPrivileges::CASHIER, CmsPrivileges::STOREHEAD])) {
			$query->where('collect_rr_tokens.location_id', CRUDBooster::myLocationId())
				->where('reference_number', 'LIKE', '%CLTN-%')
				->whereNull('collect_rr_tokens.deleted_at')
				->where('statuses_id', Statuses::COLLECTED)
				->orderBy('collect_rr_tokens.id', 'desc');
		}
	}

	// PRINT RECEIVE FORM
	public function getPrintForm(Request $request)
	{

		if ($request->ajax()){

			$collectors = [];
			$bays = [];
			$all_bays = GashaMachines::getMachineWithBay()->where('location_id', CRUDBooster::myLocationId())->pluck('bays')->toArray();
			$collect_tokens = CollectRrTokens::whereDate('created_at', $request->date)
			->where('location_id', CRUDBooster::myLocationId())
			->where('statuses_id', '!=', Statuses::FORCASHIERTURNOVER)
			->with('lines.machineSerial', 'getCreatedBy.getPrivilege', 'getReceivedBy', 'getBay', 'lines.inventory_capsule_lines.getInventoryCapsule.item')
			->get()
			->sortBy('bay_id');

			// TOTAL TOKEN SWAP VALUE

			$totalTokenSwapValue = SwapHistory::whereDate('created_at', Carbon::parse($request->date)->subDay())	
				->where('locations_id', CRUDBooster::myLocationId())
				->where('status', 'POSTED')
				->sum('token_value');

			// FOR TOTAL TOKENS DELIVERED

			$totalDeliveredTokens = StoreRrToken::where('to_locations_id', CRUDBooster::myLocationId())
			->sum('received_qty');

			$totalPulloutTokens = PulloutToken::where('locations_id', CRUDBooster::myLocationId())
			->sum('qty');

			$totalReceivedQty = StoreRrToken::where('to_locations_id', 10)
			->sum('received_qty');

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

			

			// TOKEN COLLECTION REPORT

			return response()->json([
				'missing_bays' => $missing_bay_ids,
				'store_name' => Locations::where('id', CRUDBooster::myLocationId())->value('location_name'),
				'date' => $request->date,
				'total_tokens' => $collect_tokens->sum('received_qty'),
				'token_swap_from_cashier_report' => $totalTokenSwapValue,
				'token_swap_from_cashier_report_date' => Carbon::parse($request->date)->subDay()->format('F d, Y'),
				'total_tokens_delivered' => $totalDeliveredTokens - $totalPulloutTokens,
				'formatted_request_date' => Carbon::parse($request->date)->format('F d, Y'),
				'collect_tokens' => $collect_tokens,
				'collectors' => $collectors,
				'receiver' => CmsUsers::with('getPrivilege')->find(CRUDBooster::myId())
			]);
		}
		
		$data = [];
		$data['page_title'] = 'Collect Token Form';
		$data['page_icon'] = 'fa fa-circle-o';
		$data ['store_name'] = Locations::find(CRUDBooster::myLocationId());

	


		$data ['receiver'] = CmsUsers::select('id', 'name')->where('id_cms_privileges', CmsPrivileges::CASHIER)->where('location_id', CRUDBooster::myLocationId())->get();

		

		return view("token.collect-token.print-collecttoken-form", $data);
	}

	public function exportCollectedTokenHistory(Request $request)
	{
		$filter_column = $request->get('filter_column');

		return Excel::download(new ExportCollectedTokenHistory($filter_column), 'Export-Collected-Token- ' . now()->format('Ymd h_i_sa') . '.xlsx');
	}
}
