<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submaster\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = ["MILK BOTTLE CAP POUCH [REPEAT]","NOBAMAN GAMES CAPSULE RUBBER MASCOT","STILL WAITING FOR YOU ATTACK ON TITAN THE FINAL SEASON CAPSULE RUBBER MASCOT","KAMEN RIDER GEATS CAPSULE RUBBER MASCOT 2","KAMEN RIDER DEN-O IMAJIN MASCOT","TOHATO MINIATURE COLLECTION","CHAINSAW MAN CAPSULE RUBBER MASCOT","STILL WAITING FOR YOU UNIDENTIFIED MYSTERIOUS ANIMAL VER. VOL.3","HACHUHACHU CHARM BEST","MEAT RING RETURNS!","NEWTRO POP DINER","SUNRISE ROBOT POSTER ACRYLIC STAND 01","THE DIVERSITY OF LIFE ON EARTH ADVANCE MIGRATORY LOCUST","BLUE LOCK PUNITOP CAPSULE PLUSH COLLECTION 2","GOTOBUN NO HANAYOME CAPCHARA HEROINES 01","SANRIO BLANC BLANC 2"];
        $digits_code = 60000000; 
        
        foreach ($items as $item) {
            Item::updateOrInsert(['item_description' => $item], [
                'digits_code' => $digits_code,
                'item_description' => $item,
                'no_of_tokens' => $digits_code & 1 ? 3 : 4,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $digits_code++;
        }
    }
}
