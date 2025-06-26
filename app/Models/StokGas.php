<?php

namespace App\Models;

use App\Models\TipeGas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokGas extends Model
{
    use HasFactory;

    protected $table = 'stok_gas';

    protected $fillable = ['kode', 'vendor_id', 'tipe_gas_id', 'jumlah_penuh', 'jumlah_pengembalian','jumlah_rusak','harga_beli', 'harga_jual', 'tanggal_masuk'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    protected static function booted()
    {
        static::creating(function ($stok) {
            $lastId = static::max('id') + 1;
            $stok->kode = 'STK-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);
        });
    }

    public function tipeGas()
    {
        return $this->belongsTo(TipeGas::class, 'tipe_gas_id');
    }
}
