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
        Schema::create('rents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('label')->index();
            $table->string('month');
            $table->string('year');
            $table->string('status')->default('PENDING');
            $table->integer('amount');
            $table->integer('amount_paid')->default(0);
            $table->string('currency')->default('XOF');
            $table->dateTimeTz('paid_at')->nullable();
            $table->foreignUuid('leasing_id')->constrained();

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rents');
    }
};
