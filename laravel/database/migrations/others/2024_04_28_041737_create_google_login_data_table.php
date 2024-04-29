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
        Schema::create('google_login_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // klucz obcy
            $table->string('google_id')->nullable();
            $table->string('google_email')->nullable();
            $table->string('google_name')->nullable();
            $table->string('google_family_name')->nullable();
            $table->bool('email_verified')->nullable();
            $table->bool('account_merged')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_login_data');
    }
};