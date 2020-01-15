<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivacyPolicyCommunicationPreferenceToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('communication_preference')->after('slot')->nullable()->default(null)->comment('0:Email;1:SMS;2:SMS & Email');
            $table->tinyInteger('privacy_policy')->after('communication_preference')->nullable()->default(null)->comment('0:No response;1:Accepted;2:Rejected');
            $table->text('patient_note')->after('privacy_policy')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('communication_preference');
            $table->dropColumn('privacy_policy');
            $table->dropColumn('patient_note');
        });
    }
}
