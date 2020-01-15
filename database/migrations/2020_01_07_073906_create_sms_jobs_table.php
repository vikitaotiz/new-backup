<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('template');
            $table->unsignedBigInteger('company_detail_id');
            $table->foreign('company_detail_id')->references('id')->on('company_details')->onDelete('cascade');
            $table->unsignedInteger('reminder_period')->nullable()->default(null);
            $table->time('reminder_time_from')->nullable()->default(null);
            $table->time('reminder_time_to')->nullable()->default(null);
            $table->tinyInteger('reminder_type')->nullable()->default(null)->comment('0:None;1:SMS;2:SMS & Email');
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
        Schema::dropIfExists('sms_jobs');
    }
}
