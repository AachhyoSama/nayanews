<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyExpenses extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'date',
        'bill_image',
        'bill_number',
        'bill_amount',
        'paid_amount',
        'purpose'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
