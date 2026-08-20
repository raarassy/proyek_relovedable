<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ===== barangs: dua metode independen — COD &/atau Ekspedisi =====
        Schema::table('barangs', function (Blueprint $table) {
            $table->boolean('bisa_cod')->default(false)->after('kondisi');
            $table->boolean('bisa_ekspedisi')->default(true)->after('bisa_cod');
        });

        // Pertahankan data lama: metode_transaksi (true = COD) -> bisa_cod
        if (Schema::hasColumn('barangs', 'metode_transaksi')) {
            DB::table('barangs')->where('metode_transaksi', true)->update(['bisa_cod' => true]);

            Schema::table('barangs', function (Blueprint $table) {
                $table->dropColumn('metode_transaksi');
            });
        }

        // ===== transaksis: metode yang dipakai pada transaksi =====
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('metode', ['cod', 'ekspedisi'])->nullable()->after('status_transaksi');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->boolean('metode_transaksi')->default(false)->after('kondisi');
        });

        DB::table('barangs')->where('bisa_cod', true)->update(['metode_transaksi' => true]);

        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['bisa_cod', 'bisa_ekspedisi']);
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('metode');
        });
    }
};
