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
    public function run() {
        DB::table('cms_menus')->truncate();
        self::indexMenu();
        self::tokenMenu();
        self::capsuleMenu();
        self::auditMenu();
        self::submasterMenu();
    }

    public function indexMenu() {
        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Token',
            ],
            [
                'id'                => 1,
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

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Capsule',
            ],
            [
                'id'                => 2,
                'name'              => 'Capsule',
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

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Audit',
            ],
            [
                'id'                => 3,
                'name'              => 'Audit',
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

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Submaster',
            ],
            [
                'id'                => 4,
                'name'              => 'Submaster',
                'type'              => 'URL',
                'path'              => '#',
                'color'             => 'normal',
                'icon'              => 'fa fa-list',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 4
            ]
        );
    }

    public function tokenMenu() {

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Add Token Balance',
            ],
            [
                'name'              => 'Add Token Balance',
                'type'              => 'Route',
                'path'              => 'Token\AdminReceivingTokensControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 1
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
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 3
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Disburse Token',
            ],
            [
                'name'              => 'Disburse Token',
                'type'              => 'Route',
                'path'              => 'Token\AdminStoreRrTokenControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 4
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Token Inventory	',
            ],
            [
                'name'              => 'Token Inventory	',
                'type'              => 'Route',
                'path'              => 'Token\AdminTokenInventoriesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 7
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Token History',
            ],
            [
                'name'              => 'Token History',
                'type'              => 'Route',
                'path'              => 'Token\AdminTokenHistoriesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 8
            ]
        );
    }

    public function capsuleMenu() {
        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Capsule Inventory',
            ],
            [
                'name'              => 'Capsule Inventory',
                'type'              => 'Route',
                'path'              => 'Capsule\AdminInventoryCapsulesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 3
            ]
        );
    }

    public function auditMenu() {
        return;
    }

    public function submasterMenu() {

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Gasha Machine Lists',
            ],
            [
                'name'              => 'Gasha Machine Lists',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminGashaMachinesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 1
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Location',
            ],
            [
                'name'              => 'Location',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminLocationsControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Mode of Payment',
            ],
            [
                'name'              => 'Mode of Payment',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminModeOfPaymentsControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 4
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Token Conversion',
            ],
            [
                'name'              => 'Token Conversion',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminTokenConversionsControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 5
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Token Conversion History',
            ],
            [
                'name'              => 'Token Conversion History',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminTokenConversionHistoriesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 6
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
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 7
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
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 8
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
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 9
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Token Action Type',
            ],
            [
                'name'              => 'Token Action Type',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminTokenActionTypesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 10
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Statuses',
            ],
            [
                'name'              => 'Statuses',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminStatusesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 11
            ]
        );
    }
}
