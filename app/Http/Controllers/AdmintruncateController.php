<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use CRUDBooster;

class AdminTruncateController extends \crocodicstudio\crudbooster\controllers\CBController
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
        DB::table('collect_rr_tokens')->truncate();
        DB::table('collect_rr_token_lines')->truncate();
        DB::table('capsule_refills')->truncate();
        DB::table('capsule_returns')->truncate();
        DB::table('capsule_sales')->truncate();
        DB::table('cash_float_histories')->truncate();
        DB::table('cash_float_history_lines')->truncate();
        DB::table('gasha_machines')->truncate();
        DB::table('history_capsules')->truncate();
        DB::table('inventory_capsules')->truncate();
        DB::table('inventory_capsule_lines')->truncate();
        DB::table('swap_histories')->truncate();
        DB::table('token_conversion_histories')->truncate();

        return "Truncated Successfully";
    }
}
