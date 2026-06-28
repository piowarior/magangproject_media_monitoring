<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('device')->nullable();         // Android, iOS, dll
            $table->string('user_agent')->nullable();     // info browser/app
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->timestamp('login_time');
            $table->timestamps();

            $table->index(['user_id', 'login_time']); // query riwayat login cepat
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};
