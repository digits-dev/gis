<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CmsMenuPrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $last_menu_id = 13;

        for ($i=1; $i<=$last_menu_id; $i++) {
            DB::table('cms_menus_privileges')->updateOrInsert(['id_cms_menus' => $i], [
                'id_cms_menus' => $i,
                'id_cms_privileges' => 1
            ]);
        }
    }
}
