<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vitals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('temperature')->nullable();
            $table->string('pulse_rate')->nullable();
            $table->string('systolic_bp')->nullable();
            $table->string('diastolic_bp')->nullable();
            $table->string('respiratory_rate')->nullable();
            $table->string('oxygen_saturation')->nullable();
            $table->string('o2_administered')->nullable();
            $table->string('pain')->nullable();
            $table->string('head_circumference')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('company_id');

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
        Schema::dropIfExists('vitals');
    }
}
