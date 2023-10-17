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
        DB::table('cms_moduls')->updateOrInsert(
            [
                'name'         => 'Gasha Machine Lists',
            ],
            [
                'name'         => 'Gasha Machine Lists',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'gasha_machines',
                'table_name'   => 'gasha_machines',
                'controller'   => 'Submaster\AdminGashaMachinesController',
                'is_protected' => 0,
                'is_active'    => 1
            ]
        );

        DB::table('cms_moduls')->updateOrInsert(
            [
                'name'         => 'Location',
            ],
            [
                'name'         => 'Location',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'locations',
                'table_name'   => 'locations',
                'controller'   => 'Submaster\AdminLocationsController',
                'is_protected' => 0,
                'is_active'    => 1
            ]
        );

        DB::table('cms_moduls')->updateOrInsert(
            [
                'name'         => 'Float Types',
            ],
            [
                'name'         => 'Float Types',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'float_types',
                'table_name'   => 'float_types',
                'controller'   => 'Submaster\AdminFloatTypesController',
                'is_protected' => 0,
                'is_active'    => 1
            ]
        );

        DB::table('cms_moduls')->updateOrInsert(
            [
                'name'         => 'Float Entries',
            ],
            [
                'name'         => 'Float Entries',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'float_entries',
                'table_name'   => 'float_entries',
                'controller'   => 'Submaster\AdminFloatEntriesController',
                'is_protected' => 0,
                'is_active'    => 1
            ]
        );

        DB::table('cms_moduls')->updateOrInsert(
            [
                'name'         => 'Cash Float History',
            ],
            [
                'name'         => 'Cash Float History',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'cash_float_histories',
                'table_name'   => 'cash_float_histories',
                'controller'   => 'Submaster\AdminCashFloatHistoriesController',
                'is_protected' => 0,
                'is_active'    => 1
            ]
        );

        DB::table('cms_moduls')->updateOrInsert(
            [
                'name'         => 'Pullout Defective Token',
            ],
            [
                'name'         => 'Pullout Defective Token',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'pullout_tokens',
                'table_name'   => 'pullout_tokens',
                'controller'   => 'Token\AdminPulloutTokensController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
        );

        DB::table('cms_moduls')->updateOrInsert(
            [
                'name'         => 'Mode of Payment',
            ],
            [
                'name'         => 'Mode of Payment',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'mode_of_payment',
                'table_name'   => 'mode_of_payment',
                'controller'   => 'Token\AdminModeOfPaymentsController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
        );
    }
}
