<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use CRUDBooster;

class AdmintruncateController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function dbtruncate(){
        if(!CRUDBooster::isSuperadmin()) {    
            CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"),'danger');
        }
        DB::table('pullout_tokens')->truncate();
        DB::table('receiving_tokens')->truncate();
        DB::table('store_rr_token')->truncate();
        DB::table('token_histories')->truncate();
        DB::table('token_inventories')->truncate();

        return "Truncated Successfully";
    }
}
