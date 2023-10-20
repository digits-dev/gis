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
                'theme_color'   => 'skin-red',
                 'created_at' => date('Y-m-d H:i:s')
            ],
            [   
                'name'          => 'Accounting',
                'is_superadmin' => 0,
                'theme_color'   => 'skin-red',
                 'created_at' => date('Y-m-d H:i:s')
            ],
            [   
                'name'          => 'Store',
                'is_superadmin' => 0,
                'theme_color'   => 'skin-red',
                 'created_at' => date('Y-m-d H:i:s')
            ],
            [   
                'name'          => 'Audit',
                'is_superadmin' => 0,
                'theme_color'   => 'skin-red',
                 'created_at' => date('Y-m-d H:i:s')
            ],
           
        ];

        foreach ($privileges as $privilege) {
            DB::table('cms_privileges')->updateOrInsert(['name' => $privilege['name']], $privilege);
        }
    }
}
