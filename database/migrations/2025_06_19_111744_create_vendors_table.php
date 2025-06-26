<?php

use App\Models\Vendor;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor', function (Blueprint $table) {
            $table->id();
            $table->string('kode_vendor')->unique()->default(function () {
                return 'VND-' . str_pad((Vendor::max('id') + 1), 5, '0', STR_PAD_LEFT);
            });
            $table->string('nama_vendor');
            $table->text('alamat');
            $table->string('telepon');
            $table->string('email')->nullable();
            $table->string('kontak_person');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor');
    }
};
