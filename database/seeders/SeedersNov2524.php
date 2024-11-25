<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SeedersNov2524 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('cms_moduls')->where('id', '>=', 12)->delete();
        // DB::statement('ALTER TABLE cms_moduls AUTO_INCREMENT = 12');
        $modules = [
            [
                'name'         => 'New Collect Token',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'collect_token',
                'table_name'   => 'collect_rr_tokens',
                'controller'   => 'Token\AdminCollectTokenController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Gasha Machines Bay',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'gasha_machines_bay',
                'table_name'   => 'gasha_machines_bay',
                'controller'   => 'Submaster\AdminGashaMachinesBayController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Gasha Machine Layers',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'gasha_machines_layers',
                'table_name'   => 'gasha_machines_layers',
                'controller'   => 'Submaster\AdminGashaMachinesLayersController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
        ];

        foreach ($modules as $module) {
            DB::table('cms_moduls')->updateOrInsert(['name' => $module['name']], $module);
        }

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'New Collect Token',
            ],
            [
                'name'              => 'New Collect Token',
                'type'              => 'Route',
                'path'              => 'Token\AdminCollectTokenControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 1
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Gasha Machines Bay',
            ],
            [
                'name'              => 'Gasha Machines Bay',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminGashaMachinesBayControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 17
            ]
        );
        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Gasha Machine Layers',
            ],
            [
                'name'              => 'Gasha Machine Layers',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminGashaMachinesLayersControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 18
            ]
        );
    }
}