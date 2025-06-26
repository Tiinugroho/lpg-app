<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPembelian extends Model
{
    use HasFactory;
    protected $table = 'transaksi_pembelian';

    protected $fillable = [
        'kode_transaksi',
        'nama_pembeli',
        'nomor_telepon',
        'alamat',
        'jenis_pembayaran', // tunai, transfer
        'tanggal_transaksi',
        'jumlah_tabung',
        'harga_satuan',
        'total_harga',
        'jumlah_bayar',
        'kembalian',
        'status_transaksi', // selesai, batal
        'catatan'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    public static function generateKodeTransaksi()
    {
        $lastTransaksi = self::orderBy('created_at', 'desc')->first();
        $nextId = $lastTransaksi ? $lastTransaksi->id + 1 : 1;
        return 'TRX-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
}
