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
        DB::table('locations')->insert([
            [
                'location_name' => 'Digits Head Office',
                'status' => 'Active'
            ],
            [
                'location_name' => 'Mitsukoshi',
                'status' => 'Active'
            ]
          ]);
    }
}
