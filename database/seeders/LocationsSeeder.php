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
            'location_name' => 'DIGITSHEADOFFICE',
            ],
            [
                'location_name' => 'DIGITSHEADOFFICE',
                'status' => 'ACTIVE'
            ]);

        DB::table('locations')->updateOrInsert([
            'location_name' => 'GASHAPON.MITSUKOSHIBGC.RTL',
            ],
            [
                'location_name' => 'GASHAPON.MITSUKOSHIBGC.RTL',
                'status' => 'ACTIVE'
            ]);
    }
}
