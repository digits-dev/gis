<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapsuleSalesBackUp extends Model
{
    use HasFactory;
    protected $table = 'capsule_sales_backup';
    protected $guarded = [];
}
