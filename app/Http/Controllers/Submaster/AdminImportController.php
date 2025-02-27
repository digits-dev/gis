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
        // Define headers and data for the first sheet
        $arrHeader1 = [
            "no_of_token" => "No of token",
            "bay"         => "Bay",
            "layer"       => "Layer",
            "location"       => "Location"
        ];
    
        $arrData1 = [
            "no_of_token" => "1",
            "bay"         => "Bay 1",
            "layer"       => "Layer 1",
            'location'       => "sample location"
        ];
    
        // Define headers and data for the second sheet
        $arrHeader2 = [
            "serial_number"  => "Serial Number",
            "no_of_token"    => "No of token",
            "bay"            => "Bay",
            "layer"          => "Layer",
            "location"       => "Location"
        ];
    
        $arrData2 = [
            "serial_number"  => "PH00001",
            "no_of_token"    => "3",
            "bay"            => "Bay A",
            "layer"          => "layer 1",
            'location'       => "sample location"
        ];
    
        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
    
        // First sheet: set headers and data
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('Upload Template');
        $spreadsheet->getActiveSheet()->fromArray(array_values($arrHeader1), null, 'A1');
        $spreadsheet->getActiveSheet()->fromArray($arrData1, null, 'A2');
    
        // Create second sheet, set headers and data
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Update Template');
        $sheet2->fromArray(array_values($arrHeader2), null, 'A1');
        $sheet2->fromArray($arrData2, null, 'A2');
    
        // Set the filename and headers for download
        $filename = "gasha-machines-template-" . date('Y-m-d');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
    
        // Save the file to output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    


}

?>
