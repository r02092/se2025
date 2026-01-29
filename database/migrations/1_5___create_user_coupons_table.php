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
        Schema::create('user_coupons', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->unsignedInteger('coupon_id');
            $table->unsignedInteger('user_id');
            $table->unique(['coupon_id', 'user_id']);
            $table->unsignedBigInteger('key')->unique();
            $table->boolean('is_used')->default(false);
            $table->datetimes();
            $table
                ->foreign('coupon_id')
                ->on('coupons')
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
        Schema::dropIfExists('user_coupons');
    }
};
