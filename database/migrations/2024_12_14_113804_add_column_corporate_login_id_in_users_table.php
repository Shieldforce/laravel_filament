<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->unsignedBigInteger('corporate_latest_id')->nullable();
            $table->foreign('corporate_latest_id')
                  ->references('id')
                  ->on('corporates')
                  ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('corporate_latest_id');
        });
    }
};
