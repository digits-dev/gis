<?php

namespace App\Http\Controllers\Submaster;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use DB;
use CRUDBooster;
use Excel;
use App\Imports\GashaMachineImport;
use App\Imports\GashaMachineUpdate;

class AdminImportController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function saveMachines(Request $request) {
        $path_excel = $request->file('import_file')->store('temp');
        $path = storage_path('app').'/'.$path_excel;
        $headings = array_filter((new HeadingRowImport)->toArray($path)[0][0]);

        // if (count($headings) !== 2) {
        //     CRUDBooster::redirect(CRUDBooster::adminpath('gasha_machines'), 'Template column not match, please refer to downloaded template.', 'danger');
        // } else {
            // try {
                if($request->upload_type == "Update"){
                    Excel::import(new GashaMachineUpdate, $path);
                }else{
                    Excel::import(new GashaMachineImport, $path);	
                }
                
                CRUDBooster::redirect(CRUDBooster::adminpath('gasha_machines'), trans("Upload Successfully!"), 'success');

            // } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            //     $failures = $e->failures();

            //     $error = [];
            //     foreach ($failures as $failure) {
            //         $line = $failure->row();
            //         foreach ($failure->errors() as $err) {
            //             $error[] = $err . " on line: " . $line;
            //         }
            //     }

            //     $errors = collect($error)->unique()->toArray();

            // }
            // CRUDBooster::redirect(CRUDBooster::adminpath('gasha_machines'), $errors[0], 'danger');

        // }

    }

    function downloadMachinesTemplate() {
        $arrHeader = [
            "no_of_token"        => "No of token",
            "bay"                => "Bay",
            "layer"              => "Layer"
        ];

        $arrData = [
            "no_of_token"        => "1",
            "bay"                => "Bay 1",
            "layer"              => "Layer 1"
        ];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray(array_values($arrHeader), null, 'A1');
        $spreadsheet->getActiveSheet()->fromArray($arrData, null, 'A2');
        $filename = "gasha-machines-template-".date('Y-m-d');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }


}

?>
