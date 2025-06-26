<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendor';

    protected $fillable = [
        'kode_vendor',
        'nama_vendor',
        'alamat',
        'telepon',
        'email',
        'kontak_person',
        'status_aktif',
    ];

    // Event auto generate kode_vendor saat create
    protected static function booted()
    {
        static::creating(function ($vendor) {
            $lastId = static::max('id') + 1;
            $vendor->kode_vendor = 'VND-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);
        });
    }
}
