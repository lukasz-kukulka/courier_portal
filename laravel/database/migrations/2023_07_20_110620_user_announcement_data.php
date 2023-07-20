<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('user_announcement_data', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('author'); // klucz obcy
            $table->longText( 'announcement_content' );
            $table->string('post_code_pl');
            $table->string('post_code_uk');
            $table->unsignedBigInteger('cargo_type'); // klucz obcy
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_announcement_data');
    }
};
