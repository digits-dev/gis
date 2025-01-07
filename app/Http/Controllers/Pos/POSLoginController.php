<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CmsUsers;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;

class POSLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->check()){
            return redirect()->intended('pos_dashboard');
        }
        
        return view('pos-frontend.views.login-page');
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

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email_auth = $request->all()['email'];

        $user = DB::table('cms_users')
            ->where('email', $email_auth)
            ->where('status', 'ACTIVE')
            ->first();

        if(!in_array($user->id_cms_privileges, [1,3,5,14])){
            return redirect('pos_login');
        }

        $locations_id = $user->location_id;

        $with_eod = DB::table('float_entry_view')
            ->where('locations_id',$locations_id )
            ->whereNotNull('eod')
            ->where('entry_date', date('Y-m-d'))
            ->first();

        if (!$with_eod && Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('pos_dashboard');
        }

        if ($with_eod) {
            $error = "EOD has been set, and you can't log in to the system after the end of the day.";
        } else {
            $error = "Incorrect email or password";
        }
        return redirect('pos_login')->withErrors(['error' => $error]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/pos_login')->with(['logged_out_success'=>'Successfully logout']);
    }

    public function logoutEOD(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/pos_login')->with(['logged_out_success'=>'Have a great day!']);
    }

    public function endSession(Request $request){

        Auth::logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
