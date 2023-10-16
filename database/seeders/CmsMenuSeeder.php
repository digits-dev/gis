<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CmsMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //SUB MASTER DROPDOWN
        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Submaster',
            ],
            [
                'name'              => 'Submaster',
                'type'              => 'URL',
                'path'              => 'submaster',
                'color'             => 'normal',
                'icon'              => 'fa fa-list',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 1
            ]
        );

        //SUB MASTER Gasha Machine List
        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Gasha Machine Lists',
            ],
            [
                'name'              => 'Gasha Machine Lists',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminGashaMachineListsControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-list',
                'parent_id'         => 3,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 1
            ]
        );

        //SUB MASTER Location
        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Location',
            ],
            [
                'name'              => 'Location',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminLocationsControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-list',
                'parent_id'         => 3,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );
    }
}
