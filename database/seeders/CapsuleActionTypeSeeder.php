<?php

namespace Database\Seeders;

use App\Models\Submaster\CapsuleActionType;
use Illuminate\Database\Seeder;

class CapsuleActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $capsule_descriptions = ['DR', 'Refill', 'Return', 'Swap'];
        
        foreach($capsule_descriptions as $capsule_description){
            CapsuleActionType::updateOrInsert(['description' => $capsule_description], [
                'description' => $capsule_description,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
            ]);
        }
    }
}