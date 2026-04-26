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
        Schema::connection('mongodb')->create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('project_id')->nullable();
            $table->string('client_id')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_avatar')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('IDR');
            $table->string('payment_number')->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable(); // transfer, card, e-wallet
            $table->timestamp('payment_date')->nullable();
            $table->string('proof_file_url')->nullable();
            $table->string('proof_file_type')->nullable(); // pdf, jpg, png, etc
            $table->text('notes_from_umkm')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('verified_by')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('payments');
    }
};
