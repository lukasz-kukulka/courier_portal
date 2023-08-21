<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('courier_announcement', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('author'); // klucz obcy
            $table->unsignedBigInteger('description')->nullable();
            $table->date('experience_date')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists( 'courier_announcement' );
    }
};
