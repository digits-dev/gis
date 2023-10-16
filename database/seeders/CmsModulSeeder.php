<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CmsModulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cms_moduls')->updateOrInsert(
            [
                'name'         => 'Gasha Machine Lists',
            ],
            [
                'name'         => 'Gasha Machine Lists',
                'icon'         => 'fa fa-list',
                'path'         => 'gasha_machines',
                'table_name'   => 'gasha_machines',
                'controller'   => 'Submaster\AdminGashaMachinesController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
        );

        DB::table('cms_moduls')->updateOrInsert(
            [
                'name'         => 'Location',
            ],
            [
                'name'         => 'Location',
                'icon'         => 'fa fa-list',
                'path'         => 'locations',
                'table_name'   => 'locations',
                'controller'   => 'Submaster\AdminLocationsController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
        );

                DB::table('cms_moduls')->updateOrInsert(
            [
                'name'         => 'Pullout Defective Token',
            ],
            [
                'name'         => 'Pullout Defective Token',
                'icon'         => 'fa fa-circle',
                'path'         => 'pullout_tokens',
                'table_name'   => 'pullout_tokens',
                'controller'   => 'Token\AdminPulloutTokensController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
        );
    }
}
