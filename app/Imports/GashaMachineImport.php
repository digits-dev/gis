<?php

namespace App\Imports;

use App\Models\AssetsSuppliesInventory;
use Illuminate\Support\Facades\Hash;
//use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;
use CRUDBooster;
use App\Models\Submaster\GashaMachines;
use App\Models\Submaster\Counter;
class GashaMachineImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows){
        foreach ($rows->toArray() as $key => $row){
            $location_name      = DB::table('locations')->where('id',CRUDBooster::myLocationId())->first();
            if($row['no_of_token'] == '' || $row['no_of_token'] == NULL){
                return CRUDBooster::redirect(CRUDBooster::adminpath('gasha_machines'),"Token required Or greater than zero at line ".($key+2),"danger");
            }
            if($row['no_of_token'] == 0){
                return CRUDBooster::redirect(CRUDBooster::adminpath('gasha_machines'),"Token required Or greater than zero at line ".($key+2),"danger");
            }
            if($row['no_of_token'] > 9){
                return CRUDBooster::redirect(CRUDBooster::adminpath('gasha_machines'),"Token must be equal or less than 9! at line ".($key+2),"danger");
            }
            if($location_name == NULL){
                return CRUDBooster::redirect(CRUDBooster::adminpath('gasha_machines'),"No location tag! at line ".($key+2),"danger");
            }
            GashaMachines::Create(
                [
                    'serial_number'         => Counter::getNextMachineReference(CRUDBooster::getCurrentModule()->id),
                    'location_id'           => CRUDBooster::myLocationId(),
			        'location_name'         => $location_name->location_name,
                    'no_of_token'           => $row['no_of_token'],
                    'bay'                   => $row['bay'],
                    'layer'                 => $row['layer'],
                    'machine_statuses_id'   => 1,
			        'status'                => 'ACTIVE',
                    'created_by'            => CRUDBooster::myId(),
                    'created_at'            => date('Y-m-d H:i:s')
                ]
            );


        }
    }
}
