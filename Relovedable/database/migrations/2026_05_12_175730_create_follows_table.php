<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id('id_follow');

            $table->unsignedBigInteger('id_pengikut');
            $table->foreign('id_pengikut')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_diikuti');
            $table->foreign('id_diikuti')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamp('created_at')->useCurrent();

            $table->unique(['id_pengikut', 'id_diikuti']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
