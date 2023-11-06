<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submaster\FloatEntry;
use App\Models\Submaster\FloatType;
use App\Models\Submaster\CashFloatHistory;
use App\Models\Submaster\ModeOfPayment;
use App\Models\Submaster\CashFloatHistoryLine;
use App\Http\Controllers\Pos\POSDashboardController;
use DB;

class POSEndOfDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $location_id = auth()->user()->location_id;


        $data['float_entries'] = FloatEntry::where('description', '!=', 'TOKEN')->orderBy('id','desc')->get();
        $data['mode_of_payments'] = ModeOfPayment::get();
        $missing_eod = DB::table('float_entry_view')
            ->where('locations_id',$location_id )
            ->where('eod',null)
            ->first();

        $entry_date = $missing_eod->entry_date;

        if (!$missing_eod) {
            return redirect(url('pos_dashboard'));
        }

        $data['entry_date'] = $missing_eod->entry_date;


        $entry_today = DB::table('float_entry_view')
            ->where('locations_id',$location_id )
            ->where('entry_date', date('Y-m-d'))
            ->where('eod','!=',null)
            ->first();


        $data['have_eod_today'] = !!$entry_today->eod;
        return view('pos-frontend.views.end-of-day', $data);

    }
    public function submitEOD(Request $request){
        $data = $request->all();
        $locations_id = auth()->user()->location_id;
        $entry_date = $request->input('entry_date');
        $float_types = $request->input('end_day');
        $float_type = FloatType::where('description', $float_types)->first();
        if ($float_type) {
            $float_types_id = $float_type->id;
        } else {
            $float_types_id = null;
        }
        $created_by = auth()->user()->id;
        $time_stamp = date('Y-m-d H:i:s');

        $token_price = DB::table('token_conversions')->where('status', 'ACTIVE')
            ->pluck('current_cash_value')
            ->first();

        $current_token_bal = DB::table('token_inventories')
            ->where('locations_id', $locations_id)
            ->pluck('qty')
            ->first();
        
        $cash_float_history_id = CashFloatHistory::insertGetId([
            'locations_id' => $locations_id,
            'float_types_id' => $float_types_id,
            'conversion_rate' => $token_price,
            'token_bal' => $current_token_bal,
            'entry_date' => $entry_date,
            'created_by' => $created_by,
            'created_at' => $time_stamp,
        ]);
        
        $lines = [];

        $mode_of_payments = ModeOfPayment::where('status', 'ACTIVE')
            ->get()
            ->toArray();

        foreach ($mode_of_payments as $mop) {
            $valueWithComma = $data['cash_value_' . $mop['id']];
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


        
        return response()->json(['has_sod' => $entry_date == date('Y-m-d')]);
        // return response()->json(['message' => 'Form submitted and data inserted successfully']);
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
