<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->updateOrInsert([
            'location_name' => 'Digits Head Office',
            ],
            [
                'location_name' => 'Digits Head Office',
                'status' => 'Active'
            ]);

        DB::table('locations')->updateOrInsert([
            'location_name' => 'Mitsukoshi',
            ],
            [
                'location_name' => 'Mitsukoshi',
                'status' => 'Active'
            ]);
    }
}
