<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenjualanGas extends Model
{
    use HasFactory;

    protected $table = 'penjualan_gas';

    protected $fillable = ['kode_transaksi', 'nama_pembeli', 'no_kk', 'no_telp', 'produk_id', 'jumlah', 'harga_jual_satuan', 'total_harga', 'tanggal_transaksi', 'keterangan'];

    public function produk()
    {
        return $this->belongsTo(StokGas::class, 'produk_id');
    }

    protected static function booted()
    {
        static::creating(function ($transaksi) {
            $lastId = static::max('id') + 1;
            $transaksi->kode_transaksi = 'TRX-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);
        });
    }
}
