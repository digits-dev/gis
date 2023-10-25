<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class TokenConversionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('token_conversions')->truncate();
        DB::table('token_conversion_histories')->truncate();
        DB::table('token_conversions')->insert([
            'cash_value' => 65,
            'current_cash_value' => 65,
            'token_qty' => 1,
            'created_by' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
