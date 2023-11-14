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
use App\Models\Audit\CollectRrTokenLines;

class UpdateNoOfTokenImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows){
        foreach ($rows->toArray() as $key => $row){
            $machine = DB::table('gasha_machines')->where('id',$row['gasha_machines_id'])->first();
            CollectRrTokenLines::where('gasha_machines_id',$row['gasha_machines_id'])
            ->update([
                    'no_of_token'    => $machine->no_of_token
            ]);	
        }
    }
}
