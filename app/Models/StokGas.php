<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokGas extends Model
{
    use HasFactory;

    protected $table = 'stok_gas';

    protected $fillable = [
        'nama_gas',
        'jumlah_tabung_penuh',
        'jumlah_tabung_kosong',
        'jumlah_tabung_rusak',
        'harga_beli_per_tabung',
        'harga_jual_per_tabung',
        'tanggal_update',
    ];

    protected $casts = [
        'harga_per_tabung' => 'decimal:2',
        'tanggal_update' => 'date',
    ];

    public static function getStokTerkini()
    {
        return self::latest('tanggal_update')->first();
    }

    public function getTotalTabungAttribute()
    {
        return $this->jumlah_tabung_penuh + $this->jumlah_tabung_kosong + $this->jumlah_tabung_rusak;
    }
}
