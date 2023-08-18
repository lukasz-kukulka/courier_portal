<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('courier_announcement_images_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courier_announcement_id'); // klucz obcy
            $table->string('image_name');
            $table->string('image_link');
            $table->string('image_description');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('courier_announcement_images_archive');
    }
};
