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
        Schema::create('coupons', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->unsignedInteger('spot_id');
            $table->string('name');
            $table->unsignedInteger('cond_spot_id')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->datetimes();
            $table->softDeletesDatetime();
            $table
                ->foreign('spot_id')
                ->on('spots')
                ->references('id')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
