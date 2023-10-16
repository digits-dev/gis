<?php

namespace Database\Seeders;

use App\Models\Submaster\FloatType;
use Illuminate\Database\Seeder;

class FloatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FloatType::updateOrInsert(['description' => 'START'],['description' => 'START', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatType::updateOrInsert(['description' => 'END'],['description' => 'END', 'created_at'=>date('Y-m-d H:i:s')]);
    }
}
