<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Root Admin',
            'description' => 'Overall System Administrator',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('roles')->insert([
            'name' => 'Administrator',
            'description' => 'Daily System Administrator',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('roles')->insert([
            'name' => 'Doctor',
            'description' => 'Treats Patients',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('roles')->insert([
            'name' => 'Staff',
            'description' => 'Facilitates Office Activities',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('roles')->insert([
            'name' => 'Patient',
            'description' => 'Can be treated and book appintments',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('roles')->insert([
            'name' => 'Company Owner',
            'description' => 'Overall System Administrator Of The Company',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
