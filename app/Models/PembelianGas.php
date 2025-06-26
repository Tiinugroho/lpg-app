<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembelianGas extends Model
{
    use HasFactory;

    protected $table = 'pembelian_gas';

    protected $fillable = ['kode_pembelian', 'produk_id', 'vendor_id', 'jumlah', 'harga_beli', 'tanggal_masuk', 'keterangan'];

    public function produk()
    {
        return $this->belongsTo(StokGas::class, 'produk_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    protected static function booted()
    {
        static::creating(function ($pembelian) {
            $lastId = static::max('id') + 1;
            $pembelian->kode_pembelian = 'PB-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);
        });
    }
}
