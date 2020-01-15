<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPatientTreatmentIdToPatientTreatmentNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patient_treatment_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_treatment_id')->after('answer');
            $table->foreign('patient_treatment_id')->references('id')->on('patient_treatments')->onDelete('cascade');
            $table->dropColumn('group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_treatment_notes', function (Blueprint $table) {
            //
        });
    }
}
