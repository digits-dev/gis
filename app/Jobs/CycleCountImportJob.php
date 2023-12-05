<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CycleCountImport;
use CRUDBooster;

class CycleCountImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $file;
    protected $location_id;
    protected $quantity_total;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($datas)
    {
        $this->file = $datas['file'];
        $this->location_id = $datas['location_id'];
        $this->quantity_total = $datas['quantity_total'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [];
        $data['filename'] = $this->file;
        $data['location_id'] = $this->location_id;
        $data['quantity_total'] = $this->quantity_total;
        Excel::import(new CycleCountImport($data), $this->file);
        CRUDBooster::redirect(CRUDBooster::adminpath('cycle_counts'),'Success! Cycle count has been created','success ')->send();
        // unlink(public_path('cycle-count-files/'.basename($this->file)));
    }
}
