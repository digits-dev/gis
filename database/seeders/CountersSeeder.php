<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submaster\Counter;
use CRUDBooster;

class CountersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $counters = [
            [   
                'cms_moduls_id'    => '12',
                'reference_code'   => 'GM',
                'reference_number' => '1',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
            [   
                'cms_moduls_id'    => '26',
                'reference_code'   => 'DB',
                'reference_number' => '10000001',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
            [   
                'cms_moduls_id'    => '17',
                'reference_code'   => 'PT',
                'reference_number' => '1',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
            [   
                'cms_moduls_id'    => '33',
                'reference_code'   => 'CR',
                'reference_number' => '1',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
        ];

        foreach ($counters as $counter) {
            Counter::updateOrInsert(['reference_code' => $counter['reference_code']], $counter);
        }
    }
}
