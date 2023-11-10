<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submaster\ModeOfPayment;
use App\Models\Submaster\TokenConversion;
use App\Models\Submaster\Preset;
use App\Models\Token\TokenInventory;
use App\Models\PosFrontend\POSTokenSwap;
use App\Models\PosFrontend\SwapHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Pos\POSDashboardController;



class POSTokenSwapController extends Controller
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

        $data = [];
        $data['tokens'] = Preset::where('status', 'ACTIVE')->where('preset_type', 'token')->select('value')->get();
        $data['paymayas'] = Preset::where('status', 'ACTIVE')->where('preset_type', 'paymaya')->select('value')->get();
        $data['mode_of_payments'] = ModeOfPayment::get();
        $data['cash_value'] = TokenConversion::first()->current_cash_value;
        $data['inventory_qty'] = TokenInventory::where('locations_id', Auth::user()->location_id)->value('qty');

        return view('pos-frontend.views.token-swap',$data);
        
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
        $tokenSwapCount = DB::table('swap_histories')->max('id');
        $headerCount = str_pad($tokenSwapCount + 1, 8, "0", STR_PAD_LEFT);
        $refNumber = 'TS-'.$headerCount;
        
        $token_inventory = TokenInventory::where('locations_id', Auth::user()->location_id);
        $token_inventory_qty = $token_inventory->first()->qty;
        $total_qty = $token_inventory_qty - intval(str_replace(',', '',$request->token_value));

        if ($token_inventory_qty >= intval(str_replace(',', '',$request->token_value))) {
            
            TokenInventory::updateOrInsert(['locations_id' => Auth::user()->location_id],['qty' => $total_qty]);
            $current_cash_value = DB::table('token_conversions')->value('current_cash_value');
            $typeId = DB::table('token_action_types')->select('id')->where('description', 'Swap')->first()->id;
            
            DB::table('swap_histories')->insert([
                'reference_number' =>  $refNumber,
                'cash_value' => intval(str_replace(',', '',$request->cash_value)),
                'token_value' => intval(str_replace(',', '',$request->token_value)),
                'total_value' => intval(str_replace(',', '',$request->total_value)),
                'change_value' => intval(str_replace(',', '',$request->change_value)),
                'current_cash_value' => $current_cash_value,
                'type_id' => $typeId,
                'locations_id' => Auth::user()->location_id,
                'mode_of_payments_id' => $request->mode_of_payment,
                'payment_reference' => $request->amount_received ?? $request->payment_reference,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

            return json_encode(['message'=>'success', 'reference_number'=> $refNumber]);
        
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