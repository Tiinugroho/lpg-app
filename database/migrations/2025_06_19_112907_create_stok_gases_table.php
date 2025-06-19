<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stok_gas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gas')->default('Gas Elpiji 3 Kg');
            $table->integer('jumlah_tabung_penuh')->default(0)->nullable();
            $table->integer('jumlah_tabung_kosong')->default(0)->nullable();
            $table->integer('jumlah_tabung_rusak')->default(0)->nullable();
            $table->decimal('harga_beli_per_tabung', 10, 2);
            $table->decimal('harga_jual_per_tabung', 10, 2);
            $table->date('tanggal_update');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_gas');
    }
};
