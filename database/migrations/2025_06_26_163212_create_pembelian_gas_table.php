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
        Schema::create('pembelian_gas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembelian');
            $table->foreignId('produk_id')->constrained('stok_gas')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendor')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_beli', 12, 2);
            $table->date('tanggal_masuk');
            $table->string('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_gas');
    }
};
