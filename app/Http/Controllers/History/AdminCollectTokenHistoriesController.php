<?php

namespace App\Http\Controllers\History;

use Session;
use App\Exports\ExportCollectedTokenHistory;
use App\Models\Audit\CollectRrTokens;
use Illuminate\Http\Request;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use App\Models\CmsModels\CmsPrivileges;
use App\Models\CmsUsers;
use App\Models\CollectTokenHistory;
use App\Models\PosFrontend\SwapHistory;
use App\Models\Submaster\CashFloatHistory;
use App\Models\Submaster\GashaMachines;
use App\Models\Submaster\GashaMachinesBay;
use App\Models\Submaster\Locations;
use App\Models\Submaster\Statuses;
use App\Models\Token\BeginningBalVsTokenOnHand;
use App\Models\Token\PulloutToken;
use App\Models\Token\StoreRrToken;
use App\Models\Token\TokenInventory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
		$this->col[] = ["label" => "Approved By", "name" => "approved_by", "join" => "cms_users,name"];
		$this->col[] = ["label" => "Approved Date", "name" => "approved_at"];
		$this->col[] = ["label" => "Created By", "name" => "created_by", "join" => "cms_users,name"];
		$this->col[] = ["label" => "Created Date", "name" => "created_at"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		if (in_array(CRUDBooster::myPrivilegeId(), self::CANPRINT)) {
			$this->index_button[] = ["label" => "Print Token Collection Form", "icon" => "fa fa-print", "url" => CRUDBooster::mainpath('print_token_form'), "color" => "info"];
		}

		if (in_array(CRUDBooster::myPrivilegeId(), self::EXPORTER)) {
			$this->index_button[] = [
				"label" => "Generate Collected Token Export",
				"icon" => "fa fa-refresh",
				"url" => "javascript:generateExportCollectToken('" . urldecode(http_build_query(@$_GET)) . "')",
				"color" => "success"
			];
		}

		$this->post_index_html = '
			<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
			<script src="' . asset('js/collect-token-export-generator.js') . '"></script>
		';

		$this->script_js = '
			function generateExportCollectToken(filters) {
				handleExportGenerator(filters);
			}
		';

		$this->style_css = "
			.swal2-popup {
				font-size: unset !important;
				border-radius: 10px !important;
			}
		";
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
		if (in_array(CRUDBooster::myPrivilegeId(), [CmsPrivileges::SUPERADMIN, CmsPrivileges::AUDIT, CmsPrivileges::AUDITAPPROVER, CmsPrivileges::OPERATIONMANAGER, CmsPrivileges::OPERATIONVIEWER])) {
			$query->whereNull('collect_rr_tokens.deleted_at')
				->where('reference_number', 'LIKE', '%CLTN-%')
				->whereIn('statuses_id', [Statuses::COLLECTED, Statuses::VOIDED])
				->orderBy('collect_rr_tokens.id', 'desc');
		} else if (in_array(CRUDBooster::myPrivilegeId(), [CmsPrivileges::CSA, CmsPrivileges::CASHIER, CmsPrivileges::STOREHEAD])) {
			$query->where('collect_rr_tokens.location_id', CRUDBooster::myLocationId())
				->where('reference_number', 'LIKE', '%CLTN-%')
				->whereNull('collect_rr_tokens.deleted_at')
				->whereIn('statuses_id', [Statuses::COLLECTED, Statuses::VOIDED])
				->orderBy('collect_rr_tokens.id', 'desc');
		}
	}

	// PRINT RECEIVE FORM

	public function getPrintForm(Request $request)
	{

		if ($request->ajax()) {

			$collectors = [];
			$bays = [];
			$all_bays = GashaMachines::getMachineWithBay()->where('location_id', CRUDBooster::myLocationId())->pluck('bays')->toArray();
			$collect_tokens = CollectRrTokens::whereDate('created_at', $request->date)
				->where('location_id', CRUDBooster::myLocationId())
				->where('statuses_id', '=', Statuses::COLLECTED)
				->whereNull('deleted_at')
				->with('lines.machineSerial', 'getCreatedBy.getPrivilege', 'getReceivedBy', 'getBay', 'lines.inventory_capsule_lines.getInventoryCapsule.item')
				->get()
				->sortBy('bay_id');

			// TOTAL TOKEN SWAP VALUE
			$totalTokenSwapValue = SwapHistory::whereDate('created_at', Carbon::parse($request->date)->subDay())
				->where('locations_id', CRUDBooster::myLocationId())
				->where('status', 'POSTED')
				->sum('token_value');

			// FOR TOTAL TOKENS DELIVERED
			$totalDeliveredTokens = StoreRrToken::where('to_locations_id', CRUDBooster::myLocationId())->sum('received_qty');
			$totalPulloutTokens = PulloutToken::where('locations_id', CRUDBooster::myLocationId())->sum('qty');
			$totalReceivedQty = StoreRrToken::where('to_locations_id', 10)->sum('received_qty');
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
				'receiver' => CmsUsers::with('getPrivilege')->find(CRUDBooster::myId()),
				'token_invetory' => TokenInventory::where('locations_id', CRUDBooster::myLocationId())->pluck('qty')->first(),
				'token_drawer_sod' => CashFloatHistory::where('entry_date', $request->date)
					->where('locations_id', CRUDBooster::myLocationId())
					->where('float_types_id', 1)
					->pluck('token_drawer')->first(),
				'token_sealed_sod' => CashFloatHistory::where('entry_date', $request->date)
					->where('locations_id', CRUDBooster::myLocationId())
					->where('float_types_id', 1)
					->pluck('token_sealed')->first(),
				'token_drawer_eod' => CashFloatHistory::where('entry_date', $request->date)
					->where('locations_id', CRUDBooster::myLocationId())
					->where('float_types_id', 2)
					->pluck('token_drawer')->first(),
				'token_sealed_eod' => CashFloatHistory::where('entry_date', $request->date)
					->where('locations_id', CRUDBooster::myLocationId())
					->where('float_types_id', 2)
					->pluck('token_sealed')->first(),
			]);
		}

		$data = [];
		$data['page_title'] = 'Collect Token Form';
		$data['page_icon'] = 'fa fa-circle-o';
		$data['store_name'] = Locations::find(CRUDBooster::myLocationId());
		$data['receiver'] = CmsUsers::select('id', 'name')->where('id_cms_privileges', CmsPrivileges::CASHIER)->where('location_id', CRUDBooster::myLocationId())->get();
		$data['report_generated_at'] = BeginningBalVsTokenOnHand::where('locations_id', CRUDBooster::myLocationId())->orderBy('created_at', 'DESC')->pluck('created_at')->first();

		return view("token.collect-token.print-collecttoken-form", $data);
	}

	public function postReport(Request $request)
	{
		try {

			$validated_data = $request->validate([
				'store_name' => 'required',
				'beginning_bal' => 'required',
				'token_on_hand' => 'required',
				'variance' => 'required',
				'generated_date' => 'required',
			]);

			$is_generated = BeginningBalVsTokenOnHand::where('locations_id', CRUDBooster::myLocationId())
				->where('generated_date', $validated_data['generated_date'])->first();

			if ($is_generated) {
				return response()->json([
					'already_generated' => 'This report is generated already. 
					 Double check your date entry if it is the date today, if not,
					 please select the date today to generate todays report.
					 And please strictly remember that this works only once per day. Thank You.'
				]);
			}

			$post_save = BeginningBalVsTokenOnHand::insert([
				'locations_id' => CRUDBooster::myLocationId(),
				'location_name' => $validated_data['store_name'],
				'total_beginning_bal' => $validated_data['beginning_bal'],
				'total_token_on_hand' => $validated_data['token_on_hand'],
				'variance' => $validated_data['variance'],
				'generated_date' => $validated_data['generated_date'],
				'created_by' => CRUDBooster::myId(),
				'created_at' => now(),
			]);

			if ($post_save) {
				return response()->json([
					'success' => 'Form printed successfully'
				]);
			}
		} catch (\Exception $e) {
			return response()->json([
				'error' => 'Error respose:' . $e->getMessage()
			]);
		}
	}

	// Generate Export
	public function exportCollectedTokenHistory(Request $request)
	{		
		$raw_filters = $request->input('filters');
		$filters = [];
		parse_str($raw_filters, $filters);

		if (in_array(CRUDBooster::myPrivilegeId(), [CmsPrivileges::SUPERADMIN, CmsPrivileges::AUDIT, CmsPrivileges::AUDITAPPROVER, CmsPrivileges::OPERATIONMANAGER])) {
			$query = DB::table('collect_rr_tokens')
				->leftJoin('collect_rr_token_lines', 'collect_rr_tokens.id', '=', 'collect_rr_token_lines.collected_token_id')
				->leftJoin('cms_users as cu1', 'collect_rr_tokens.confirmed_by', '=', 'cu1.id')
				->leftJoin('cms_users as cu2', 'collect_rr_tokens.approved_by', '=', 'cu2.id')
				->leftJoin('cms_users as cu3', 'collect_rr_tokens.received_by', '=', 'cu3.id')
				->leftJoin('cms_users as cu4', 'collect_rr_tokens.created_by', '=', 'cu4.id')
				->leftJoin('statuses', 'collect_rr_tokens.statuses_id', '=', 'statuses.id')
				->leftJoin('gasha_machines', 'collect_rr_token_lines.gasha_machines_id', '=', 'gasha_machines.id')
				->leftJoin('locations', 'collect_rr_tokens.location_id', '=', 'locations.id')
				->leftJoin('gasha_machines_bay', 'collect_rr_tokens.bay_id', '=', 'gasha_machines_bay.id')
				->select(
					'collect_rr_tokens.*',
					'collect_rr_token_lines.jan_number',
					'collect_rr_token_lines.item_description',
					'collect_rr_token_lines.no_of_token',
					'collect_rr_token_lines.qty',
					'collect_rr_token_lines.variance',
					'collect_rr_token_lines.projected_capsule_sales',
					'collect_rr_token_lines.current_capsule_inventory',
					'collect_rr_token_lines.actual_capsule_inventory',
					'collect_rr_token_lines.actual_capsule_sales',
					'collect_rr_token_lines.variance_type',
					'statuses.status_description',
					'gasha_machines.serial_number',
					'locations.location_name',
					'gasha_machines_bay.name',
					'cu1.name as confirmed_by_name',
					'cu2.name as approved_by_name',
					'cu3.name as received_by_name',
					'cu4.name as created_by_name',
				)
				->where('collect_rr_tokens.reference_number', 'LIKE', '%CLTN-%')
				->whereIn('collect_rr_tokens.statuses_id', [Statuses::COLLECTED, Statuses::VOIDED]);
		} else if (in_array(CRUDBooster::myPrivilegeId(), [CmsPrivileges::CSA, CmsPrivileges::CASHIER, CmsPrivileges::STOREHEAD])) {
			$query = DB::table('collect_rr_tokens')
				->leftJoin('collect_rr_token_lines', 'collect_rr_tokens.id', '=', 'collect_rr_token_lines.collected_token_id')
				->leftJoin('cms_users as cu1', 'collect_rr_tokens.confirmed_by', '=', 'cu1.id')
				->leftJoin('cms_users as cu2', 'collect_rr_tokens.approved_by', '=', 'cu2.id')
				->leftJoin('cms_users as cu3', 'collect_rr_tokens.received_by', '=', 'cu3.id')
				->leftJoin('cms_users as cu4', 'collect_rr_tokens.created_by', '=', 'cu4.id')
				->leftJoin('statuses', 'collect_rr_tokens.statuses_id', '=', 'statuses.id')
				->leftJoin('gasha_machines', 'collect_rr_token_lines.gasha_machines_id', '=', 'gasha_machines.id')
				->leftJoin('locations', 'collect_rr_tokens.location_id', '=', 'locations.id')
				->leftJoin('gasha_machines_bay', 'collect_rr_tokens.bay_id', '=', 'gasha_machines_bay.id')
				->select(
					'collect_rr_tokens.*',
					'collect_rr_token_lines.jan_number',
					'collect_rr_token_lines.item_description',
					'collect_rr_token_lines.no_of_token',
					'collect_rr_token_lines.qty',
					'collect_rr_token_lines.variance',
					'collect_rr_token_lines.projected_capsule_sales',
					'collect_rr_token_lines.current_capsule_inventory',
					'collect_rr_token_lines.actual_capsule_inventory',
					'collect_rr_token_lines.actual_capsule_sales',
					'collect_rr_token_lines.variance_type',
					'statuses.status_description',
					'gasha_machines.serial_number',
					'locations.location_name',
					'gasha_machines_bay.name',
					'cu1.name as confirmed_by_name',
					'cu2.name as approved_by_name',
					'cu3.name as received_by_name',
					'cu4.name as created_by_name',
				)
				->where('collect_rr_tokens.location_id', CRUDBooster::myLocationId())
				->where('collect_rr_tokens.reference_number', 'LIKE', '%CLTN-%')
				->whereIn('collect_rr_tokens.statuses_id', [Statuses::COLLECTED, Statuses::VOIDED]);
		}

		if ($filters['filter_column']) {
            foreach ((array) $filters['filter_column'] as $key => $fc) {
				$value = $fc['value'] ?? null;
				$type = $fc['type'] ?? null;

				if (!empty($value) && !empty($type)) {
					switch ($type) {
						case 'empty':
							$query->whereNull($key)->orWhere($key, '');
							break;
						case 'like':
						case 'not like':
							$query->where($key, $type, '%' . $value . '%');
				
							break;
						case 'in':
						case 'not in':
							$values = explode(',', $value);
							$type === 'in' ? $query->whereIn($key, $values) : $query->whereNotIn($key, $values);
							break;
						case 'between':
							$range = $value;
	
							if (count($range) == 2) {
								$startDate = date('Y-m-d', strtotime($range[0]));
								$endDate = date('Y-m-d', strtotime($range[1]));
								
								$query->whereBetween('collect_rr_tokens.created_at', [$startDate, $endDate]);
							}
							break;
						default:
							$query->where($key, $type, $value);
							
							break;
					}
				}
			}
		}

		$directory = 'tc_history/exports';
		$fileName = CRUDBooster::myId() . '_Collect_Token_History_' . date('Y-m-d_H-i-s') . '.csv';
		$filePath = storage_path('app/' . $directory . '/' . $fileName);
		Storage::makeDirectory(storage_path('app/' . $directory), 0777, true, true);

		// Open file for writing
		$file = fopen($filePath, 'w');

		// headers
		fputcsv($file, [
			'Reference Number', 'Status', 'Location', 'JAN #', 'Item Description', 'Bay', 
			'Machine #', 'No of Token', 'Token Collected', 'Variance', 
			'Projected Capsule Sales', 'Current Capsule Inventory', 'Actual Capsule Inventory', 
			'Actual Capsule Sales', 'Variance Type', 'Confirmed By', 'Confirmed Date', 
			'Approved By', 'Approved Date', 'Received By', 'Received Date', 
			'Created By', 'Created Date'
		]);

		// Process data using cursor
		foreach ($query->cursor() as $per_collect_token) {
			fputcsv($file, [
				$per_collect_token->reference_number ?? 'NULL',
				$per_collect_token->status_description ?? 'NULL',
				$per_collect_token->location_name ?? 'NULL',
				$per_collect_token->jan_number ?? 'NULL',
				$per_collect_token->item_description ?? 'NULL',
				$per_collect_token->name ?? 'NULL',
				$per_collect_token->serial_number ?? 'NULL',
				$per_collect_token->no_of_token ?? 0,
				$per_collect_token->qty ?? 0,
				$per_collect_token->variance ?? 0,
				$per_collect_token->projected_capsule_sales ?? 0,
				$per_collect_token->current_capsule_inventory ?? 0,
				$per_collect_token->actual_capsule_inventory ?? 0,
				$per_collect_token->actual_capsule_sales ?? 0,
				$per_collect_token->variance_type ?? 'NULL',
				$per_collect_token->confirmed_by_name ?? 'NULL',
				$per_collect_token->confirmed_at ?? 'NULL',
				$per_collect_token->approved_by_name ?? 'NULL',
				$per_collect_token->approved_at ?? 'NULL',
				$per_collect_token->received_by_name ?? 'NULL',
				$per_collect_token->received_at ?? 'NULL',
				$per_collect_token->created_by_name ?? 'NULL',
				$per_collect_token->created_at ?? 'NULL',
			]);
		}

		// Close the file after writing
		fclose($file);

		return response()->json([
			'success' => true,
			'message' => 'CSV file has been generated successfully.',
			'file_name' => $fileName,
		]);

	}

	// Download generated export
	public function downloadFile(Request $request)
	{
		try {
			$filename = $request->input('file_name'); 
			$filePath = "tc_history/exports/{$filename}";

			// Check if the file exists
			if (!Storage::exists($filePath)) {
				return response()->json(['error' => 'File not found'], 404);
			}

			// StreamedResponse to download file
			$response = new StreamedResponse(function () use ($filePath) {
				$stream = Storage::readStream($filePath); 
				fpassthru($stream);
				fclose($stream);
			}, 200, [
				'Content-Type' => Storage::mimeType($filePath),
				'Content-Disposition' => 'attachment; filename="'.basename($filePath).'"',
			]);

			// Delete file after response has been sent
			$response->send();
			Storage::delete($filePath); 

			return $response;

		} catch (\Exception $e) {
			return response()->json([
				'error' => 'An error occurred while downloading the file.',
				'message' => $e->getMessage(),
			], 500);
		}
	}
}
