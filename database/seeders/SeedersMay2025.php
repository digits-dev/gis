<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SeedersMay2025 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            [
                'name'         => 'Item POS',
                'icon'         => 'fa fa-briefcase',
                'path'         => 'item_pos_transactions_backend',
                'table_name'   => 'item_pos_transactions_backend',
                'controller'   => 'ItemPos\AdminItemPosTransactionsBackendController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Items Category',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'items_categories',
                'table_name'   => 'items_categories',
                'controller'   => 'Submaster\AdminItemsCategoriesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Items Product Type',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'items_product_types',
                'table_name'   => 'items_product_types',
                'controller'   => 'Submaster\AdminItemsProductTypesController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
         
        ];

        foreach ($modules as $module) {
            DB::table('cms_moduls')->updateOrInsert(['name' => $module['name']], $module);
        }

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Item POS',
            ],
            [
                'name'              => 'Item POS',
                'type'              => 'Route',
                'path'              => 'ItemPos\AdminItemPosTransactionsBackendControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-briefcase',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 11
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Items Category',
            ],
            [
                'name'              => 'Items Category',
                'type'              => 'Route',
                'path'              => 'AdminItemsCategoriesControllerGetIndex',
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
                'name'              => 'Items Product Type',
            ],
            [
                'name'              => 'Items Product Type',
                'type'              => 'Route',
                'path'              => 'AdminItemsProductTypesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );

       
    }
}