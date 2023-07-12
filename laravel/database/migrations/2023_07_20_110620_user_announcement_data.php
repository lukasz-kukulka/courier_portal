<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('user_announcement_data', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('author'); // klucz obcy
            $table->longText( 'order_description' )->nullable();
            $table->string('direction');
            $table->string('post_code_sending');
            $table->string('post_code_receiving');
            $table->string('phone_number', 15 );
            $table->string('email');
            $table->unsignedBigInteger('cargo_parameters'); // klucz obcy
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_announcement_data');
    }
};
