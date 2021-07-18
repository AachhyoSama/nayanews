<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalImage extends Model
{
    use HasFactory;

    protected $fillable = ['journalvoucher_id', 'location'];
}
