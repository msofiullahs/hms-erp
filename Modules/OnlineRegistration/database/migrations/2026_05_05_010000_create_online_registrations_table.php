<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('online_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('department')->nullable();
            $table->date('visit_date');
            $table->text('notes')->nullable();
            $table->string('otp_code', 6)->nullable();
            $table->timestamp('otp_sent_at')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('online_registrations');
    }
};
