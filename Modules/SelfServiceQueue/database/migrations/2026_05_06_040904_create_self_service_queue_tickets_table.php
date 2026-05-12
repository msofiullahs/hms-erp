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
        Schema::create('self_service_queue_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('customer_name')->nullable();
            $table->string('kiosk_id')->nullable();
            $table->string('service_type')->default('general');
            $table->string('counter')->nullable();
            $table->string('status')->default('waiting');
            $table->string('qr_code')->nullable();
            $table->string('barcode_data')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_service_queue_tickets');
    }
};
