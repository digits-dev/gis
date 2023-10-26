<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submaster\FloatEntry;
use App\Models\Submaster\FloatType;
use App\Models\Submaster\CashFloatHistory;
use App\Models\Submaster\ModeOfPayment;
use App\Models\Submaster\CashFloatHistoryLine;
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
        $data = [];

        
        $cash_floats_histories_id = CashFloatHistory::pluck('id');


        $data['entries'] = DB::table('float_history_view')
            ->leftJoin('float_types', 'float_types.id', 'float_history_view.float_types_id')
            ->get()
            ->toArray();

        

        return view('pos-frontend.views.float-history', $data);
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
