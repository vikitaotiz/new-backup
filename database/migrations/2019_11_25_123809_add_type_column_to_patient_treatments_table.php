<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeColumnToPatientTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('patient_treatments') && !Schema::hasColumn('patient_treatments', 'type')) {                
            Schema::table('patient_treatments', function (Blueprint $table) {
                $table->string('type')->nullable()->default('note'); // note, letter
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
        if (Schema::hasTable('patient_treatments') && Schema::hasColumn('patient_treatments', 'type')) {                
            Schema::table('patient_treatments', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
}
