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
        Schema::create('post_codes_pl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author'); // klucz obcy
            $table->boolean('05-XXX')->default( false );
            // $table->boolean('06-XXX')->default( false );
            // $table->boolean('07-XXX')->default( false );
            // $table->boolean('08-XXX')->default( false );
            // $table->boolean('09-XXX')->default( false );
            // $table->boolean('11-XXX')->default( false );
            // $table->boolean('12-XXX')->default( false );
            // $table->boolean('13-XXX')->default( false );
            // $table->boolean('14-XXX')->default( false );
            // $table->boolean('16-XXX')->default( false );
            // $table->boolean('17-XXX')->default( false );
            // $table->boolean('18-XXX')->default( false );
            // $table->boolean('21-XXX')->default( false );
            // $table->boolean('22-XXX')->default( false );
            // $table->boolean('23-XXX')->default( false );
            // $table->boolean('24-XXX')->default( false );
            // $table->boolean('26-XXX')->default( false );
            // $table->boolean('27-XXX')->default( false );
            // $table->boolean('28-XXX')->default( false );
            // $table->boolean('29-XXX')->default( false );
            // $table->boolean('32-XXX')->default( false );
            // $table->boolean('33-XXX')->default( false );
            // $table->boolean('34-XXX')->default( false );
            // $table->boolean('36-XXX')->default( false );
            // $table->boolean('37-XXX')->default( false );
            // $table->boolean('38-XXX')->default( false );
            // $table->boolean('39-XXX')->default( false );
            // $table->boolean('42-XXX')->default( false );
            // $table->boolean('43-XXX')->default( false );
            // $table->boolean('44-XXX')->default( false );
            // $table->boolean('46-XXX')->default( false );
            // $table->boolean('47-XXX')->default( false );
            // $table->boolean('48-XXX')->default( false );
            // $table->boolean('49-XXX')->default( false );
            // $table->boolean('55-XXX')->default( false );
            // $table->boolean('56-XXX')->default( false );
            // $table->boolean('57-XXX')->default( false );
            // $table->boolean('58-XXX')->default( false );
            // $table->boolean('59-XXX')->default( false );
            // $table->boolean('62-XXX')->default( false );
            // $table->boolean('63-XXX')->default( false );
            // $table->boolean('64-XXX')->default( false );
            // $table->boolean('66-XXX')->default( false );
            // $table->boolean('67-XXX')->default( false );
            // $table->boolean('68-XXX')->default( false );
            // $table->boolean('69-XXX')->default( false );
            // $table->boolean('72-XXX')->default( false );
            // $table->boolean('73-XXX')->default( false );
            // $table->boolean('74-XXX')->default( false );
            // $table->boolean('76-XXX')->default( false );
            // $table->boolean('77-XXX')->default( false );
            // $table->boolean('78-XXX')->default( false );
            // $table->boolean('82-XXX')->default( false );
            // $table->boolean('83-XXX')->default( false );
            // $table->boolean('84-XXX')->default( false );
            // $table->boolean('86-XXX')->default( false );
            // $table->boolean('87-XXX')->default( false );
            // $table->boolean('88-XXX')->default( false );
            // $table->boolean('89-XXX')->default( false );
            // $table->boolean('95-XXX')->default( false );
            // $table->boolean('96-XXX')->default( false );
            // $table->boolean('97-XXX')->default( false );
            // $table->boolean('98-XXX')->default( false );
            // $table->boolean('99-XXX')->default( false );

        });
    }

    public function down(): void {
        Schema::dropIfExists( 'post_codes_pl' );
    }
};
