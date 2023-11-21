<?php

namespace Database\Seeders;

use App\Models\Submaster\SalesType;
use Illuminate\Database\Seeder;

class SalesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SalesType::updateOrInsert(['description' => 'RETURN'],['description' => 'RETURN', 'created_at'=>date('Y-m-d H:i:s')]);
        SalesType::updateOrInsert(['description' => 'CYCLEOUT'],['description' => 'CYCLEOUT', 'created_at'=>date('Y-m-d H:i:s')]);
        SalesType::updateOrInsert(['description' => 'MERGE'],['description' => 'MERGE   ', 'created_at'=>date('Y-m-d H:i:s')]);
    }
}
