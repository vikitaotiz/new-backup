<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreInfoToQuestionnairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->text('more_info')->after('generic_comment')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('questionnaires') && Schema::hasColumn('questionnaires', 'more_info')) {
            Schema::table('questionnaires', function (Blueprint $table) {
                $table->dropColumn('more_info');
            });            
        }
    }
}
