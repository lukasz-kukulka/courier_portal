<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('user_announcement_data_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author'); // klucz obcy
            $table->unsignedInteger('priority')->nullable();
            $table->string('direction');
            $table->string('post_code_sending');
            $table->string('post_code_receiving');
            $table->string('phone_number', 15 );
            $table->string('email');
            $table->date('expect_sending_date');
            $table->date('experience_date');
            $table->string('title');
            $table->longText( 'order_description_short' );
            $table->longText( 'order_description_long' );
            $table->unsignedInteger('parcels_quantity')->nullable();
            $table->unsignedInteger('humans_quantity')->nullable();
            $table->unsignedInteger('pallets_quantity')->nullable();
            $table->unsignedInteger('animals_quantity')->nullable();
            $table->unsignedInteger('others_quantity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_announcement_data_archive');
    }
};
