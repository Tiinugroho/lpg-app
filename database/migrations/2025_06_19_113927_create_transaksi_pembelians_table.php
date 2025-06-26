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
        Schema::create('transaksi_pembelian', function (Blueprint $table) {
             $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('nama_pembeli');
            $table->string('nomor_telepon')->nullable();
            $table->string('alamat')->nullable();
            $table->enum('jenis_pembayaran', ['tunai', 'transfer'])->default('tunai'); // tukar, kembali
            $table->datetime('tanggal_transaksi');
            $table->integer('jumlah_tabung');
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('total_harga', 10, 2);
            $table->decimal('jumlah_bayar', 10, 2);
            $table->decimal('kembalian', 10, 2);
            $table->enum('status_transaksi', ['selesai', 'batal'])->default('selesai');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembelian');
    }
};
