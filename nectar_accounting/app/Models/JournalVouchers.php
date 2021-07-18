<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalVouchers extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_voucher_no',
        'entry_date_english',
        'entry_date_nepali',
        'fiscal_year_id',
        'payable_to',
        'debitTotal',
        'creditTotal',
        'narration',
        'status',
        'is_cancelled',
        'vendor_id',
        'entry_by',
        'cancelled_by',
        'approved_by',
        'edited_by',
        'editcount',
    ];

    public function journal_extras(){
        return $this->hasMany(JournalExtra::class, 'journal_voucher_id');
    }
    public function fiscal_year(){
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id', 'id');
    }

    public function user_entry()
    {
        return $this->hasOne(User::class, 'id', 'entry_by');
    }

    public function user_edit()
    {
        return $this->hasOne(User::class, 'id', 'edited_by');
    }

    public function user_cancel()
    {
        return $this->hasOne(User::class, 'id', 'cancelled_by');
    }
    public function user_approved()
    {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }
}
