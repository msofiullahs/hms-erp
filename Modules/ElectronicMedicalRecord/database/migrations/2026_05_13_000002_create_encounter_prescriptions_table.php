<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encounter_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinical_encounter_id')
                ->constrained('clinical_encounters')
                ->cascadeOnDelete();
            $table->string('drug_name');
            $table->string('strength')->nullable();
            $table->string('form')->nullable();
            $table->string('dosage');
            $table->string('route', 16)->nullable();
            $table->string('frequency');
            $table->string('duration')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encounter_prescriptions');
    }
};
