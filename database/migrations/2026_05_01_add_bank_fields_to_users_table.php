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
        Schema::connection('mongodb')->table('users', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->comment('Nama bank untuk pencairan');
            $table->string('bank_account_number')->nullable()->comment('Nomor rekening');
            $table->string('bank_account_name')->nullable()->comment('Nama pemilik rekening');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->table('users', function (Blueprint $table) {
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_account_number');
            $table->dropColumn('bank_account_name');
        });
    }
};
