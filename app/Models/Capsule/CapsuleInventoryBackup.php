<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapsuleInventoryBackup extends Model
{
    use HasFactory;
    protected $table = 'capsule_inventory_backup';
    protected $guarded = [];
}
