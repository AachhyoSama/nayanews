<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChildAccount extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['sub_account_id', 'title', 'slug'];

    protected $dates = [ 'deleted_at' ];

    public function subAccount()
    {
        return $this->belongsTo(SubAccount::class);
    }
    public function journal_extras(){
        return $this->hasMany(JournalExtra::class, 'child_account_id');
    }
}
