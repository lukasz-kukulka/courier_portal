<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('cargo_types_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courier_announcement_id'); // klucz obcy
            $table->string('cargo_name');
            $table->unsignedInteger('cargo_price');
            $table->string('cargo_description');
            $table->string('currency');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('cargo_types_archive');
    }
};