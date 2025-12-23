<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->unsignedInteger('spot_id');
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('rate');
            $table->text('comment');
            $table->unsignedInteger('views');
            $table->ipAddress('ip_addr');
            $table->unsignedSmallInteger('port');
            $table->string('user_agent');
            $table->datetimes();
            $table->softDeletesDatetime();
            $table
                ->foreign('spot_id')
                ->on('spots')
                ->references('id')
                ->cascadeOnDelete();
            $table
                ->foreign('user_id')
                ->on('users')
                ->references('id')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
