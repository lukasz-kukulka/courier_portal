<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('company_announcement_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courier_announcement_id'); // klucz obcy
            $table->string('images_name');
            $table->string('images_link');
            $table->string('images_description');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('company_announcement_images');
    }
};
