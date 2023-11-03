<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsModels\CmsPrivileges;
use DB;

class CmsPrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $privileges = [
            [   
                'name'          => 'Super Administrator',
                'is_superadmin' => 1,
                'theme_color'   => 'skin-blue',
                 'created_at' => date('Y-m-d H:i:s')
            ],
            [   
                'name'          => 'Accounting',
                'is_superadmin' => 0,
                'theme_color'   => 'skin-blue',
                 'created_at' => date('Y-m-d H:i:s')
            ],
            [   
                'name'          => 'Cashier',
                'is_superadmin' => 0,
                'theme_color'   => 'skin-blue',
                 'created_at' => date('Y-m-d H:i:s')
            ],
            [   
                'name'          => 'Audit',
                'is_superadmin' => 0,
                'theme_color'   => 'skin-blue',
                 'created_at' => date('Y-m-d H:i:s')
            ],
            [   
                'name'          => 'OIC',
                'is_superadmin' => 0,
                'theme_color'   => 'skin-blue',
                 'created_at' => date('Y-m-d H:i:s')
            ],
            [   
                'name'          => 'Area Manager',
                'is_superadmin' => 0,
                'theme_color'   => 'skin-blue',
                 'created_at' => date('Y-m-d H:i:s')
            ],
            [   
                'name'          => 'Operations Head',
                'is_superadmin' => 0,
                'theme_color'   => 'skin-blue',
                 'created_at' => date('Y-m-d H:i:s')
            ],
            [   
                'name'          => 'Inventory Control',
                'is_superadmin' => 0,
                'theme_color'   => 'skin-blue',
                 'created_at' => date('Y-m-d H:i:s')
            ],
            [   
                'name'          => 'ISD',
                'is_superadmin' => 0,
                'theme_color'   => 'skin-blue',
                 'created_at' => date('Y-m-d H:i:s')
            ],
           
        ];

        foreach ($privileges as $privilege) {
            DB::table('cms_privileges')->updateOrInsert(['name' => $privilege['name']], $privilege);
        }
    }
}
