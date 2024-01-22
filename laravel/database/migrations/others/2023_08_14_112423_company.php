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
            $table->foreign('author')->references('id')->on('users');
            $table->string('company_name')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_post_code')->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_country')->nullable();
            $table->string('company_phone_number', 15 )->nullable();
            $table->string('company_register_link')->nullable();
            $table->boolean('confirmed')->default( false );
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::table('company', function (Blueprint $table) {
            $table->dropForeign(['author']);
            $table->dropColumn('author');
        });
    }
};