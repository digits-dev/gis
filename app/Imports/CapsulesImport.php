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

class CapsulesImport implements ToCollection, WithHeadingRow, WithValidation
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
            // checking if item and machine has the same no. of tokens
			$is_tally = $item->no_of_tokens == $machine->no_of_token;
            // dd($row);
            //getting the locations_id from where the scanned machine was deployed
			$locations_id = $machine->location_id;

            // getting the current inventory for this item_code and this location
			$current_inventory = InventoryCapsule::where([
				'item_code' => $item->digits_code2,
				'locations_id' => $locations_id
			])->leftJoin(
				'inventory_capsule_view',
				'inventory_capsule_view.inventory_capsules_id',
				'inventory_capsules.id'
			)->first();
            // returning if no. of tokens does not match
			// if (!$is_tally) {
			// 	return CRUDBooster::redirect(CRUDBooster::mainpath(),"Machine No of token not equal to Item No of tokens".($key+2),"danger");
			// // returning if there is no current inventory
			// } else 
            if (!$current_inventory) {
                return CRUDBooster::redirect(CRUDBooster::mainpath(),"No Inventory! at Line: ".($key+2),"danger");
			// returning if the inputted qty is greater than the stockroom qty
			} else if ($current_inventory->stockroom_capsule_qty < $qty) {
                return CRUDBooster::redirect(CRUDBooster::mainpath(),"Inputed Qty is greater than stock room qty! at Line: ".($key+2),"danger");
			}

            // getting the 'refill' action type
			$action_type = CapsuleActionType::where(DB::raw('UPPER(description)'), '=', 'REFILL')->first();
			// generating a new reference number
			$reference_number = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
			// inserting capsule refill entry
			$capsule = CapsuleRefill::insert([
				'reference_number' => $reference_number,
				'item_code' => $row['jan_no'],
				'gasha_machines_id' => $machine->id,
				'created_at' => $time_stamp,
				'created_by' => $action_by,
				'qty' => $qty,
				'locations_id' => $locations_id,
			]);

            // creating history for the transaction
			HistoryCapsule::insert([
				'reference_number' => $reference_number,
				'item_code' => $row['jan_no'],
				'capsule_action_types_id' => $action_type->id,
				'locations_id' => $locations_id,
				'gasha_machines_id' => $machine->id,
				'qty' => $qty,
				'created_at' => $time_stamp,
				'created_by' => $action_by
			]);

            $inventory_capsules_id = $current_inventory->inventory_capsules_id;

			// getting the machines inventory
			$current_inventory_line = InventoryCapsuleLine::where([
				'inventory_capsules_id' => $inventory_capsules_id,
				'gasha_machines_id' => $machine->id,
			]);

			// checking if there is current inventory
			if (!$current_inventory_line->first()) {
				// inserting a new one if not existing
				$inventory_line_id = $current_inventory_line->insertGetId([
					'inventory_capsules_id' => $inventory_capsules_id,
					'gasha_machines_id' => $machine->id,
					'qty' => $qty,
					'created_by' => $action_by,
					'created_at' => $time_stamp,
				]);
			} else {
				// updating if already exists
				$current_inventory_line->update([
					'qty' => DB::raw("qty + $qty"),
					'updated_at' => $time_stamp,
					'updated_by' => $action_by,
				]);
			}

			// updating the quantity of stock room inventory
			DB::table('inventory_capsule_lines')->whereNotNull('sub_locations_id')
				->leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
				->leftJoin('sub_locations', 'sub_locations.id', 'inventory_capsule_lines.sub_locations_id')
				->where('sub_locations.location_id', $locations_id)
				->where('inventory_capsules.item_code', $item->digits_code2)
				->update([
					'inventory_capsule_lines.updated_by' => $action_by,
					'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty - $qty")
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
            // '*.machine_exist' => function($attribute, $value, $onFailure) {
            //     if ($value['check'] === false) {
            //         $onFailure('Machine not found!');
            //     }
            // },
        ];
    }

}
