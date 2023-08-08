<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('humans_parameters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('announcement_id'); // klucz obcy
            $table->unsignedInteger('kids')->nullable();
            $table->unsignedInteger('adult')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'humans_parameters' );
    }

};
