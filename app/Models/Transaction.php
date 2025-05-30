<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = ['type', 'item', 'stock', 'actor'];
    protected $hidden = [
        'updated_at', 
    ];
}