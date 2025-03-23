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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_id')->constrained('medical_records')->onDelete('cascade');
            $table->foreignId('service_record_id')->constrained('service_record')->onDelete('cascade');
            $table->foreignId('prescription_id')->constrained('prescriptions')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['Tiền mặt', 'Chuyển khoản'])->default('Tiền mặt');
            $table->tinyInteger('status')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};