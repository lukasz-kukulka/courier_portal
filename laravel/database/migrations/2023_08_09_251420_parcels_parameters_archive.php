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
        Schema::create('parcels_parameters_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('announcement_id'); // klucz obcy
            $table->unsignedInteger('weight');
            $table->unsignedInteger('length');
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'parcels_parameters_archive' );
    }

};
