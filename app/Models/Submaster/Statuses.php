<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statuses extends Model
{
    use HasFactory;
    protected $table = 'statuses';

    public const GOOD = 1;
    public const FORPRINT = 2;
    public const FORRECEIVING = 3;
    public const CLOSED = 4;
    public const COLLECTED = 5;
    public const CANCELLED = 6;
    public const DEFECTIVE = 7;
    public const RECEIVED = 8;
    public const FORAPPROVAL = 9;
    public const FORCASHIERTURNOVER = 10;
    public const FORSTOREHEADAPPROVAL = 11;
    public const FORCONFIRMATION = 12;

}
