<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Submaster\ModeOfPayment;
use App\Models\Submaster\Locations;
class ItemPos extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'total_value',
        'change_value',
        'mode_of_payments_id',
        'locations_id',
        'status',
        'payment_reference',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $filterable = [
        'reference_number',
        'total_value',
        'change_value',
        'mode_of_payments_id',
        'locations_id',
        'status',
        'payment_reference',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];


    public function scopeFilter($query, array $filters) {
        if($filters['search'] ?? false) {
            $search = $filters['search'];
            $query->where(function ($query) use ($search) {
                foreach ($this->filterable as $field) {
                    if ($field === 'mode_of_payments_id') {
                        $query->orWhereHas('ModeOfPayments', function ($query) use ($search) {
                            $query->where('payment_description', 'LIKE', "%$search%");
                        });
                    }else if ($field === 'locations_id') {
                        $query->orWhereHas('location', function ($query) use ($search) {
                            $query->where('location_name', 'LIKE', "%$search%");
                        });
                    }else{
                        $query->orWhere($field, 'LIKE', "%$search%");
                    }
                }
            });
        }
    }

    public function item_lines(): HasMany
    {
        return $this->hasMany(ItemPosLines::class, 'item_pos_id');
    }

    public function creator()
    {
        return $this->belongsTo(CmsUsers::class, 'created_by');
    }

    public function updator()
    {
        return $this->belongsTo(CmsUsers::class, 'updated_by');
    }

    public function ModeOfPayments()
    {
        return $this->belongsTo(ModeOfPayment::class, 'mode_of_payments_id');
    }
    public function location()
    {
        return $this->belongsTo(Locations::class, 'locations_id');
    }

}
