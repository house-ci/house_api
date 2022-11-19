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
        Schema::create('leasings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTimeTz('started_on');
            $table->dateTimeTz('ended_on');
            $table->integer('amount');
            $table->string('currency')->index()->default('XOF');
            $table->string('payment_deadline_day');
            $table->string('agreement_url')->nullable();
            $table->boolean('is_active');
            $table->foreignUuid('asset_id');
            $table->foreignUuid('tenant_id');

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
        Schema::dropIfExists('leasings');
    }
};
