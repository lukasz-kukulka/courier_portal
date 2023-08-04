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
        Schema::create('animals_parameters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('announcement_id'); // klucz obcy
            $table->string('animal_type');
            $table->unsignedInteger('weight');
            $table->longText( 'animal_description' )->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'animals_parameters' );
    }

};
