<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('patient_number')->nullable();
            $table->string('department');
            $table->string('doctor')->nullable();
            $table->date('admission_date');
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_registrations');
    }
};
