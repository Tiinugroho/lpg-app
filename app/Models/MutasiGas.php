<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MutasiGas extends Model
{
    use HasFactory;

    protected $table = 'mutasi_gas';

    protected $fillable = ['kode', 'produk_id', 'stok_awal', 'stok_masuk', 'stok_keluar', 'stok_akhir', 'total_harga', 'kode_mutasi', 'ket_mutasi', 'tanggal'];

    public function stok()
    {
        return $this->belongsTo(StokGas::class, 'produk_id');
    }
    protected static function booted()
    {
        static::creating(function ($mutasi) {
            $lastId = static::max('id') + 1;
            $mutasi->kode = 'MUT-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);
        });
    }
}
