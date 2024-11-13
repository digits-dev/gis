<?php

namespace App\Http\Controllers\Token;

use App\Models\Audit\CollectRrTokens;
use App\Models\CmsModels\CmsPrivileges;
use App\Models\Submaster\Counter;
use App\Models\Submaster\Statuses;
use App\Models\Submaster\GashaMachines;
use App\Models\Submaster\TokenConversion;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminCollectTokenController extends \crocodicstudio\crudbooster\controllers\CBController
{

	private const CANCREATE = [CmsPrivileges::SUPERADMIN, CmsPrivileges::CSA];
	private const FORCASHIERTURNOVER = [CmsPrivileges::SUPERADMIN, CmsPrivileges::CASHIER];
	private const CANPRINT = [CmsPrivileges::SUPERADMIN, CmsPrivileges::CSA];
	private const APPROVER = [CmsPrivileges::SUPERADMIN, CmsPrivileges::OPERATIONSHEAD];

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
		$this->button_export = true;
		$this->table = "collect_rr_tokens";


		$this->col = [];
		$this->col[] = ["label" => "Reference Number", "name" => "reference_number"];
		$this->col[] = ["label" => "Status", "name" => "statuses_id", "join" => "statuses,status_description"];
		$this->col[] = ["label" => "Location", "name" => "location_id", "join" => "locations,location_name"];
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


	public function hook_query_index(&$query) {
		if(in_array(CRUDBooster::myPrivilegeId(),[CmsPrivileges::SUPERADMIN, CmsPrivileges::AUDIT, CmsPrivileges::AUDITAPPROVER])){
			$query->whereNull('collect_rr_tokens.deleted_at')
				  ->orderBy('collect_rr_tokens.id', 'desc');
		}else if(in_array(CRUDBooster::myPrivilegeId(),[
				CmsPrivileges::CASHIER,
				CmsPrivileges::OIC, 
				CmsPrivileges::AREAMANAGER,
				CmsPrivileges::STAFF1,
				CmsPrivileges::STAFF2]
			)){
				$query->where('collect_rr_tokens.location_id', CRUDBooster::myLocationId())
					->whereNull('collect_rr_tokens.deleted_at')
					->orderBy('collect_rr_tokens.id', 'desc');
			}
	}

	public function getDetail($id)
	{
		$data = [];
		$data['page_title'] = 'Collect Token Details';
		$data['page_icon'] = 'fa fa-circle-o';
		$data['collected_tokens'] = CollectRrTokens::with(['lines', 'getLocation'])->where('id', $id)->get();
		
		return view("token.collect-token.detail-collect-token", $data);
	}

	public function getPrintForm(){
		$data = [];
		$data['page_title'] = 'Token Collection Form';
		$data['page_icon'] = 'fa fa-circle-o';

		return view("token.collect-token.print-collecttoken-form", $data);
	}

	// STEP 1


	public function getCollectToken()
	{
		$data = [];
		$data['page_title'] = 'Collect Token';
		$data['page_icon'] = 'fa fa-circle-o';
		$data['gasha_machines'] = GashaMachines::getMachineWithBay()->get();

		return view("token.collect-token.add-collect-token", $data);
	}

	public function getMachines(Request $request)
	{
		$location_id = $request->input('location');
		$bay = $request->input('bay');
		$machines = GashaMachines::where('location_id', $location_id)->where('bay', $bay)->where('status', 'ACTIVE')->get();

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
				'header_location_id' => 'required'
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
			'collected_qty' => $validatedData['total_qty'],
			'variance' => $header_variance,
			'created_by' => CRUDBooster::myId()
		]);

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
	public function getCashierTurnover($id){
		$data = [];
		$data['page_title'] = 'Collect Token Details';
		$data['page_icon'] = 'fa fa-circle-o';
		$data['collected_tokens'] = CollectRrTokens::with(['lines', 'getLocation'])->where('id', $id)->get();
		
		return view("token.collect-token.cashier-turnover-collect-token", $data);
	}

	public function postCashierTurnover(Request $request)
	{
		// Validate
		try {
			$validatedData = $request->validate([
				'collectedTokenHeader_id' => 'required',
			]);
		} catch (ValidationException $e) {
			$errors = $e->validator->errors()->all();
			$errorMessage = implode('<br>', $errors);
			CRUDBooster::redirect(CRUDBooster::mainpath(), $errorMessage, 'danger');
		}

		$collectTokenHeader = CollectRrTokens::find($validatedData['collectedTokenHeader_id']);
		if (!$collectTokenHeader) {
			CRUDBooster::redirect(CRUDBooster::mainpath(), 'Collect Token Header not found.', 'danger');
		}

		$collectTokenHeader->update([
			'statuses_id' => Statuses::FORSTOREHEADAPPROVAL,
			'updated_by' => CRUDBooster::myId(),
		]);

		$collectTokenHeader->lines()->update([
			'line_status' => Statuses::FORSTOREHEADAPPROVAL,
			'updated_at' => now(),
		]);

		CRUDBooster::redirect(CRUDBooster::mainpath(), "Token collect updated successfully!", 'success');
	}


	// STEP 3

	public function getCollectTokenApproval($id){
		$data = [];
		$data['page_title'] = 'Review Token Details';
		$data['page_icon'] = 'fa fa-circle-o';
		$data['collected_tokens'] = CollectRrTokens::with(['lines', 'getLocation'])->where('id', $id)->get();
		
		return view("token.collect-token.approve-collect-token", $data);
	}

}
