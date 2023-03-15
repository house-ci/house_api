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
        Schema::create('real_estates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('number_of_floor')->nullable()->default(1);
            $table->string('lot')->nullable();
            $table->string('i_lot')->nullable();
            $table->string('block')->nullable();
            $table->foreignUuid('city_id')->constrained();
            $table->foreignUuid('property_type_id')->constrained();
            $table->foreignUuid('owner_id')->constrained();

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
        Schema::dropIfExists('real_estates');
    }
};
