<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Capsule\InventoryCapsule;
use App\Models\Submaster\FloatEntry;
use App\Models\Submaster\FloatType;
use App\Models\Submaster\CashFloatHistory;
use App\Models\Submaster\ModeOfPayment;
use App\Models\Submaster\CashFloatHistoryLine;
use App\Models\Submaster\GashaMachines;
use App\Models\Submaster\Item;
use App\Models\Submaster\TokenConversion;
use App\Models\Token\TokenInventory;
use DB;


class POSDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $location_id = auth()->user()->location_id;
                
        $data = [];
        $data['float_entries'] = FloatEntry::where('description', '!=', 'TOKEN')->orderBy('id','desc')->get();
        $data['mode_of_payments'] = ModeOfPayment::get();
        $data['no_of_tokens'] = TokenInventory::where('locations_id', $location_id)->first()->qty;
        $data['no_of_capsules_in_stock_room'] =DB::table('inventory_capsules')
            ->where('locations_id', $location_id)
            ->where('sub_locations_id', '!=', 'null')
            ->leftJoin('inventory_capsule_lines as icp', 'icp.inventory_capsules_id', 'inventory_capsules.id')
            ->sum('qty');
        $data['no_of_capsules_in_machine'] =DB::table('inventory_capsules')
            ->where('locations_id', $location_id)
            ->where('gasha_machines_id', '!=', 'null')
            ->leftJoin('inventory_capsule_lines as icp', 'icp.inventory_capsules_id', 'inventory_capsules.id')
            ->sum('qty');
        $data['no_of_gm'] = GashaMachines::where('location_id', $location_id)->count();
        $data['no_of_items'] = Item::count();
        $data['monthly_swap'] = DB::table('monthly_swap_view')
            ->where('locations_id', $location_id)
            ->orderBy('year', 'desc')
            ->orderBy('month')
            ->get()
            ->toArray();   
        $data['user'] = DB::table('cms_users')
            ->leftJoin('locations as loc', 'loc.id', 'cms_users.location_id')
            ->where('cms_users.id',auth()->user()->id)
            ->first();
        $missing_eod = DB::table('float_entry_view')
            ->where('locations_id',$location_id )
            ->where('eod',null)
            ->where('entry_date', '!=', date('Y-m-d'))
            ->first();

        if ($missing_eod) {
            return redirect(url('pos_end_of_day'))->with('is_missing', true);
        }
        $missing_sod = DB::table('float_entry_view')
            ->where('locations_id',$location_id )
            ->where('entry_date', date('Y-m-d'))
            ->exists();

        $data['missing_sod'] = $missing_sod;
        return view('pos-frontend.views.dashboard', $data);

    }

    public function submitSOD(Request $request){
        $data = $request->all();
        $locations_id = auth()->user()->location_id;
        $entry_date = $request->input('entry_date');
        $float_types = $request->input('start_day');
        $float_type = FloatType::where('description', $float_types)->first();

        $entry_today = DB::table('float_entry_view')
            ->where('entry_date', date('Y-m-d'))
            ->where('locations_id', $location_id)
            ->first();

        if ($entry_today->sod) {
            return response()->json(['has_sod_today' => true]);
        }

        if ($float_type) {
            $float_types_id = $float_type->id;
        } else {
            $float_types_id = null;
        }
        $created_by = auth()->user()->id;
        $time_stamp = date('Y-m-d H:i:s');

        $token_price = DB::table('token_conversions')
            ->where('status', 'ACTIVE')
            ->pluck('current_cash_value')
            ->first();

        $current_token_bal = DB::table('token_inventories')
            ->where('locations_id', $location_id)
            ->pluck('qty')
            ->first();
        
        $cash_float_history_id = CashFloatHistory::insertGetId([
            'locations_id' => $locations_id,
            'float_types_id' => $float_types_id,
            'conversion_rate' => $token_price,
            'token_bal' => $current_token_bal,
            'entry_date' => date('Y-m-d'),
            'created_by' => $created_by,
            'created_at' => $time_stamp,
        ]);
        
        $lines = [];

        $mode_of_payments = ModeOfPayment::where('status', 'ACTIVE')
            ->get()
            ->toArray();

        foreach ($mode_of_payments as $mop) {
            $valueWithComma = $data['cash_value_' . $mop['payment_description']];
            $valueWithoutComma = (float)str_replace(',','',$valueWithComma);
            $lines[] = [
                'cash_float_histories_id' => $cash_float_history_id,
                'mode_of_payments_id' => $mop['id'],
                'float_entries_id' => null,
                'qty' => $valueWithoutComma ? 1 : null,
                'value' => $valueWithoutComma,
                'created_by' => $created_by,
                'created_at' => $time_stamp,
            ];
        }
        
        $float_entries = FloatEntry::where('status', 'ACTIVE')
            ->get()
            ->toArray();


        foreach ($float_entries as $fe) {
            $floatEntriesId = $fe['id'];
            $floatEntriesDescription = $fe['description'];
            $tokenWithoutComma = (float)str_replace(',','',$data['total_token']);
            $qtyWithoutComma = (float)str_replace(',','',$data['cash_value_' . $fe['description']]);

            
            if ($floatEntriesDescription == 'TOKEN') {
                $modeOfPaymentsId = null;
                $qty =  $tokenWithoutComma;
                $value = $token_price * $tokenWithoutComma;
            } else {
                $modeOfPaymentsId = null;
                $qty = $qtyWithoutComma;
                $value = $data['cash_value_' . $fe['description']] * $fe['value'];
            }
        
            $lines[] = [
                'cash_float_histories_id' => $cash_float_history_id,
                'mode_of_payments_id' => $modeOfPaymentsId,
                'float_entries_id' => $floatEntriesId,
                'qty' => preg_replace('/\D/', '', (string) $qty),
                'value' => $value,
                'created_by' => $created_by,
                'created_at' => $time_stamp,
            ];
        }

        
        CashFloatHistoryLine::insert($lines);
        // DB::table('cash_float_history_lines')->insert($lines);

        
        return response()->json($data);
        // return response()->json(['message' => 'Form submitted and data inserted successfully']);
    }


    public function check_sod() {
        $email = auth()->user()->email;
        $is_sod_existing = DB::table('cms_users')
            ->leftJoin('float_entry_view as fev', 'fev.locations_id', 'cms_users.location_id')
            ->select('cms_users.*',
                'fev.entry_date',
                'fev.sod',
                'fev.eod'
            )
            ->where('email', $email)
            ->where('status', 'ACTIVE')
            ->where('entry_date', date('Y-m-d'))
            ->get()
            ->first();

        return $is_sod_existing;
    }

    public function check_sod_or_eod() {
        $location_id = auth()->user()->location_id;
        
        $missing_eod = DB::table('float_entry_view')
            ->where('locations_id',$location_id )
            ->where('eod',null)
            ->where('entry_date', '!=', date('Y-m-d'))
            ->first();

        if ($missing_eod) {
            return redirect(url('pos_end_of_day'))->with('is_missing', true);
        }

        $with_sod = DB::table('float_entry_view')
            ->where('locations_id',$location_id )
            ->whereNotNull('sod')
            ->where('entry_date', date('Y-m-d'))
            ->first();

        if (!$with_sod) {
            return redirect(url('pos_dashboard'));
        }

        return false;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
