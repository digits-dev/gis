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
        
        $arr_num = [1,2,3,4,5,6,7,8];

        foreach($arr_num as $num){
            DB::table('cms_menus_privileges')->updateOrInsert(['id_cms_menus' => $num], [
                'id_cms_menus' => $num,
                'id_cms_privileges' => 1
            ]);
        }
    }
}
