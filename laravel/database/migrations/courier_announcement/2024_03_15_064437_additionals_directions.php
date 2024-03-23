<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('courier_announcement_additional_directions', function (Blueprint $table) {
            $json = app(\App\Http\Controllers\JsonParserController::class)->directionsAction();

            $table->id();
            $table->unsignedBigInteger('courier_announcement_id'); // klucz obcy
            foreach( $json as $dir ) {
                $table->boolean( $dir['name'] )->default( false );
            }
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('courier_announcement_additional_directions');
    }
};