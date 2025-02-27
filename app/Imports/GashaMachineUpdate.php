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
use App\Models\Submaster\GashaMachinesBay;
use App\Models\GashaMachinesLayer;
class GashaMachineUpdate implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows){
        foreach ($rows->toArray() as $key => $row) {
            $location_name = DB::table('locations')->where('location_name', trim($row['location']))->first();
        
            // Only query for bay and layer if they are provided in the row
            $bay = !empty(trim($row['bay'] ?? '')) ? GashaMachinesBay::where('name', trim($row['bay']))->first() : null;
            $layer = !empty(trim($row['layer'] ?? '')) ? GashaMachinesLayer::where('name', trim($row['layer']))->first() : null;
            $token = isset($row['no_of_token']) && trim($row['no_of_token']) !== '' ? trim($row['no_of_token']) : null;
        
            if ($location_name == NULL) {
                return CRUDBooster::redirect(CRUDBooster::adminpath('gasha_machines'), "Location not exist! at line " . ($key + 2), "danger");
            }
        
            // Build the update data array
            $updateData = [
                'updated_by'  => CRUDBooster::myId(),
                'updated_at'  => date('Y-m-d H:i:s'),
                'location_name' => $location_name->location_name,
                'location_id' => $location_name->id

            ];
        
            // Add bay and layer to the update array only if they are present
            if ($bay !== null) {
                $updateData['bay'] = $bay->id;
            }
            if ($layer !== null) {
                $updateData['layer'] = $layer->id;
            }
        
            // Add no_of_token to the update array only if it's not null
            if ($token !== null) {
                $updateData['no_of_token'] = $token;
            }
        
            GashaMachines::where('serial_number', $row['serial_number'])->update($updateData);
        }        
    
    }
}
