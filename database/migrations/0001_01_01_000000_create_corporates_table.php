<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corporates', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('document');
            $table->string('email');
            $table->string('phone');
            $table->string('slug');
            $table->string('domain');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corporates');
    }
};
