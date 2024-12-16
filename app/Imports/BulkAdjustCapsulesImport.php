<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;
use CRUDBooster;
use App\Models\Capsule\CapsuleRefill;
use App\Models\Capsule\HistoryCapsule;
use App\Models\Capsule\InventoryCapsule;
use App\Models\Capsule\InventoryCapsuleLine;
use App\Models\Submaster\Counter;
use App\Models\Submaster\CapsuleActionType;

class BulkAdjustCapsulesImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows){
        foreach ($rows->toArray() as $key => $row){
            $location = DB::table('locations')->where(DB::raw('LOWER(TRIM(REPLACE(`location_name`," ","")))'), strtolower(str_replace(' ', '', trim($row['location']))))->first();
            $machine  = DB::table('gasha_machines')->where(DB::raw('LOWER(TRIM(REPLACE(`serial_number`," ","")))'), strtolower(str_replace(' ', '', trim($row['machine_serial']))))->first();
            $item     = DB::table('items')->where('digits_code',$row['jan_no'])->first();
            $time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
            $qty = preg_replace('/,/', '', $row['qty']);
			$action_type = 6;
	
            InventoryCapsule::leftjoin('inventory_capsule_lines','inventory_capsule_lines.inventory_capsules_id','inventory_capsules.id')
				->where('inventory_capsules.item_code', $item->digits_code2)
                ->where('inventory_capsule_lines.gasha_machines_id', $machine->id)
                ->where('inventory_capsules.locations_id', $location->id)
                ->update([
                'qty' => DB::raw("qty + $qty"),
                'inventory_capsule_lines.updated_at' => $time_stamp,
                'inventory_capsule_lines.updated_by' => $action_by,
            ]);
				
			HistoryCapsule::insert([
				'reference_number' => null,
				'item_code' => $item->digits_code2,
				'capsule_action_types_id' => $action_type,
				'locations_id' => $location->id,
				'to_machines_id' => $machine->id,
				'to_sub_locations_id' => null,
				'qty' => $qty,
				'created_at' => $time_stamp,
				'created_by' => $action_by
			]);
        }
    }

    public function prepareForValidation($data, $index)
    {
        //Jan No
        $data['jan_no_exist']['check'] = false;
        $checkRowDbJanNo = DB::table('items')->select("digits_code AS jan_code")->get()->toArray();
        $checkRowDbJanNoColumn = array_column($checkRowDbJanNo, 'jan_code');

        if(!empty($data['jan_no'])){
            if(in_array(strtolower(str_replace(' ', '', trim($data['jan_no']))), $checkRowDbJanNoColumn)){
                $data['jan_no_exist']['check'] = true;
            }
        }else{
            $data['jan_no_exist']['check'] = true;
        }

        //Machine
        $data['machine_exist']['check'] = false;
        $checkRowDbMachine = DB::table('gasha_machines')->select(DB::raw("serial_number AS serial_number"))->get()->toArray();
        $checkRowDbMachineColumn = array_column($checkRowDbMachine, 'serial_number');

        if(!empty($data['machine_serial'])){
            if(in_array(str_replace(' ', '', trim($data['machine_serial'])), $checkRowDbMachineColumn)){
                $data['machine_exist']['check'] = true;
            }
        }else{
            $data['machine_exist']['check'] = true;
        }
        
        return $data;
    }

    public function rules(): array
    {
        return [
            '*.jan_no_exist' => function($attribute, $value, $onFailure) {
                if ($value['check'] === false) {
                    $onFailure('Invalid Jan No! Please refer to valid Jan No in system');
                }
            },
            '*.machine_exist' => function($attribute, $value, $onFailure) {
                if ($value['check'] === false) {
                    $onFailure('Machine not found!');
                }
            },
        ];
    }

}
