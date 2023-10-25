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
        FloatType::truncate();
        FloatType::updateOrInsert(['description' => 'START'],['id' => 1, 'description' => 'START', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatType::updateOrInsert(['description' => 'END'],['id' => 2, 'description' => 'END', 'created_at'=>date('Y-m-d H:i:s')]);
    }
}
