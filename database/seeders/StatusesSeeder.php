<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submaster\Statuses;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['GOOD'];
        $type     = ['Gasha Machine'];
        foreach ($statuses as $key => $status) {
            Statuses::updateOrInsert(['status_description' => $status], [
                'status_description' => $status,
                'type'               => $type[$key],
                'created_at'         => date('Y-m-d H:i:s'),
                'created_by'         => 1,
            ]);
        }
    }
}
