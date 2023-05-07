<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('pay_id');
            $table->dateTimeTz('paid_on')->nullable();
            $table->dateTimeTz('paid_at')->nullable();
            $table->string('paid_by');
            $table->integer('amount');
            $table->uuid('rent_id')->nullable();
            $table->index(['rent_id']);

            $table->timestamps();
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
        Schema::dropIfExists('payments');
    }
};
