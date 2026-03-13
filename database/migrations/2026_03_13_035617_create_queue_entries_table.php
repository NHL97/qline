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
        Schema::create('queue_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('wa_id', 20)->nullable();
            $table->integer('ticket_number');
            $table->string('ticket_code', 10);
            $table->enum('status', ['waiting', 'called', 'serving', 'done', 'skipped', 'cancelled'])
                ->default('waiting');
            $table->enum('source', ['whatsapp', 'manual'])->default('whatsapp');
            $table->integer('position');
            $table->datetime('joined_at');
            $table->datetime('called_at')->nullable();
            $table->datetime('served_at')->nullable();
            $table->datetime('done_at')->nullable();
            $table->integer('wait_minutes')->nullable();
            $table->integer('service_minutes')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'status']);
            $table->index(['wa_id', 'business_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_entries');
    }
};
