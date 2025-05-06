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

       
    }
}