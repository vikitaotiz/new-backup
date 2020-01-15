<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('gender');
            $table->date('date_of_birth');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('profile_photo')->nullable();
            $table->boolean('availability')->default(1);

            $table->string('nhs_number')->nullable();
            $table->string('address')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->longText('gp_details')->nullable();

            $table->string('occupation')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('referral_source')->nullable();
            $table->longText('medication_allergies')->nullable();
            $table->longText('current_medication')->nullable();
            $table->longText('more_info')->nullable();

            $table->unsignedBigInteger('role_id')->default(5);

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('creator_id')->default(2);
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');

            $table->softDeletes();

            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
