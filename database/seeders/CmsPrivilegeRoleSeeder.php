<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CmsPrivilegeRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //Store Priviles Roles
        //  DB::table('cms_privileges_roles')->insert([
        //     'is_visible'        => 1,
        //     'is_create'         => 1,
        //     'is_create'         => 1,
        //     'is_edit'           => 1,
        //     'is_delete'         => 0,
        //     'id_cms_privileges' => 3,
        //     'id_cms_moduls'     => 27,
        //     'created_at'        => date('Y-m-d H:i:s')
        // ]);
        // DB::table('cms_privileges_roles')->insert([
        //     'is_visible'        => 1,
        //     'is_create'         => 0,
        //     'is_create'         => 1,
        //     'is_edit'           => 1,
        //     'is_delete'         => 0,
        //     'id_cms_privileges' => 3,
        //     'id_cms_moduls'     => 4,
        //     'created_at'        => date('Y-m-d H:i:s')
        // ]);
        // DB::table('cms_privileges_roles')->insert([
        //     'is_visible'        => 1,
        //     'is_create'         => 0,
        //     'is_create'         => 1,
        //     'is_edit'           => 0,
        //     'is_delete'         => 0,
        //     'id_cms_privileges' => 3,
        //     'id_cms_moduls'     => 28,
        //     'created_at'        => date('Y-m-d H:i:s')
        // ]);
    }
}
