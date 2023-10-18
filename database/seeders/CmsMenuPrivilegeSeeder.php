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
        $last_menu_id = 50;

        for ($i=1; $i<=$last_menu_id; $i++) {
            DB::table('cms_menus_privileges')->updateOrInsert(['id_cms_menus' => $i], [
                'id_cms_menus' => $i,
                'id_cms_privileges' => 1
            ]);
        }

        // //Store Menus Priviles
        // DB::table('cms_menus_privileges')->insert([
        //     'id_cms_menus' => 1,
        //     'id_cms_privileges' => 3
        // ]);
        // DB::table('cms_menus_privileges')->insert([
        //     'id_cms_menus' => 9,
        //     'id_cms_privileges' => 3
        // ]);

        // //Histories
        // DB::table('cms_menus_privileges')->insert([
        //     'id_cms_menus' => 5,
        //     'id_cms_privileges' => 3
        // ]);
        // DB::table('cms_menus_privileges')->insert([
        //     'id_cms_menus' => 22,
        //     'id_cms_privileges' => 3
        // ]);
    }
}
