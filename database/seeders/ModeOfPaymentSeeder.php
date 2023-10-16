<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submaster\ModeOfPayment;

class ModeOfPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_descriptions = ['CASH', 'BDO', 'BPI', 'GCASH', 'PAYMAYA'];

        foreach ($payment_descriptions as $payment_description) {
            ModeOfPayment::updateOrInsert(['payment_description' => $payment_description], [
                'payment_description' => $payment_description,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
            ]);
        }
    }
}
