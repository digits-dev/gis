<?php

namespace Database\Seeders;

use App\Models\Submaster\FloatEntry;
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
        FloatEntry::truncate();
        FloatEntry::updateOrInsert(['description' => '1C'],['description' => '1C', 'value'=>'0.01', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => '5C'],['description' => '5C', 'value'=>'0.05', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => '10C'],['description' => '10C', 'value'=>'0.10', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => '25C'],['description' => '25C', 'value'=>'0.25', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => 'P1'],['description' => 'P1', 'value'=>'1', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => 'P5'],['description' => 'P5', 'value'=>'5', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => 'P10'],['description' => 'P10', 'value'=>'10', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => 'P20'],['description' => 'P20', 'value'=>'20', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => 'P50'],['description' => 'P50', 'value'=>'50', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => 'P100'],['description' => 'P100', 'value'=>'100', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => 'P200'],['description' => 'P200', 'value'=>'200', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => 'P500'],['description' => 'P500', 'value'=>'500', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => 'P1000'],['description' => 'P1000', 'value'=>'1000', 'created_at'=>date('Y-m-d H:i:s')]);
        FloatEntry::updateOrInsert(['description' => 'TOKEN'],['description' => 'TOKEN', 'value'=>'1', 'created_at'=>date('Y-m-d H:i:s')]);
    }
}
