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
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->unsignedTinyInteger('provider')->default(0);
            $table->string('login_name');
            $table->unique(['provider', 'login_name']);
            $table->string('password')->nullable();
            $table->unsignedTinyInteger('permission')->default(1);
            $table->string('name');
            $table->string('icon_ext', 4)->nullable();
            $table->unsignedInteger('num_plan_std')->default(0);
            $table->unsignedInteger('num_plan_prm')->default(0);
            $table->unsignedInteger('postal_code')->nullable();
            $table->unsignedInteger('addr_city')->nullable();
            $table->string('addr_detail')->nullable();
            $table->string('totp_secret', 16, true)->nullable();
            $table->binary('totp_iv', 12, true)->nullable();
            $table->binary('totp_tag', 16, true)->nullable();
            $table->unsignedInteger('totp_last_time')->nullable();
            $table->unsignedTinyInteger('totp_counter')->nullable();
            $table->datetimes();
            $table->softDeletesDatetime();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
