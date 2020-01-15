<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'firstname' => 'Root',
            'lastname' => 'User',
            'email' => 'rootuser@hospitalnote.com',
            'email_verified_at' => now(),
            'phone' => '789654',
            'gender' => 'male',
            'date_of_birth' => '1999-07-22 00:00:00',
            'role_id' => 1,
            'creator_id' => 1,
            'password' => bcrypt('adminRoot@1990'),
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('users')->insert([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'adminuser@hospitalnote.com',
            'email_verified_at' => now(),
            'phone' => '789629',
            'gender' => 'male',
            'date_of_birth' => '1999-07-22 00:00:00',
            'role_id' => 2,
            'creator_id' => 1,
            'user_id' => 1,
            'password' => bcrypt('adminRoot@2000'),
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('users')->insert([
            'firstname' => 'Abdulkadir',
            'lastname' => 'Ali',
            'email' => 'walkinclinicenquiries@gmail.com',
            'email_verified_at' => now(),
            'phone' => '789629',
            'gender' => 'male',
            'date_of_birth' => '1999-07-22 00:00:00',
            'role_id' => 2,
            'creator_id' => 1,
            'user_id' => 1,
            'password' => bcrypt('adminRoot@2000'),
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('users')->insert([
            'firstname' => 'Doctor',
            'lastname' => 'User',
            'email' => 'doctoruser@hospitalnote.com',
            'email_verified_at' => now(),
            'phone' => '789630',
            'gender' => 'male',
            'date_of_birth' => '1999-07-22 00:00:00',
            'role_id' => 3,
            'creator_id' => 1,
            'user_id' => 1,
            'password' => bcrypt('adminRoot@2003'),
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('users')->insert([
            'firstname' => 'Staff',
            'lastname' => 'User',
            'email' => 'staffuser@hospitalnote.com',
            'email_verified_at' => now(),
            'phone' => '789631',
            'gender' => 'male',
            'date_of_birth' => '1999-07-22 00:00:00',
            'role_id' => 4,
            'creator_id' => 1,
            'user_id' => 1,
            'password' => bcrypt('staff@2005'),
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('users')->insert([
            'firstname' => 'Patient',
            'lastname' => 'User',
            'email' => 'patientuser@hospitalnote.com',
            'email_verified_at' => now(),
            'phone' => '789633',
            'gender' => 'male',
            'date_of_birth' => '1999-07-22 00:00:00',
            'role_id' => 5,
            'creator_id' => 1,
            'user_id' => 1,
            'password' => bcrypt('patient@2019'),
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
