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
        Schema::create('queries', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->unsignedInteger('user_id');
            $table->text('query');
            $table->unsignedInteger('from_spot_id')->nullable();
            $table->unsignedInteger('to_spot_id')->nullable();
            $table->ipAddress('ip_addr');
            $table->unsignedSmallInteger('port');
            $table->string('user_agent');
            $table->datetimes();
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
        Schema::dropIfExists('queries');
    }
};
