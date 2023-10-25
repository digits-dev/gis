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


        $data['entry_token'] = DB::table('cash_float_histories')
            ->leftJoin('cash_float_history_lines as cfhl', 'cfhl.cash_float_histories_id', 'cash_float_histories.id')
            ->leftJoin('float_types as ft', 'ft.id', 'cash_float_histories.float_types_id')
            ->leftJoin('float_entries as fe', 'fe.id', 'cfhl.float_entries_id')
            // ->select('cash_float_histories.*','cfhl.*','ft.*','fe.*')
            // ->where('fe.description','TOKEN')
            ->select('cash_float_histories.id',
                'fe.description as fe_description',
                'cash_float_histories.created_at',
                'cash_float_histories.created_by',
                'cfhl.value as cfhl_value',
                'ft.description as ft_description')
            ->get();
            // ->pluck('cfhl.value')
            // ->toArray();

        // $data['entry_token'] = DB::table('cash_float_history_lines')
        //     ->leftJoin('cash_float_histories as cfh', 'cfh.id', 'cash_float_history_lines.cash_float_histories_id')
        //     ->leftJoin('float_types as ft', 'ft.id', 'cfh.float_types_id')
        //     ->select('cash_float_history_lines.*', 'cfh.*','ft.*')
        //     ->get();


        dd($data['entry_token']);
        // $data['entry_id'] = CashFloatHistory::get();
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
}
