<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SeedersFeb072025 extends Seeder
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
                'name'         => 'SOD vs EOD',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'sod_vs_eod_report',
                'table_name'   => 'cash_float_histories',
                'controller'   => 'Token\AdminSodVsEodReportController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'SOD vs Beg. Balance',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'sod_vs_beginning_bal',
                'table_name'   => 'cash_float_histories',
                'controller'   => 'Token\AdminSodVsBeginningBalController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'On Hand vs Beg. Balance',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'token_on_hand_vs_beginning_bal_report',
                'table_name'   => 'token_on_hand_vs_beginning_bal_report',
                'controller'   => 'Token\AdminTokenOnHandVsBeginningBalReportController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
        ];

        foreach ($modules as $module) {
            DB::table('cms_moduls')->updateOrInsert(['name' => $module['name']], $module);
        }

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Token Variance Reports',
            ],
            [
                'name'              => 'Token Variance Reports',
                'type'              => 'URL',
                'path'              => '#',
                'color'             => 'normal',
                'icon'              => 'fa fa-file-text-o',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 9
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'SOD vs EOD',
            ],
            [
                'name'              => 'SOD vs EOD',
                'type'              => 'Route',
                'path'              => 'Token\AdminSodVsEodReportControllerGetIndex',
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
                'name'              => 'SOD vs Beg. Balance',
            ],
            [
                'name'              => 'SOD vs Beg. Balance',
                'type'              => 'Route',
                'path'              => 'Token\AdminSodVsBeginningBalControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'On Hand vs Beg. Balance',
            ],
            [
                'name'              => 'On Hand vs Beg. Balance',
                'type'              => 'Route',
                'path'              => 'Token\AdminTokenOnHandVsBeginningBalReportControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 3
            ]
        );
       
    }
}