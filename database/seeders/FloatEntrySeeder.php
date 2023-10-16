<?php

namespace Database\Seeders;

use App\Models\Submaster\FloatEntries;
use Illuminate\Database\Seeder;

class FloatEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FloatEntries::updateOrInsert(['description' => '1 PESO COIN'],['description' => '1 PESO COIN', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntries::updateOrInsert(['description' => '5 PESO COIN'],['description' => '5 PESO COIN', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntries::updateOrInsert(['description' => '10 PESO COIN'],['description' => '10 PESO COIN', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntries::updateOrInsert(['description' => '20 PESO COIN'],['description' => '20 PESO COIN', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntries::updateOrInsert(['description' => '20 PESO BILL'],['description' => '20 PESO BILL', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntries::updateOrInsert(['description' => '50 PESO BILL'],['description' => '50 PESO BILL', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntries::updateOrInsert(['description' => '100 PESO BILL'],['description' => '100 PESO BILL', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntries::updateOrInsert(['description' => '200 PESO BILL'],['description' => '200 PESO BILL', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntries::updateOrInsert(['description' => '500 PESO BILL'],['description' => '500 PESO BILL', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntries::updateOrInsert(['description' => '1000 PESO BILL'],['description' => '1000 PESO BILL', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntries::updateOrInsert(['description' => 'TOKEN'],['description' => 'TOKEN', 'created_at'=>date('Y-m-d H:i:s')]);
    }
}
