<?php

namespace App\Models\CmsModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPrivileges extends Model
{
    public const SUPERADMIN = 1;
    public const ACCOUNTING = 2;
    public const CASHIER = 3;
    public const AUDIT = 4;
    public const OIC = 5;
    public const AREAMANAGER = 6;
    public const OPERATIONSHEAD = 7;
    public const INVENTORYCONTROL = 8;
    public const ISD = 9;
    public const CSA = 10;
    public const STAFF1 = 11;
    public const STAFF2 = 12;
    public const BRANDS = 13;
    public const AUDITAPPROVER = 14;

    use HasFactory;
    protected $guarded = [];
    protected $table = 'cms_privileges';
}