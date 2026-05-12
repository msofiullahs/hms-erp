<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_record_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained('medical_records')->cascadeOnDelete();
            $table->string('visit_number', 32)->index();
            $table->dateTime('visit_date');
            $table->string('visit_type')->default('rawat_jalan');
            $table->string('department')->nullable();
            $table->string('doctor')->nullable();
            $table->string('chief_complaint')->nullable();
            $table->text('diagnosis')->nullable();
            $table->string('icd10_code', 16)->nullable()->index();
            $table->string('status')->default('active');
            $table->string('source')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->timestamps();

            $table->unique(['medical_record_id', 'visit_number']);
            $table->index(['visit_date']);
            $table->index(['source', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_record_visits');
    }
};
