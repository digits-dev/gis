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
        self::historyMenu();
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

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'History',
            ],
            [
                'id'                => 5,
                'name'              => 'History',
                'type'              => 'URL',
                'path'              => '#',
                'color'             => 'normal',
                'icon'              => 'fa fa-list',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 5
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
                'sorting'           => 2
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Receive Pullout Token',
            ],
            [
                'name'              => 'Receive Pullout Token',
                'type'              => 'Route',
                'path'              => 'Token\AdminReceivedPulloutTokensControllerGetIndex',
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
                'name'              => 'Receive Token',
            ],
            [
                'name'              => 'Receive Token',
                'type'              => 'Route',
                'path'              => 'Token\AdminReceiveTokenStoreControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 5
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Receive Collect Token',
            ],
            [
                'name'              => 'Receive Collect Token',
                'type'              => 'Route',
                'path'              => 'Token\AdminCollectRrTokensReceivingControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 6
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
    }

    public function capsuleMenu() {

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Capsule Refill',
            ],
            [
                'name'              => 'Capsule Refill',
                'type'              => 'Route',
                'path'              => 'Capsule\AdminCapsuleRefillsControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 1
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Capsule Returns',
            ],
            [
                'name'              => 'Capsule Returns',
                'type'              => 'Route',
                'path'              => 'Capsule\AdminCapsuleReturnsControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );

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

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Capsule Sales',
            ],
            [
                'name'              => 'Capsule Sales',
                'type'              => 'Route',
                'path'              => 'Capsule\AdminCapsuleSalesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 4
            ]
        );


    }

    public function auditMenu() {
        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Collect Token',
            ],
            [
                'name'              => 'Collect Token',
                'type'              => 'Route',
                'path'              => 'Audit\AdminCollectRrTokensControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 3,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 1
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Cycle Count (Capsule)',
            ],
            [
                'name'              => 'Cycle Count (Capsule)',
                'type'              => 'Route',
                'path'              => 'Audit\AdminCycleCountsControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 3,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );
    }

    public function submasterMenu() {

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Counter',
            ],
            [
                'name'              => 'Counter',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminCountersControllerGetIndex',
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
                'sorting'           => 2
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
                'sorting'           => 3
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Items',
            ],
            [
                'name'              => 'Items',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminItemsControllerGetIndex',
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
                'sorting'           => 5
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
                'sorting'           => 9
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
                'sorting'           => 10
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Capsule Action Type',
            ],
            [
                'name'              => 'Capsule Action Type',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminCapsuleActionTypesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 11
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Sub Location',
            ],
            [
                'name'              => 'Sub Location',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminSubLocationControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 12
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Sales Types',
            ],
            [
                'name'              => 'Sales Types	',
                'type'              => 'Route',
                'path'              => 'Submaster\AdminSalesTypesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 13
            ]
        );
    }

    public function historyMenu() {
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
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 1
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Receive Token History',
            ],
            [
                'name'              => 'Receive Token History',
                'type'              => 'Route',
                'path'              => 'History\AdminReceiveTokenHistoryControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Collect Token History',
            ],
            [
                'name'              => 'Collect Token History',
                'type'              => 'Route',
                'path'              => 'History\AdminCollectRrTokensHistoryControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 3
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
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 4
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Capsule History',
            ],
            [
                'name'              => 'Capsule History',
                'type'              => 'Route',
                'path'              => 'Capsule\AdminHistoryCapsulesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 5
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
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 6
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Pullout Token History',
            ],
            [
                'name'              => 'Pullout Token History',
                'type'              => 'Route',
                'path'              => 'History\AdminPulloutTokensHistoryControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 7
            ]
        );
    }
}
