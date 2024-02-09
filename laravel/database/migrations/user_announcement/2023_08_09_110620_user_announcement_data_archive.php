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
            $table->string('direction_sending');
            $table->string('post_code_prefix_sending');
            $table->string('post_code_postfix_sending');
            $table->string('city_sending');
            $table->string('direction_receiving');
            $table->string('post_code_prefix_receiving');
            $table->string('post_code_postfix_receiving');
            $table->string('city_receiving');
            $table->string('phone_number', 15 );
            $table->string('email');
            $table->date('expect_sending_date');
            $table->date('experience_date');
            $table->string('title');
            $table->string('additional_info')->nullable();
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