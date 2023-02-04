<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('number_of_rooms')->default(1);
            $table->string('description')->nullable();
            $table->string('door_number')->index();
            $table->boolean('is_available')->default(true);
            $table->unsignedDouble('amount')->default(0);
            $table->string('currency')->default('XOF');
            $table->string('payment_deadline_day')->default('5');
            $table->json('extras')->nullable();
            $table->foreignUuid('real_estate_id')->constrained();

            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
