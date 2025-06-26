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
        Schema::create('pengembalian_tabung', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengembalian')->unique();
            $table->string('nama_pelanggan');
            $table->string('nomor_telepon')->nullable();
            $table->string('alamat')->nullable();
            $table->enum('jenis_pengembalian', ['tukar', 'kembali'])->default('tukar'); // tukar, kembali
            $table->datetime('tanggal_pengembalian');
            $table->integer('jumlah_tabung_dikembalikan');
            $table->enum('kondisi_tabung', ['baik', 'rusak'])->default('baik');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian_tabung');
    }
};
