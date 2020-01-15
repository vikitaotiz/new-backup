<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitialConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('initial_consultations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('complain')->nullable();
            $table->longText('history_presenting_complaint')->nullable();
            $table->longText('past_medical_history')->nullable();
            $table->longText('drug_history')->nullable();
            $table->longText('social_history')->nullable();
            $table->longText('family_history')->nullable();
            $table->longText('drug_allergies')->nullable();
            $table->longText('examination')->nullable();
            $table->longText('diagnosis')->nullable();
            $table->longText('treatment')->nullable();

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
        Schema::dropIfExists('initial_consultations');
    }
}
