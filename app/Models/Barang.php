<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    /** @use HasFactory<\Database\Factories\BarangFactory> */
    use HasFactory;
    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'satuan',
    ];

    public function batchBarang()
    {
        return $this->hasMany(BatchBarang::class);
    }
}
