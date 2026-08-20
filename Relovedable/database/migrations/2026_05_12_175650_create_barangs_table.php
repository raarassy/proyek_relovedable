<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barang');

            $table->unsignedBigInteger('id_toko');

            $table->foreign('id_toko')
                ->references('id_toko')
                ->on('tokos')
                ->onDelete('cascade');

            $table->string('nama_barang', 100);
            $table->string('kategori', 50);
            $table->integer('harga');
            $table->text('deskripsi');
            $table->enum('kondisi', ['baru', 'seperti_baru', 'bekas']);
            $table->boolean('metode_transaksi')->default(false); // false = kirim, true = COD tersedia
            $table->enum('status_barang', ['tersedia', 'terjual', 'nonaktif'])->default('tersedia');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};