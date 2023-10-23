<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submaster\FloatEntry;
use App\Models\Submaster\FloatType;
use App\Models\Submaster\CashFloatHistory;
use App\Models\Submaster\ModeOfPayment;

class POSDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = [];
        $data['float_entries'] = FloatEntry::where('description', '!=', 'TOKEN')->orderBy('id','desc')->get();
        $data['mode_of_payments'] = ModeOfPayment::get();
        // dd($data['float_entries'] , $data['mode_of_payments']);
        return view('pos-frontend.views.dashboard', $data);
    }
    public function submitSOD(Request $request){
        $data = $request->all();
    
        $locations_id = auth()->user()->location_id;
        $float_types = $request->input('start_day');
        $float_type = FloatType::where('description', $float_types)->first();
        if ($float_type) {
            $float_types_id = $float_type->id;
        } else {
            $float_types_id = null;
        }
        $created_by = auth()->user()->id;
        $time_stamp = date('Y-m-d H:i:s');
        
        CashFloatHistory::create([
            'locations_id' => $locations_id,
            'float_types_id' => $float_types_id,
            'created_by' => $created_by,
            'updated_at' => $time_stamp,
        ]);
        // return response()->json($data);
        return response()->json(['message' => 'Form submitted and data inserted successfully']);
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
