<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = ['date', 'type', 'item', 'stock', 'actor'];
    public $timestamps = false; 
}
