<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorits', function (Blueprint $table) {
            $table->id('id_favorit');

            $table->unsignedBigInteger('id_barang');
            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barangs')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamp('created_at')->useCurrent();

            $table->unique(['id_barang', 'id_user']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorits');
    }
};
