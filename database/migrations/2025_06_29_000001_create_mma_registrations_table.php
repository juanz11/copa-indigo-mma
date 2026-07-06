<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mma_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('id_number', 50);
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->string('social_media')->nullable();
            $table->string('ticket_type', 50);
            $table->integer('quantity')->default(1);
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_proof')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mma_registrations');
    }
};
