<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CmsModulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cms_moduls')->insert([
            [
                'name'         => 'Gasha Machine Lists',
                'icon'         => 'fa fa-list',
                'path'         => 'gasha_machines',
                'table_name'   => 'gasha_machines',
                'controller'   => 'Submaster\AdminGashaMachineListsController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
          ]);
    }
}
