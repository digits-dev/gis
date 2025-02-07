<?php

namespace App\Models\Token;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeginningBalVsTokenOnHand extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'token_on_hand_vs_beginning_bal_report';

    protected $fillable = [
        'locations_id',
        'location_name',
        'total_beginning_bal',
        'total_token_on_hand',
        'variance',
        'generated_date',
        'created_by',
        'created_at',
    ];
}
