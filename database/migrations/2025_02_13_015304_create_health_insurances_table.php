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
        Schema::create('health_insurances', function (Blueprint $table) {
            $table->id(); 
            $table->string('card_number', 15)->unique(); 
            $table->unsignedBigInteger('patient_id')->nullable(); 
            $table->date('issue_date'); 
            $table->date('expiry_date'); 
            $table->string('insurance_type', 50); 
            $table->decimal('coverage_rate', 5, 2)->default(80.00); 
            $table->tinyInteger('status')->default(1); 
            
            // Khóa ngoại liên kết với bảng patients
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_insurances');
    }
};