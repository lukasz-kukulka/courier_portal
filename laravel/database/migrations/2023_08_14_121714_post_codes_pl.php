<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('post_codes_pl', function (Blueprint $table) {
            $json = app(\App\Http\Controllers\JsonParserController::class)->plPostCodeAction();

            $table->id();
            $table->unsignedBigInteger('author'); // klucz obcy
            foreach( $json as $post_code ) {
                $table->boolean( $post_code )->default( false );
            }
        });
    }

    public function down(): void {
        Schema::dropIfExists( 'post_codes_pl' );
    }
};