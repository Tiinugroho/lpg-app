<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        Vendor::create([
            'nama_vendor'   => 'PT Gas Sejahtera',
            'alamat'        => 'Jl. Pancasila No. 1, Pekanbaru',
            'telepon'       => '081234567890',
            'email'         => 'gas@sejahtera.com',
            'kontak_person' => 'Doni Wibowo',
            'status_aktif'  => true,
        ]);

        Vendor::create([
            'nama_vendor'   => 'CV Berkah LPG',
            'alamat'        => 'Jl. Merdeka No. 77, Pekanbaru',
            'telepon'       => '082345678901',
            'email'         => 'berkah@lpg.co.id',
            'kontak_person' => 'Elsi Titasari',
            'status_aktif'  => true,
        ]);
    }
}
