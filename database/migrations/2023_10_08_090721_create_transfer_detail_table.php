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
        Schema::create('transfer_detail', function (Blueprint $table) {
            $table->foreignId('transfer_id')
                ->constrained('transfer')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->comment('Receiver user ID')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('date');
            $table->time('time');
            $table->bigInteger('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_detail');
    }
};
