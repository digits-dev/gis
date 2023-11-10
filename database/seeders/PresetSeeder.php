<?php

namespace Database\Seeders;
use App\Models\Submaster\Preset;

use Illuminate\Database\Seeder;

class PresetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $presets = ['1','3','5', '10', '15', '20', '25', '50', '100', 'GCASH REF# ', 'APP CODE DEBIT ', 'APP CODE CREDIT '];
        Preset::truncate();
        foreach ($presets as $preset) {
            Preset::updateOrInsert(['value' => $preset], [
                'value' => $preset,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
            ]);
        }
    }
}