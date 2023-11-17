<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            // CmsMenuPrivilegeSeeder::class,
            // AddOnsActionTypeSeeder::class,
            CmsMenuSeeder::class,
            CmsModulSeeder::class,
            // CmsPrivilegeRoleSeeder::class,
            // CmsPrivilegeSeeder::class,
            // FloatEntrySeeder::class,
            // FloatTypeSeeder::class,
            // LocationsSeeder::class,
            // ModeOfPaymentSeeder::class,
            TokenActionTypeSeeder::class,
            // StatusesSeeder::class,
            // CapsuleActionTypeSeeder::class,
            // CmsPrivilegeSeeder::class,
            // ItemSeeder::class,
            // TokenConversionSeeder::class,
            // CountersSeeder::class,
            // SalesTypeSeeder::class,
            PresetSeeder::class,
        ]);

    }
}
