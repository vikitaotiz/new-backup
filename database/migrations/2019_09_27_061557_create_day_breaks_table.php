<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDayBreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_breaks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->time('from')->nullable()->default(null);
            $table->time('to')->nullable()->default(null);
            $table->unsignedBigInteger('timing_id');
            $table->foreign('timing_id')->references('id')->on('timings')->onDelete('cascade');
            $table->unsignedBigInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_breaks');
    }
}
