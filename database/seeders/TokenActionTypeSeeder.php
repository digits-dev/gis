<?php

namespace Database\Seeders;

use App\Models\Submaster\TokenActionType;
use Illuminate\Database\Seeder;

class TokenActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $token_descriptions = ['Add Token', 'Disburse', 'Receive', 'Deduct','Swap','Collect'];

        foreach ($token_descriptions as $token_description) {
            TokenActionType::updateOrInsert(['description' => $token_description], [
                'description' => $token_description,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
            ]);
        }
    }
}