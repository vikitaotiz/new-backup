<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendSmsToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->tinyInteger('send_sms')->after('progress')->nullable()->default(0)->comment('0:Overdue;1:Not sent;2:Sent');
            $table->tinyInteger('send_mail')->after('send_sms')->nullable()->default(0)->comment('0:Overdue;1:Not sent;2:Sent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('send_sms');
            $table->dropColumn('send_mail');
        });
    }
}
