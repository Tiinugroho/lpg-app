<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $table = 'vendor';

    protected $fillable = [
        'nama_vendor',
        'nomor_telepon',
        'alamat',
        'email',
        'kontak_person',
        'status_aktif',
        'kode_vendor',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function generateKodeVendor()
    {
        $lastVendor = self::orderBy('created_at', 'desc')->first();
        $nextId = $lastVendor ? $lastVendor->id + 1 : 1;
        return 'VND-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }   
}
