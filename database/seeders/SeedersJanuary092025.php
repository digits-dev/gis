<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SeedersJanuary092025 extends Seeder
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
                'name'         => 'New Collect Token History',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'collect_token_histories',
                'table_name'   => 'collect_token_histories',
                'controller'   => 'History\AdminCollectTokenHistoriesController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
         
        ];

        foreach ($modules as $module) {
            DB::table('cms_moduls')->updateOrInsert(['name' => $module['name']], $module);
        }

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'New Collect Token History',
            ],
            [
                'name'              => 'New Collect Token History',
                'type'              => 'Route',
                'path'              => 'History\AdminCollectRrTokensHistoryControllerGetIndex',
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