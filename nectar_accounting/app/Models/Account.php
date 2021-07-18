<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'slug'];

    protected $dates = [ 'deleted_at' ];

    public function sub_account() {
        return $this->hasMany(SubAccount::class);
    }

    protected static $relations_to_cascade = ['sub_account'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($resource) {
            foreach (static::$relations_to_cascade as $relation) {
                foreach ($resource->{$relation}()->get() as $item) {
                    $item->delete();
                }
            }
        });
    }
    public function sub_accounts(){
        return $this->hasMany(SubAccount::class, 'account_id');
    }
    public function child_accounts(){
        return $this->sub_accounts()->child_accounts();
    }
    public function journal_vouchers(){
        return $this->subaccounts()->child_accounts()->journal_extras();
    }

}
