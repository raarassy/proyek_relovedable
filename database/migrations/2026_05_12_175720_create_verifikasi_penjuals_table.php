<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifikasi_penjuals', function (Blueprint $table) {
            $table->id('id_verif_pen');

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('nik');
            $table->string('foto_ktp');
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending');

            // data toko yang diajukan, dipakai saat approve
            $table->string('nama_toko', 100);
            $table->string('alamat_toko')->nullable();
            $table->text('deskripsi_toko')->nullable();

            $table->dateTime('tanggal_verifikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi_penjuals');
    }
};
