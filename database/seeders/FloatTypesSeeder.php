<?php

namespace Database\Seeders;

use App\Models\Submaster\FloatTypes;
use Illuminate\Database\Seeder;

class FloatTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FloatTypes::updateOrInsert(['description' => 'START'],['description' => 'START', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatTypes::updateOrInsert(['description' => 'END'],['description' => 'END', 'created_at'=>date('Y-m-d H:i:s')]);
    }
}
