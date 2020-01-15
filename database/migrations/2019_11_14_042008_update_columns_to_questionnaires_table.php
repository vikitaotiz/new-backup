<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnsToQuestionnairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('questionnaires') && !Schema::hasColumn('questionnaires', 'yes_more_info')) {                
            Schema::table('questionnaires', function (Blueprint $table) {
                $table->string('yes_more_info')->nullable();
            });
        }
        if (Schema::hasTable('questionnaires') && !Schema::hasColumn('questionnaires', 'no_more_info')) {                
            Schema::table('questionnaires', function (Blueprint $table) {
                $table->string('no_more_info')->nullable();
            });
        }
        if (Schema::hasTable('questionnaires') && Schema::hasColumn('questionnaires', 'more_info')) {                
            Schema::table('questionnaires', function (Blueprint $table) {
                $table->dropColumn('more_info');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('questionnaires') && Schema::hasColumn('questionnaires', 'yes_more_info')) {                
            Schema::table('questionnaires', function (Blueprint $table) {
                $table->dropColumn('yes_more_info');
            });
        }
        if (Schema::hasTable('questionnaires') && Schema::hasColumn('questionnaires', 'no_more_info')) {
            Schema::table('questionnaires', function (Blueprint $table) {
                $table->dropColumn('no_more_info');
            });
        }
        if (Schema::hasTable('questionnaires') && !Schema::hasColumn('questionnaires', 'more_info')) {                
            Schema::table('questionnaires', function (Blueprint $table) {
                $table->string('more_info')->nullable();
            });
        }
    }
}
