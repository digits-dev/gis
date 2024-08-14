<?php

namespace App\Http\Controllers\Submaster;

use Excel;
use Session;
use CRUDBooster;
use Carbon\Carbon;
use App\Models\Submaster\Item;
use Illuminate\Http\Request;
use App\Imports\AdminItemsImport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminItemsController extends \crocodicstudio\crudbooster\controllers\CBController
{

	public function cbInit()
	{

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "digits_code";
		$this->limit = "20";
		$this->orderby = "digits_code,desc";
		$this->global_privilege = false;
		$this->button_table_action = true;
		$this->button_bulk_action = true;
		$this->button_action_style = "button_icon";
		$this->button_add = false;
		$this->button_edit = false;
		$this->button_delete = false;
		$this->button_detail = true;
		$this->button_show = true;
		$this->button_filter = true;
		$this->button_import = false;
		$this->button_export = false;
		$this->table = "items";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label" => "JAN #", "name" => "digits_code"];
		$this->col[] = ["label" => "Digits Code", "name" => "digits_code2"];
		$this->col[] = ["label" => "Item Description", "name" => "item_description"];
		$this->col[] = ["label" => "No Of Tokens", "name" => "no_of_tokens"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		$this->form[] = ['label' => 'Digits Code', 'name' => 'digits_code', 'type' => 'text', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Item Description', 'name' => 'item_description', 'type' => 'text', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'No Of Tokens', 'name' => 'no_of_tokens', 'type' => 'number', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-10'];
		# END FORM DO NOT REMOVE THIS LINE

		$this->sub_module = array();
		$this->addaction = array();
		$this->button_selected = array();
		$this->alert = array();

		$this->index_button = array();
		if (CRUDBooster::getCurrentMethod() == 'getIndex') {
			if (CRUDBooster::isSuperAdmin()) {
				$this->index_button[] = ["label" => "Upload Data", "icon" => "fa fa-upload", "url" => CRUDBooster::mainpath('upload-items'), 'color' => 'primary'];
			}
		}

		$this->table_row_color = array();
		$this->index_statistic = array();
		$this->script_js = NULL;
		$this->pre_index_html = null;
		$this->post_index_html = null;
		$this->load_js = array();

		$this->style_css = '
				.panel-heading{
					background-color:#3c8dbc !important;
					color:#fff !important;
				}
				input[name="submit"]{
					background-color:#3c8dbc !important;
					color:#fff !important;
				}
				@media (min-width:729px){
				.panel-default{
						width:40% !important; 
						margin:auto !important;
				}
			';

		$this->load_css = array();
	}

	public function getItemsCreatedAPI()
	{
		$secretKey = config('api.dimfs_secret_key');

		$uniqueString = time();
		$userAgent = $_SERVER['HTTP_USER_AGENT'] ?: config('api.user_agent');
		$xAuthorizationToken = md5($secretKey . $uniqueString . $userAgent);
		$xAuthorizationTime = $uniqueString;

		$dateFrom = Carbon::now()->format('Y-m-d H:i:s');
		$dateTo = Carbon::now()->format('Y-m-d H:i:s');

		$page = 1;
		$perPage = 1000;

		$response = Http::withHeaders([
			'X-Authorization-Token' => $xAuthorizationToken,
			'X-Authorization-Time' => $xAuthorizationTime,
			'User-Agent' => $userAgent
		])->get(config('api.dimfs_api_created_link'), [
			'page' => $page,
			'limit' => $perPage,
			'datefrom' => $dateFrom,
			'dateto' => $dateTo
		]);

		if ($response->successful()) {
			$data = $response->json();

			if (!empty($data["data"])) {
				$new_items = [];
				foreach ($data["data"] as $value) {
					$new_items[] = [
						'digits_code' => $value['jan_no'] ?? null,
						'digits_code2' => $value['digits_code'] ?? null,
						'item_description' => $value['item_description'] ?? null,
						'no_of_tokens' => $value['no_of_tokens'] ?? null,
						'current_srp' => $value['current_srp'] ?? null
					];
				}

				Item::insert($new_items);
			}
		} else {
			Log::error('Error occurred: ' . $response->status());
		}
	}

	public function getItemsUpdatedAPI()
	{
		$secretKey = config('api.dimfs_secret_key');
		$uniqueString = time();
		$userAgent = $_SERVER['HTTP_USER_AGENT'] ?: config('api.user_agent');
		$xAuthorizationToken = md5($secretKey . $uniqueString . $userAgent);
		$xAuthorizationTime = $uniqueString;

		$dateFrom = Carbon::now()->format('Y-m-d H:i:s');
		$dateTo = Carbon::now()->format('Y-m-d H:i:s');

		$page = 1;
		$perPage = 1000;

		$response = Http::withHeaders([
			'X-Authorization-Token' => $xAuthorizationToken,
			'X-Authorization-Time' => $xAuthorizationTime,
			'User-Agent' => $userAgent
		])->get(config('api.dimfs_api_updated_link'), [
			'page' => $page,
			'limit' => $perPage,
			'datefrom' => $dateFrom,
			'dateto' => $dateTo
		]);

		if ($response->successful()) {
			$data = $response->json();

			if (!empty($data['data'])) {
				foreach ($data['data'] as $value) {
					Item::where('digits_code', $value['digits_code'])->update([
						'item_description' => $value['item_description'] ?? null,
						'no_of_tokens' => $value['no_of_tokens'] ?? null,
						'current_srp' => $value['current_srp'] ?? null
					]);
				}
			}
		} else {
			Log::error('Error occurred: ' . $response->status());
		}
	}

	public function importData()
	{
		$data['page_title'] = 'Upload Data';
		return view('import.items.item-import', $data)->render();
	}

	public function importPostSave(Request $request)
	{
		$path_excel = $request->file('import_file')->store('temp');
		$path = storage_path('app') . '/' . $path_excel;
		Excel::import(new AdminItemsImport, $path);
		CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Upload Successfully!"), 'success');
	}

	function importItemsTemplate()
	{
		$arrHeader = [
			"jan_no"             => "Jan No",
			"digits_code"        => "Digits Code",
			"item_description"   => "Item Description",
			"no_of_tokens"       => "No Of Tokens"
		];

		$arrData = [
			"jan_no"             => "4570118023759",
			"digits_code"        => "60000058",
			"item_description"   => "POCKET MONSTERS ACRYLIC STAND COLLECTN 2",
			"no_of_tokens"       => "3"
		];

		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->fromArray(array_values($arrHeader), null, 'A1');
		$spreadsheet->getActiveSheet()->fromArray($arrData, null, 'A2');
		$filename = "items-" . date('Y-m-d');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
}
