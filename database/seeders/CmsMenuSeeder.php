<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CmsMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cms_menus')->truncate();

        //SUB MASTER DROPDOWN
        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Submaster',
            ],
            [
                'name'              => 'Submaster',
                'type'              => 'URL',
                'path'              => '#',
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
                'path'              => 'Submaster\AdminGashaMachinesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-list',
                'parent_id'         => 1,
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
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Float Types',
            ],
            [
                'name'              => 'Float Types',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminFloatTypesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-list',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Float Entries',
            ],
            [
                'name'              => 'Float Entries',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminFloatEntriesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-list',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Cash Float History',
            ],
            [
                'name'              => 'Cash Float History',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminCashFloatHistoriesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-list',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Pullout Defective Token',
            ],
            [
                'name'              => 'Pullout Defective Token',
                'type'              => 'Route',
                'path'              => 'Token\AdminPulloutTokensControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle',
                'parent_id'         => 8,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );

        //SUB MASTER DROPDOWN
        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Token',
            ],
            [
                'name'              => 'Token',
                'type'              => 'URL',
                'path'              => '#',
                'color'             => 'normal',
                'icon'              => 'fa fa-list',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 1
            ]
        );


    }
}
