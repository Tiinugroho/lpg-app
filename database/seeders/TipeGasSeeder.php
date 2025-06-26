<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipeGas;

class TipeGasSeeder extends Seeder
{
    public function run(): void
    {
        TipeGas::create(['nama' => 'Gas LPG 3 Kg']);
        TipeGas::create(['nama' => 'Gas LPG 12 Kg']);
        TipeGas::create(['nama' => 'Gas Bright 5.5 Kg']);
        TipeGas::create(['nama' => 'Gas Bright 12 Kg']);
    }
}
