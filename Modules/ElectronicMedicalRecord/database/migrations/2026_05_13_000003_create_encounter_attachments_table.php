<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encounter_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinical_encounter_id')
                ->constrained('clinical_encounters')
                ->cascadeOnDelete();
            $table->string('label');
            $table->string('disk')->default('local');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('size_bytes')->nullable();
            $table->string('uploaded_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encounter_attachments');
    }
};
