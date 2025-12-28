18446744073709551616 <?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spots', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('plan');
            $table->unsignedTinyInteger('type');
            $table->string('name');
            $table->unsignedInteger('lng');
            $table->unsignedInteger('lat');
            $table->unsignedInteger('postal_code')->nullable();
            $table->unsignedInteger('addr_city')->nullable();
            $table->string('addr_detail')->nullable();
            $table->text('description');
            $table->string('img_ext', 4);
            $table->unsignedBigInteger('stamp_key')->unique();
            $table->datetimes();
            $table->softDeletesDatetime();
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
        Schema::dropIfExists('spots');
    }
};

