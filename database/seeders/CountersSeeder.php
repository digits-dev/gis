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
                'reference_code'   => 'PH',
                'reference_number' => '1',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
            [   
                'cms_moduls_id'    => '26',
                'reference_code'   => 'DBT',
                'reference_number' => '1',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
            [   
                'cms_moduls_id'    => '17',
                'reference_code'   => 'PLT',
                'reference_number' => '1',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
            [   
                'cms_moduls_id'    => '32',
                'reference_code'   => 'CRF',
                'reference_number' => '1',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
            [   
                'cms_moduls_id'    => '19',
                'reference_code'   => 'ATB',
                'reference_number' => '1',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
            [   
                'cms_moduls_id'    => '34',
                'reference_code'   => 'CLT',
                'reference_number' => '1',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
            [   
                'cms_moduls_id'    => '35',
                'reference_code'   => 'CRT',
                'reference_number' => '1',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
            [   
                'cms_moduls_id'    => '42',
                'reference_code'   => 'CPS',
                'reference_number' => '1',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
            [   
                'cms_moduls_id'    => '48',
                'reference_code'   => 'AJT',
                'reference_number' => '1',
                'status'           => 'ACTIVE',
                'created_by'       => CRUDBooster::myId(),
                'created_at'       => date('Y-m-d H:i:s')
            ],
        ];

        Counter::truncate();

        foreach ($counters as $counter) {
            Counter::updateOrInsert(['reference_code' => $counter['reference_code']], $counter);
        }
    }
}
