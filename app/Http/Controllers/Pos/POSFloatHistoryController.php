<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submaster\FloatEntry;
use App\Models\Submaster\FloatType;
use App\Models\Submaster\CashFloatHistory;
use App\Models\Submaster\ModeOfPayment;
use App\Models\Submaster\CashFloatHistoryLine;
use App\Models\Submaster\Locations;
use App\Http\Controllers\Pos\POSDashboardController;

use DB;

class POSFloatHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $is_missing_eod_or_sod = (new POSDashboardController)->check_sod_or_eod();
        if ($is_missing_eod_or_sod) {
            return $is_missing_eod_or_sod;
        }
        $token_id = FloatEntry::where('description', 'TOKEN')->pluck('id');
        $data = [];
        $data['float_entries'] = FloatEntry::where('description', '!=', 'TOKEN')->orderBy('id','desc')->get();
        $data['mode_of_payments'] = DB::table('mode_of_payments')->where('status', 'ACTIVE')->get()->toArray();
        $data['mode_of_payments'] = array_map(function($obj) {
            $obj->payment_custom_desc = preg_replace("/[^a-zA-Z]/", '_', $obj->payment_description);
            return $obj;
        }, $data['mode_of_payments']);

        $cash_floats_histories_id = CashFloatHistory::pluck('id');
        $data['entries'] = DB::table('float_history_view')
            ->leftJoin('float_types', 'float_types.id', 'float_history_view.float_types_id')
            ->leftJoin('cash_float_histories','cash_float_histories.id','float_history_view.cash_float_histories_id')
            ->leftJoin('cms_users','cms_users.id','cash_float_histories.created_by')
            ->leftJoin('cash_float_history_lines', 'cash_float_history_lines.cash_float_histories_id', 'float_history_view.cash_float_histories_id')
            ->leftJoin('locations', 'locations.id','cash_float_histories.locations_id')
            ->select('*','cash_float_histories.created_at', 'cash_float_history_lines.qty as token_qty', 'float_history_view.entry_date')
            ->where('cash_float_history_lines.float_entries_id', $token_id)
            ->get()
            ->toArray();

            // dd($data['entries']);
        
        return view('pos-frontend.views.float-history', $data);
    }

    public function viewFloatHistory($id){
        $data =[];

        $data['cash_float_history'] = DB::table('cash_float_histories')
            ->where('cash_float_histories.id', $id)
            ->leftJoin('float_history_view', 'float_history_view.cash_float_histories_id', 'cash_float_histories.id')
            ->leftJoin('float_types', 'float_types.id', 'cash_float_histories.float_types_id')
            ->select(
                'cash_float_histories.id',
                'float_history_view.entry_date',
                'float_history_view.cash_value',
                'float_history_view.token_value',
                'float_types.description',
            )
            ->first();

        $data['cash_float_history_lines'] = DB::table('cash_float_history_lines')
            ->where('cash_float_history_lines.cash_float_histories_id', $id)
            ->leftJoin('float_entries', 'float_entries.id', 'cash_float_history_lines.float_entries_id')
            ->leftJoin('mode_of_payments','mode_of_payments.id','cash_float_history_lines.mode_of_payments_id')
            ->select(
                'cash_float_history_lines.qty as line_qty',
                'cash_float_history_lines.value as line_value',
                'float_entries.description as entry_description',
                'float_entries.value as entry_value',
                'mode_of_payments.payment_description as payment_description',
            )
            ->get()
            ->toArray();


        $data['cash_float_history_lines'] = array_map(function($obj) {
            $obj->payment_custom_desc = preg_replace("/[^a-zA-Z]/", '_', $obj->payment_description);
            return $obj;
        }, $data['cash_float_history_lines']);
        
        
        return response()->json($data);
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

    public function cronFloatHistory() {
        $yesterday = date('Y-m-d', strtotime('yesterday'));

        $locations_ids = DB::table('locations')
            ->where('status', 'ACTIVE')
            ->where('id', '!=', 1)
            ->pluck('id')
            ->toArray();
        
        foreach ($locations_ids as $location_id) {
            $is_yesterday_existing = DB::table('float_entry_view')
                ->where('entry_date', $yesterday)
                ->where('locations_id', $location_id)
                ->exists();

            if (!$is_yesterday_existing) {
                DB::table('cash_float_histories')->insert([
                    'locations_id' => $location_id,
                    'entry_date' => $yesterday,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
