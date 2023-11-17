<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submaster\AddOnActionType;


class AddOnsActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $add_ons_descriptions = ['DR','Swap'];

        foreach ($add_ons_descriptions as $add_ons_description) {
            AddOnActionType::updateOrInsert(['description' => $add_ons_description], [
                'description' => $add_ons_description,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
            ]);
        }
    }
}
