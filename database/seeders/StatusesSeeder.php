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
        Statuses::truncate();
        $statuses = [
                        [   
                            'status_description' => 'GOOD',
                            'type'               => 'Gasha Machine'
                        ],
                        [   
                            'status_description' => 'FOR PRINT',
                            'type'               => 'Disburse Token'
                        ],
                        [   
                            'status_description' => 'FOR RECEIVING',
                            'type'               => 'Disburse Token'
                        ],
                        [   
                            'status_description' => 'CLOSED',
                            'type'               => 'Disburse Token'
                        ],
                        [   
                            'status_description' => 'COLLECTED',
                            'type'               => 'Collect Token'
                        ],
                        [   
                            'status_description' => 'FOR CHECKING',
                            'type'               => 'Collect Token'
                        ],
                        [   
                            'status_description' => 'DEFECTIVE',
                            'type'               => 'Gasha Machine'
                        ],
                    ];
    
        foreach ($statuses as $status) {
            Statuses::updateOrInsert(['status_description' => $status['status_description']], [
                'status_description' => $status['status_description'],
                'type'               => $status['type'],
                'created_at'         => date('Y-m-d H:i:s'),
                'created_by'         => 1,
            ]);
        }
    }
}
