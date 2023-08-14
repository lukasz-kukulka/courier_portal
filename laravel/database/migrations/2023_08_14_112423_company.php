<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author'); // klucz obcy
            $table->string('company_name');
            $table->string('company_address');
            $table->string('company_post_code');
            $table->string('company_city');
            $table->string('company_country');
            $table->string('company_phone_number', 15 );
            $table->string('company_register_link')->nullable();
            $table->boolean('confirmed')->default( false );
        });
    }

    public function down(): void {
        Schema::dropIfExists( 'company' );
    }
};
