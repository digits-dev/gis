<?php

namespace App\Models\CmsModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPrivileges extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'cms_privileges';
}