<?php

namespace Database\Seeders;

use App\Models\StokGas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StokGasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_gas' => 'Elpiji 3 Kg',
                'jumlah_tabung_penuh' => 50,
                'jumlah_tabung_kosong' => 30,
                'jumlah_tabung_rusak' => 5,
                'harga_beli_per_tabung' => 16000,
                'harga_jual_per_tabung' => 18000,
                'tanggal_update' => Carbon::now(),
            ],
            [
                'nama_gas' => 'Elpiji 12 Kg',
                'jumlah_tabung_penuh' => 20,
                'jumlah_tabung_kosong' => 10,
                'jumlah_tabung_rusak' => 2,
                'harga_beli_per_tabung' => 140000,
                'harga_jual_per_tabung' => 150000,
                'tanggal_update' => Carbon::now(),
            ],
            [
                'nama_gas' => 'Bright Gas 5.5 Kg',
                'jumlah_tabung_penuh' => 15,
                'jumlah_tabung_kosong' => 8,
                'jumlah_tabung_rusak' => 1,
                'harga_beli_per_tabung' => 70000,
                'harga_jual_per_tabung' => 75000,
                'tanggal_update' => Carbon::now(),
            ],
        ];

        foreach ($data as $item) {
            StokGas::create($item);
        }
    }
}
