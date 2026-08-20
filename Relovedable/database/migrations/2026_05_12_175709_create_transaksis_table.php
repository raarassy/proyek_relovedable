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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');

            $table->unsignedBigInteger('id_barang');

            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barangs')
                ->onDelete('cascade');

            $table->foreignId('id_pembeli')
                ->constrained('users', 'id_user')
                ->onDelete('cascade');

            $table->foreignId('id_penjual')
                ->constrained('users', 'id_user')
                ->onDelete('cascade');

            $table->dateTime('tanggal_transaksi')
                ->useCurrent();

            $table->enum('status_transaksi', [
                'pending',
                'dibayar',
                'dikirim',
                'selesai',
                'dibatalkan'
            ])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
