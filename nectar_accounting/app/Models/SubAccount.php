<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubAccount extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['account_id', 'title', 'slug'];

    protected $dates = [ 'deleted_at' ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function child_account()
    {
        return $this->hasMany(ChildAccount::class);
    }

    protected static $relations_to_cascade = ['child_account'];

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
    public function child_accounts()
    {
        return $this->hasMany(ChildAccount::class, 'sub_account_id');
    }

}


