<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SeedersMarch072025 extends Seeder
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
                'name'         => 'New Cash Float History',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'new_cash_float_histories',
                'table_name'   => 'cash_float_histories',
                'controller'   => 'History\AdminNewCashFloatHistoriesController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
         
        ];

        foreach ($modules as $module) {
            DB::table('cms_moduls')->updateOrInsert(['name' => $module['name']], $module);
        }

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'New Cash Float History',
            ],
            [
                'name'              => 'New Cash Float History',
                'type'              => 'Route',
                'path'              => 'History\AdminNewCashFloatHistoriesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 0
            ]
        );

       
    }
}