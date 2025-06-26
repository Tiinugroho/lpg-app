<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengembalianTabung extends Model
{
    use HasFactory;

    protected $table = 'pengembalian_tabung';
    
    protected $fillable = [
        'kode_pengembalian',
        'nama_pelanggan',
        'nomor_telepon',
        'alamat',
        'jenis_pengembalian', // tukar, kembali
        'tanggal_pengembalian',
        'jumlah_tabung_dikembalikan',
        'kondisi_tabung', // baik, rusak
        'catatan'
    ];

    protected $casts = [
        'tanggal_pengembalian' => 'datetime',
    ];

    public static function generateKodePengembalian()
    {
        $lastPengembalian = self::orderBy('created_at', 'desc')->first();
        $nextId = $lastPengembalian ? $lastPengembalian->id + 1 : 1;
        return 'PB-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
}
