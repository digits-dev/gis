<?php
namespace App\Http\Controllers\Token;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use CRUDBooster;
use App\Models\Token\TokenInventory;

class DisburseTokenRequestController extends \crocodicstudio\crudbooster\controllers\CBController
{
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkTokenInventory(Request $request){
          $token = TokenInventory::select(
            'token_inventories.*'
          )
          ->where('id',1)
          ->first();
     
          $data = $token;
        echo json_encode($data);
    }

    
}
