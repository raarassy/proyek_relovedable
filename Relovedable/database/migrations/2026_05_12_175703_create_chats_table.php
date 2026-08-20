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
        Schema::create('chats', function (Blueprint $table) {
            $table->id('id_chat');

            $table->foreignId('id_pengirim')
                ->constrained('users', 'id_user')
                ->onDelete('cascade');

            $table->foreignId('id_penerima')
                ->constrained('users', 'id_user')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_barang');

            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barangs')
                ->onDelete('cascade');

            $table->text('pesan');

            $table->dateTime('waktu_kirim')
                ->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
