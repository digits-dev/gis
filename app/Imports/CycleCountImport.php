<?php

namespace App\Imports;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use DB;
use CRUDBooster;
use App\Models\Audit\CycleCount;
use App\Models\Audit\CycleCountLine;
use App\Models\Capsule\CapsuleSales;
use App\Models\Capsule\HistoryCapsule;
use App\Models\Capsule\InventoryCapsule;
use App\Models\Capsule\InventoryCapsuleLine;
use App\Models\CmsModels\CmsModule;
use App\Models\Submaster\CapsuleActionType;
use App\Models\Submaster\Counter;
use App\Models\Submaster\GashaMachines;
use App\Models\Submaster\Item;
use App\Models\Submaster\Locations;
use App\Models\Submaster\SalesType;
use App\Models\Submaster\SubLocations;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class CycleCountImport implements ToCollection, WithHeadingRow, WithChunkReading, WithStrictNullComparison {
    protected $filename;
    protected $location_id;
    protected $quantity_total;

    private const CYCLE_COUNT_ACTION = 'Cycle Count';
    private const CYCLE_SALE_TYPE = 'CYCLE COUNT';
    private const STOCK_ROOM = 'STOCK ROOM';
    
    function __construct($datas) {
        $this->filename = $datas['filename'];
        $this->location_id = $datas['location_id'];
        $this->quantity_total = $datas['quantity_total'];
    }

    public function collection(Collection $rows){
        $cycleCountFloorRef = Counter::getNextReference(CRUDBooster::getCurrentModule()->id);
        foreach ($rows->toArray() as $key_item => $item_value){
            if($item_value['quantity'] === '' || $item_value['quantity'] === NULL){
                unlink(public_path('cycle-count-files/'.basename($this->filename)));
                return CRUDBooster::redirect(CRUDBooster::adminpath('cycle_counts'),"Quantity required! Please put zero if N/A! at row: ".($key_item+2),"danger");
            }
            $sublocation_id = SubLocations::where('location_id',$this->location_id)->where('description',self::STOCK_ROOM)->first();
            $item = Item::where('digits_code',$item_value['item_code'])->first();
            $fqty = str_replace(',', '', $item_value['quantity']);
            $capsuleHeader = [
                'reference_number' => $cycleCountFloorRef,
                'locations_id' => $this->location_id
            ];

            $capsuleInventory = InventoryCapsule::where('item_code',$item->digits_code2)
            ->where('locations_id',$this->location_id)->first();

            $capsuleInventoryLine = InventoryCapsuleLine::where([
                'inventory_capsules_id'=>$capsuleInventory->id,
                'sub_locations_id'=> $sublocation_id->id,
                'gasha_machines_id'=> null
            ])->first();

            $capsule = CycleCount::firstOrCreate($capsuleHeader,[
                'reference_number' =>$cycleCountFloorRef,
                'locations_id' => $this->location_id,
                'sub_locations_id' => $sublocation_id->id,
                'total_qty' => $this->quantity_total,
                'created_by' => CRUDBooster::myId(),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $capsuleLines = new CycleCountLine([
                'cycle_counts_id' => $capsule->id,
                'digits_code' => $item_value['item_code'],
                'qty' => $fqty,
                'variance' => ($fqty - $capsuleInventoryLine->qty),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $capsuleLines->save();

            HistoryCapsule::insert([
                'reference_number' => $capsule->reference_number,
                'item_code' => $item->digits_code2,
                'capsule_action_types_id' => CapsuleActionType::getByDescription(self::CYCLE_COUNT_ACTION)->id,
                'locations_id' => $this->location_id,
                'from_sub_locations_id' => $sublocation_id->id,
                'qty' => ($fqty - $capsuleInventoryLine->qty),
                'created_by' => CRUDBooster::myId(),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if(!empty($capsuleInventoryLine) || !is_null($capsuleInventoryLine)){
                InventoryCapsuleLine::where([
                    'inventory_capsules_id' => $capsuleInventory->id,
                    'sub_locations_id'=> $sublocation_id->id
                ])->update([
                    'qty' => $fqty,
                    'updated_by' => CRUDBooster::myId(),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            else{
                InventoryCapsuleLine::insert([
                    'inventory_capsules_id' => $capsuleInventory->id,
                    'sub_locations_id'=> $sublocation_id->id,
                    'qty' => $fqty,
                    'updated_by' => CRUDBooster::myId(),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
