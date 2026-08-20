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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('id_review');

            $table->unsignedBigInteger('id_transaksi');

            $table->unique('id_transaksi');

            $table->foreign('id_transaksi')
                ->references('id_transaksi')
                ->on('transaksis')
                ->onDelete('cascade');

            $table->foreignId('id_pembeli')
                ->constrained('users', 'id_user')
                ->onDelete('cascade');

            $table->foreignId('id_penjual')
                ->constrained('users', 'id_user')
                ->onDelete('cascade');

            $table->integer('rating');

            $table->text('ulasan')->nullable();

            $table->dateTime('tanggal_review')
                ->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
