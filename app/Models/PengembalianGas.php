<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengembalianGas extends Model
{
    use HasFactory;

    protected $table = 'pengembalian_gas';

    protected $fillable = ['kode', 'nama_pembeli', 'no_kk', 'no_telp', 'produk_id', 'kondisi_rusak', 'jumlah', 'jumlah_rusak', 'tanggal_pengembalian', 'keterangan'];

    public function stok()
    {
        return $this->belongsTo(StokGas::class, 'produk_id');
    }

    protected static function booted()
    {
        static::creating(function ($pengembalian) {
            $lastId = static::max('id') + 1;
            $pengembalian->kode = 'RET-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);
        });
    }
}
