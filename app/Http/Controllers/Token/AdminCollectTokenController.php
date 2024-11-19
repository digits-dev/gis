<?php

namespace App\Http\Controllers\Token;

use App\Exports\ExportCollectedToken;
use App\Models\Audit\CollectRrTokenLines;
use App\Models\Audit\CollectRrTokens;
use App\Models\Capsule\CapsuleSales;
use App\Models\Capsule\InventoryCapsuleLine;
use App\Models\CmsModels\CmsPrivileges;
use App\Models\CmsUsers;
use App\Models\CollectTokenMessage;
use App\Models\Submaster\Counter;
use App\Models\Submaster\Statuses;
use App\Models\Submaster\GashaMachines;
use App\Models\Submaster\GashaMachinesBay;
use App\Models\Submaster\SalesType;
use App\Models\Submaster\Locations;
use App\Models\Submaster\TokenConversion;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class AdminCollectTokenController extends \crocodicstudio\crudbooster\controllers\CBController
{

	private const CANCREATE = [CmsPrivileges::SUPERADMIN, CmsPrivileges::CSA, CmsPrivileges::CASHIER];
	private const FORCASHIERTURNOVER = [CmsPrivileges::SUPERADMIN, CmsPrivileges::CASHIER];
	private const FORCONFIRMATION = [CmsPrivileges::SUPERADMIN, CmsPrivileges::OPERATIONSHEAD];
	private const CANPRINT = [CmsPrivileges::SUPERADMIN, CmsPrivileges::CSA];
	private const APPROVER = [CmsPrivileges::SUPERADMIN, CmsPrivileges::OPERATIONSHEAD];
	private const EXPORTER = [CmsPrivileges::SUPERADMIN];

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

		$this->col = [];
		$this->col[] = ["label" => "Reference Number", "name" => "reference_number"];
		$this->col[] = ["label" => "Status", "name" => "statuses_id", "join" => "statuses,status_description"];
		$this->col[] = ["label" => "Location", "name" => "location_id", "join" => "locations,location_name"];
		$this->col[] = ["label" => "Bay", "name" => "bay_id", "join" => "gasha_machines_bay,name"];
		$this->col[] = ["label" => "Collected Qty", "name" => "collected_qty", 'callback_php' => 'number_format($row->collected_qty)'];
		$this->col[] = ["label" => "Received Qty", "name" => "received_qty", 'callback_php' => 'number_format($row->received_qty)'];
		$this->col[] = ["label" => "Variance", "name" => "variance"];
		$this->col[] = ["label" => "Received By", "name" => "received_by", "join" => "cms_users,name"];
		$this->col[] = ["label" => "Received Date", "name" => "received_at"];
		$this->col[] = ["label" => "Created By", "name" => "created_by", "join" => "cms_users,name"];
		$this->col[] = ["label" => "Created Date", "name" => "created_at"];

		if (CRUDBooster::isCreate()) {
			if (in_array(CRUDBooster::myPrivilegeId(), self::CANCREATE)) {
				$this->index_button[] = ["label" => "Add Collect Token", "icon" => "fa fa-plus-circle", "url" => CRUDBooster::mainpath('add_collect_token'), "color" => "success"];
			}
		}

		if (in_array(CRUDBooster::myPrivilegeId(), self::EXPORTER)) {
			$this->index_button[] = ["label" => "Export Collected Token", "icon" => "fa fa-download", "url" => route('export_collected_token') . '?' . urldecode(http_build_query(@$_GET)), "color" => "success"];
		}
		
		if (in_array(CRUDBooster::myPrivilegeId(), self::CANPRINT)) {
			$this->index_button[] = ["label" => "Print Token Collection Form", "icon" => "fa fa-print", "url" => CRUDBooster::mainpath('print_token_form'), "color" => "info"];
		}

		if (in_array(CRUDBooster::myPrivilegeId(), self::FORCASHIERTURNOVER)) {
			$this->addaction[] = [
				'title' => 'Cashier Turnover',
				'url' => CRUDBooster::mainpath('cashier_turnover/[id]'),
				'icon' => 'fa fa-pencil',
				'color' => 'warning',
				'showIf' => "[statuses_id]=='" . Statuses::FORCASHIERTURNOVER . "'"
			];
		}
		
		if (in_array(CRUDBooster::myPrivilegeId(), self::FORCONFIRMATION)) {
			$this->addaction[] = [
				'title' => 'For Confirmation',
				'url' => CRUDBooster::mainpath('confirm_token/[id]'),
				'icon' => 'fa fa-check',
				'color' => 'success',
				'showIf' => "[statuses_id]=='" . Statuses::FORCONFIRMATION . "'"
			];
		}

		if (in_array(CRUDBooster::myPrivilegeId(), self::APPROVER)) {
			$this->addaction[] = [
				'title' => 'For Approval',
				'url' => CRUDBooster::mainpath('review/[id]'),
				'icon' => 'fa fa-thumbs-up',
				'color' => 'info',
				'showIf' => "[statuses_id] == '" . Statuses::FORSTOREHEADAPPROVAL . "'"
			];
		}
	}

	public function getDetail($id)
	{
		$data = [];
		$data['page_title'] = 'Collect Token Details';
		$data['page_icon'] = 'fa fa-circle-o';
		$data['collected_tokens'] = CollectRrTokens::with(['lines', 'getBay', 'getLocation', 'getCreatedBy', 'getConfirmedBy', 'getApprovedBy', 'getReceivedBy', 'collectTokenMessages'])->find($id);


		return view("token.collect-token.detail-collect-token", $data);
	}

	public function hook_query_index(&$query)
	{
		if (in_array(CRUDBooster::myPrivilegeId(), [1, 4, 14])) {
			$query->whereNull('collect_rr_tokens.deleted_at')
				->orderBy('collect_rr_tokens.id', 'desc');
		} else if (in_array(CRUDBooster::myPrivilegeId(), [3, 5, 6, 11, 12])) {
			$query->where('collect_rr_tokens.location_id', CRUDBooster::myLocationId())
				->whereNull('collect_rr_tokens.deleted_at')
				->orderBy('collect_rr_tokens.id', 'desc');
		}
	}

	// PRINT RECEIVE FORM

	public function getPrintForm(Request $request)
	{

		if ($request->ajax()){
			$collect_tokens = CollectRrTokens::whereIn('id', $request->references)
				->with('lines.machineSerial', 'getCreatedBy.getPrivilege', 'getBay', 'lines.inventory_capsule_lines.getInventoryCapsule.item')
				->orderBy('bay_id', 'asc')
				->get();

			$receiver = CmsUsers::with('getPrivilege')->find($request->receiver);

			$collectors = [];

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

			return response()->json([
				'store_name' => Locations::where('id', CRUDBooster::myLocationId())->value('location_name'),
				'date' => date('Y-m-d H:i:s'),
				'collect_tokens' => $collect_tokens,
				'collectors' => $collectors,
				'receiver' => $receiver
			]);
		}
		
		$data = [];
		$data['page_title'] = 'Collect Token Form';
		$data['page_icon'] = 'fa fa-circle-o';
		$data ['store_name'] = Locations::find(CRUDBooster::myLocationId());
		$data ['reference_numbers'] = CollectRrTokens::with('getBay')->select('id','reference_number', 'bay_id')
			->where('location_id', CRUDBooster::myLocationId())
			->where('statuses_id', Statuses::COLLECTED)
			->get();
		$data ['receiver'] = CmsUsers::select('id', 'name')->where('id_cms_privileges', CmsPrivileges::CASHIER)->where('location_id', CRUDBooster::myLocationId())->get();


		return view("token.collect-token.print-collecttoken-form", $data);
	}

	// STEP 1

	public function getCollectToken()
	{
		$data = [];
		$data['page_title'] = 'Collect Token';
		$data['page_icon'] = 'fa fa-circle-o';
		$data['gasha_machines'] = GashaMachines::getMachineWithBay()->get();
		$data['gasha_machine_bay'] = GashaMachinesBay::all();

		return view("token.collect-token.add-collect-token", $data);
	}

	public function getMachines(Request $request)
	{
		$location_id = $request->input('location');
		$bay = $request->input('bay');
		$machines = GashaMachines::where('location_id', $location_id)->where('bay', $bay)->where('status', 'ACTIVE')->get();
		
		// foreach ($machines as $per_machine) {
		// 	$machine_id = $per_machine->id;
		// 	$CollectRrTokenLines::where('gasha_machines_id', $machine_id)->get();

		// }

		return response()->json($machines);
	}

	public function postCollectToken(Request $request)
	{
		// validations 
		try {
			$validatedData = $request->validate([
				'total_qty' => 'required',
				'gasha_machines_id' => 'required',
				'no_of_token' => 'required',
				'qty' => 'required',
				'variance' => 'required',
				'location_id' => 'required',
				'header_location_id' => 'required',
				'header_bay_id' => 'required'
			]);
		} catch (ValidationException $e) {
			$errors = $e->validator->errors()->all();
			$errorMessage = implode('<br>', $errors);
			CRUDBooster::redirect(CRUDBooster::mainpath(), $errorMessage, 'danger');
		}

		// collect token lines to map each array 
		$ValidatedLines = array_map(function ($gasha_machines_id, $no_of_token, $qty, $variance, $location_id) {
			return [
				'gasha_machines_id' => $gasha_machines_id,
				'no_of_token' => $no_of_token,
				'qty' => $qty,
				'variance' => $variance,
				'location_id' => $location_id,
			];
		}, $validatedData['gasha_machines_id'], $validatedData['no_of_token'], $validatedData['qty'], $validatedData['variance'], $validatedData['location_id']);

		// for collect token header if there's variance in the set
		$header_variance = (count(array_filter($validatedData['variance'], fn($value) => $value != 0)) > 0) ? 'Yes' : 'No';

		// collect tokens headers
		$collectTokenHeader = CollectRrTokens::firstOrCreate([
			'reference_number' => Counter::getNextReference(CRUDBooster::getCurrentModule()->id),
			'statuses_id' => Statuses::FORCASHIERTURNOVER,
			'location_id' => $validatedData['header_location_id'],
			'bay_id' => $validatedData['header_bay_id'],
			'collected_qty' => $validatedData['total_qty'],
			'variance' => $header_variance,
			'created_by' => CRUDBooster::myId()
		]);

		// Save remarks if provided
		if ($request->has('remarks') && !empty($request->input('remarks'))) {
			$collectTokenHeader->collectTokenMessages()->create([
				'collect_token_id' => $collectTokenHeader->id,
				'message' => $request->input('remarks'),
				'created_by' => CRUDBooster::myId(),
				'created_at' => now(),
			]);
		}

		// collect tokens lines
		foreach ($ValidatedLines as $item) {
			$collectTokenHeader->lines()->create([
				'line_status' => Statuses::FORCASHIERTURNOVER,
				'collected_token_id' => $collectTokenHeader->id,
				'gasha_machines_id' => $item['gasha_machines_id'],
				'no_of_token' => $item['no_of_token'],
				'qty' => $item['qty'],
				'variance' => $item['variance'],
				'location_id' => $item['location_id'],
				'current_cash_value' => TokenConversion::where('status', 'ACTIVE')->first()->current_cash_value,
				'created_at' => now(),
			]);
		}

		CRUDBooster::redirect(CRUDBooster::mainpath(), "Token collected successfully!", 'success');
	}

	//STEP 2

	public function getCashierTurnover($id)
	{
		$data = [];
		$data['page_title'] = 'Collect Token Details';
		$data['page_icon'] = 'fa fa-circle-o';
		$data['collected_tokens'] = CollectRrTokens::with(['lines', 'getLocation', 'collectTokenMessages'])->where('id', $id)->get();

		return view("token.collect-token.cashier-turnover-collect-token", $data);
	}

	public function postCashierTurnover(Request $request)
	{
	
		$collectTokenHeader = CollectRrTokens::find($request->collectedTokenHeader_id);
		if (!$collectTokenHeader) {
			CRUDBooster::redirect(CRUDBooster::mainpath(), 'Collect Token Header not found.', 'danger');
		}

		$collectTokenHeader->update([
			'statuses_id' => Statuses::FORCONFIRMATION,
			'received_by' => CRUDBooster::myId(),
			'received_at' => now()
		]);

		$collectTokenHeader->lines()->update([
			'line_status' => Statuses::FORCONFIRMATION,
			'updated_at' => now(),
		]);

		
		CRUDBooster::redirect(CRUDBooster::mainpath(), "{$collectTokenHeader->reference_number} Confirmed successfully!", 'success');
	}

	//STEP 3
	public function getConfirmToken($id)
	{
		$data = [];
		$data['page_title'] = 'Collect Token Details';
		$data['page_icon'] = 'fa fa-circle-o';
		$data['collected_tokens'] = CollectRrTokens::with(['lines', 'getLocation', 'collectTokenMessages'])->where('id', $id)->get();

		return view("token.collect-token.confirm-collect-token", $data);
	}

	public function postConfirmToken(Request $request)
	{
		// Validate
		try {
			$validatedData = $request->validate([
				'collectedTokenHeader_id' => 'required',
				'lines_ids' => 'required',
				'variance_type' => 'required',
				'variance' => 'required',
				'projectedCapsuleSales' => 'required',
				'currentMachineInventory' => 'required',
				'actualCapsuleInventory' => 'required',
				'actualCapsuleSales' => 'required',
			]);
		} catch (ValidationException $e) {
			$errors = $e->validator->errors()->all();
			$errorMessage = implode('<br>', $errors);
			CRUDBooster::redirect(CRUDBooster::mainpath(), $errorMessage, 'danger');
		}

		// collect token lines to map each array 
		$ValidatedLines = array_map(function ($lines_ids, $variance_type, $variance, $projectedCapsuleSales, $currentMachineInventory, $actualCapsuleInventory, $actualCapsuleSales) {
			return [
				'lines_ids' => $lines_ids,
				'variance_type' => $variance_type,
				'variance' => $variance,
				'projectedCapsuleSales' => $projectedCapsuleSales,
				'currentMachineInventory' => $currentMachineInventory,
				'actualCapsuleInventory' => $actualCapsuleInventory,
				'actualCapsuleSales' => $actualCapsuleSales,
			];
		}, $validatedData['lines_ids'], $validatedData['variance_type'], $validatedData['variance'], $validatedData['projectedCapsuleSales'], $validatedData['currentMachineInventory'], $validatedData['actualCapsuleInventory'], $validatedData['actualCapsuleSales']);

		$collectTokenHeader = CollectRrTokens::find($validatedData['collectedTokenHeader_id']);
		if (!$collectTokenHeader) {
			CRUDBooster::redirect(CRUDBooster::mainpath(), 'Collect Token Header not found.', 'danger');
		}

		// update collect token header
		$collectTokenHeader->update([
			'statuses_id' => Statuses::FORSTOREHEADAPPROVAL,
			'confirmed_by' => CRUDBooster::myId(),
			'confirmed_at' => now()
		]);

		// update each collect token line
		$updatedCount = 0;
		foreach ($ValidatedLines as $perItem) {
			$updated = $collectTokenHeader->lines()->where('id', $perItem['lines_ids'])->update([
				'line_status' => Statuses::FORSTOREHEADAPPROVAL,
				'variance' => $perItem['variance'],
				'variance_type' => $perItem['variance_type'],
				'projected_capsule_sales' => $perItem['projectedCapsuleSales'],
				'actual_capsule_sales' => $perItem['actualCapsuleSales'],
				'current_capsule_inventory' => $perItem['currentMachineInventory'],
				'actual_capsule_inventory' => $perItem['actualCapsuleInventory'],
				'updated_at' => now(),
			]);

			if ($updated) {
				$updatedCount++;
			}
		}
		
		CRUDBooster::redirect(CRUDBooster::mainpath(), "{$collectTokenHeader->reference_number} Confirmed successfully!", 'success');
	}

	public function postNewRemarks(Request $request)
	{
		$collect_token_id = $request->input('MessageCollectTokenId');
		$new_remarks = $request->input('NewRemarks');

		$savedRemaks = CollectTokenMessage::firstOrCreate([
			'collect_token_id' => $collect_token_id,
			'message' => $new_remarks,
			'created_by' => CRUDBooster::myId(),
		]);

		return response()->json($savedRemaks);
	}

	// STEP 4
	public function getCollectTokenApproval($id)
	{
		$data = [];
		$data['page_title'] = 'Review Token Details';
		$data['page_icon'] = 'fa fa-circle-o';
		$data['collected_tokens'] = CollectRrTokens::with(['lines', 'getCreatedBy', 'getConfirmedBy', 'getLocation', 'collectTokenMessages'])->find($id);

		return view("token.collect-token.approve-collect-token", $data);
	}

	public function postCollectTokenApproval(Request $request)
	{

		$collectTokenHeader = CollectRrTokens::find($request['collect_token_id']);

		if ($request->action_type == 'approve'){
			
			// validations 
			try {
				$validatedData = $request->validate([
					'inventory_capsule_lines_id' => 'required',
					'actual_capsule_inventory' => 'required',
					'ref_number' => 'required',
					'jan_code' => 'required',
					'gasha_machines_id' => 'required',
					'location_id' => 'required',
					'actual_capsule_sales' => 'required',
				]);
			} catch (ValidationException $e) {
				$errors = $e->validator->errors()->all();
				$errorMessage = implode('<br>', $errors);
				CRUDBooster::redirect(CRUDBooster::mainpath(), $errorMessage, 'danger');
			}

			// array map for capsule sales
			$ValidatedCapsuleSalesLines = array_map(function ($jan_code, $gasha_machines_id, $location_id, $actual_capsule_sales) {
				return [
					'jan_code' => $jan_code,
					'gasha_machines_id' => $gasha_machines_id,
					'location_id' => $location_id,
					'actual_capsule_sales' => $actual_capsule_sales,
				];
			}, $validatedData['jan_code'], $validatedData['gasha_machines_id'], $validatedData['location_id'], $validatedData['actual_capsule_sales']);

			// arrap map for inventory capsule lines
			$ValidatedInventoryCapsuleLines = array_map(function ($inventory_capsule_lines_id, $actual_capsule_inventory) {
				return [
					'inventory_capsule_lines_id' => $inventory_capsule_lines_id,
					'actual_capsule_inventory' => $actual_capsule_inventory,
				];
			}, $validatedData['inventory_capsule_lines_id'], $validatedData['actual_capsule_inventory']);

			// create capsule sales
			foreach($ValidatedCapsuleSalesLines as $perCapsuleSale){
				if ($perCapsuleSale['actual_capsule_sales'] > 0) {
					CapsuleSales::firstOrCreate([
						'reference_number' => $validatedData['ref_number'],
						'item_code' => $perCapsuleSale['jan_code'],
						'gasha_machines_id' => $perCapsuleSale['gasha_machines_id'],
						'locations_id' => $perCapsuleSale['location_id'],
						'qty' => $perCapsuleSale['actual_capsule_sales'],
						'sales_type_id' => SalesType::COLLECTTOKEN,
						'created_by' => CRUDBooster::myId(),
						'created_at' => now()
					]);
				}
			}

			// update inventory capsule lines qty
			foreach($ValidatedInventoryCapsuleLines as $perLine){
				InventoryCapsuleLine::where('id', $perLine['inventory_capsule_lines_id'])->update([
					'qty' => $perLine['actual_capsule_inventory']
				]);
			}

			// approved
			$collectTokenHeader->update([
				'statuses_id' => Statuses::COLLECTED,
				'approved_by' => CRUDBooster::myId(),
				'approved_at' => now(),
			]);
		}

		else {
			// rejected
			$collectTokenHeader->update([
				'statuses_id' => Statuses::FORCONFIRMATION,
				'rejected_by' => CRUDBooster::myId(),
				'rejected_at' => now(),
			]);
		}

		$actionType = $request->action_type == 'approve' ? "Approved" : "Rejected";
		CRUDBooster::redirect(CRUDBooster::mainpath(), $collectTokenHeader->reference_number . " has been " . $actionType . "!", 'success');
	}

	public function exportCollectedToken(Request $request){
		$filter_column = $request->get('filter_column');

		return Excel::download(new ExportCollectedToken($filter_column), 'Export-Collected-Token- ' . now()->format('Ymd h_i_sa') . '.xlsx');
	}
}
