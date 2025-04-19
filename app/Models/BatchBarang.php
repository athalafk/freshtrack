<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchBarang extends Model
{
    /** @use HasFactory<\Database\Factories\BatchBarangFactory> */
    use HasFactory;
    protected $table = 'batch_barang';

    protected $fillable = [
        'barang_id',
        'tanggal_kadaluarsa',
        'stok',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
