<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CmsModulSeeder extends Seeder
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
                'name'         => 'Gasha Machine Lists',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'gasha_machines',
                'table_name'   => 'gasha_machines',
                'controller'   => 'Submaster\AdminGashaMachinesController',
                'is_protected' => 0,
                'is_active'    => 1
            ],
            [
                'name'         => 'Location',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'locations',
                'table_name'   => 'locations',
                'controller'   => 'Submaster\AdminLocationsController',
                'is_protected' => 0,
                'is_active'    => 1
            ],
            [
                'name'         => 'Float Types',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'float_types',
                'table_name'   => 'float_types',
                'controller'   => 'Submaster\AdminFloatTypesController',
                'is_protected' => 0,
                'is_active'    => 1
            ],
            [
                'name'         => 'Float Entries',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'float_entries',
                'table_name'   => 'float_entries',
                'controller'   => 'Submaster\AdminFloatEntriesController',
                'is_protected' => 0,
                'is_active'    => 1
            ],
            [
                'name'         => 'Cash Float History',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'cash_float_histories',
                'table_name'   => 'cash_float_histories',
                'controller'   => 'Submaster\AdminCashFloatHistoriesController',
                'is_protected' => 0,
                'is_active'    => 1
            ],
            [
                'name'         => 'Pullout Defective Token',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'pullout_tokens',
                'table_name'   => 'pullout_tokens',
                'controller'   => 'Token\AdminPulloutTokensController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Mode of Payment',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'mode_of_payment',
                'table_name'   => 'mode_of_payment',
                'controller'   => 'Submaster\AdminModeOfPaymentsController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Add Token Balance',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'receiving_tokens',
                'table_name'   => 'receiving_tokens',
                'controller'   => 'Token\AdminReceivingTokensController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Token Conversion',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'token_conversions',
                'table_name'   => 'token_conversions',
                'controller'   => 'Submaster\AdminTokenConversionsController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Token Action Type',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'token_action_types',
                'table_name'   => 'token_action_types',
                'controller'   => 'Submaster\AdminTokenActionTypesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Token Inventory	',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'token_inventories',
                'table_name'   => 'token_inventories',
                'controller'   => 'Token\AdminTokenInventoriesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Token History',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'token_histories',
                'table_name'   => 'token_histories',
                'controller'   => 'Token\AdminTokenHistoriesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Token Conversion History',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'token_conversion_histories',
                'table_name'   => 'token_conversion_histories',
                'controller'   => 'Submaster\AdminTokenConversionHistoriesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Statuses',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'statuses',
                'table_name'   => 'statuses',
                'controller'   => 'Submaster\AdminStatusesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Disburse Token',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'store_rr_token',
                'table_name'   => 'store_rr_token',
                'controller'   => 'Token\AdminStoreRrTokenController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Capsule Inventory',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'inventory_capsules',
                'table_name'   => 'inventory_capsules',
                'controller'   => 'Capsule\AdminInventoryCapsulesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Capsule Movement History',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'history_capsules',
                'table_name'   => 'history_capsules',
                'controller'   => 'Capsule\AdminHistoryCapsulesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Receive Token',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'receive_token',
                'table_name'   => 'store_rr_token',
                'controller'   => 'Token\AdminReceiveTokenStoreController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Receive Token History',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'receive_token_history',
                'table_name'   => 'store_rr_token',
                'controller'   => 'History\AdminReceiveTokenHistoryController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Capsule Action Type',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'capsule_action_types',
                'table_name'   => 'capsule_action_types',
                'controller'   => 'Submaster\AdminCapsuleActionTypesController',
                'is_protected' => 0,
                'is_active'    => 0

            ],
            [
                'name'         => 'Capsule Refill',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'capsule_refills',
                'table_name'   => 'capsule_refills',
                'controller'   => 'Capsule\AdminCapsuleRefillsController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Sub Location',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'sub_location',
                'table_name'   => 'sub_locations',
                'controller'   => 'Submaster\AdminSubLocationController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Collect Token',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'collect_rr_tokens',
                'table_name'   => 'collect_rr_tokenss',
                'controller'   => 'Audit\AdminCollectRrTokensController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Capsule Return',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'capsule_returns',
                'table_name'   => 'capsule_returns',
                'controller'   => 'Capsule\AdminCapsuleReturnsController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Items',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'items',
                'table_name'   => 'items',
                'controller'   => 'Submaster\AdminItemsController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Counter',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'counters',
                'table_name'   => 'counters',
                'controller'   => 'Submaster\AdminCountersController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Receive Pullout Token',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'received_pullout_tokens',
                'table_name'   => 'pullout_tokens',
                'controller'   => 'Token\AdminReceivedPulloutTokensController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Pullout Token History',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'pullout_tokens_history',
                'table_name'   => 'pullout_tokens',
                'controller'   => 'History\AdminPulloutTokensHistoryController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Receive Collect Token',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'collect_rr_tokens_receiving',
                'table_name'   => 'collect_rr_tokens',
                'controller'   => 'Token\AdminCollectRrTokensReceivingController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Collect Token History',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'collect_rr_tokens_history',
                'table_name'   => 'collect_rr_tokens',
                'controller'   => 'History\AdminCollectRrTokensHistoryController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Capsule Sales',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'capsule_sales',
                'table_name'   => 'capsule_sales',
                'controller'   => 'Capsule\AdminCapsuleSalesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Sales Type',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'sales_types',
                'table_name'   => 'sales_types',
                'controller'   => 'Submaster\AdminSalesTypesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Cycle Count (Capsule',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'cycle_counts',
                'table_name'   => 'cycle_counts',
                'controller'   => 'Audit\AdminCycleCountsController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Token Sales',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'collect_rr_token_sales',
                'table_name'   => 'collect_rr_token_lines',
                'controller'   => 'Token\AdminCollectRrTokenSalesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Token Swap History',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'swap_histories',
                'table_name'   => 'swap_histories',
                'controller'   => 'History\AdminSwapHistoriesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Presets',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'presets',
                'table_name'   => 'presets',
                'controller'   => 'Submaster\AdminPresetsController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Adjust Token Balance',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'token_adjustments',
                'table_name'   => 'token_adjustments',
                'controller'   => 'Token\AdminTokenAdjustmentsController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Add Ons',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'add_ons',
                'table_name'   => 'add_ons	',
                'controller'   => 'Submaster\AdminAddOnsController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Add Ons Action Type',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'add_on_action_types',
                'table_name'   => 'add_on_action_types',
                'controller'   => 'Submaster\AdminAddOnActionTypesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Add Ons Movement History',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'add_on_movement_histories',
                'table_name'   => 'add_on_movement_histories',
                'controller'   => 'History\AdminAddOnMovementHistoriesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Capsule Merge',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'capsule_merges',
                'table_name'   => 'capsule_merges',
                'controller'   => 'Capsule\AdminCapsuleMergesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Capsule Swap',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'capsule_swap_headers',
                'table_name'   => 'capsule_swap_headers',
                'controller'   => 'Capsule\AdminCapsuleSwapHeadersController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Capsule Split',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'capsule_split',
                'table_name'   => 'capsule_split',
                'controller'   => 'Capsule\AdminCapsuleSplitController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Cycle Count Approval',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'cycle_count_approval',
                'table_name'   => 'cycle_count_lines',
                'controller'   => 'Audit\AdminCycleCountApprovalController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Collect Token Approval',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'collect_token_approval',
                'table_name'   => 'collect_rr_token_lines',
                'controller'   => 'Audit\AdminCollectTokenApprovalController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Adjust Capsule Balance',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'capsule_adjustments',
                'table_name'   => 'capsule_adjustments',
                'controller'   => 'Capsule\AdminCapsuleAdjustmentsController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
        ];

        foreach ($modules as $module) {
            DB::table('cms_moduls')->updateOrInsert(['name' => $module['name']], $module);
        }

    }
}