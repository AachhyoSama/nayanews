<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalExtra extends Model
{
    use HasFactory;

    protected $table = "journal_extras";
    protected $fillable = ['journal_voucher_id', 'child_account_id', 'code', 'remarks', 'debitAmount', 'creditAmount'];

    public function journal_voucher(){
        return $this->belongsTo(JournalVouchers::class, 'journal_voucher_id', 'id');
    }
    public function fiscal_year(){
        return $this->journal_voucher()->fiscal_year();
    }

}
