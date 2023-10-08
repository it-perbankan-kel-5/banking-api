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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 12);
            $table->string('last_name', 32);
            $table->string('email', 128)->unique();
            $table->char('phone_number', 12)->nullable(false);
            $table->string('password', 60)->nullable(false);
            $table->string('address', 128)->nullable(false);
            $table->char('card_number', 19)->nullable(false);
            $table->char('card_pin', 6)->nullable(false);
            $table->bigInteger('balance')->nullable(false);
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
