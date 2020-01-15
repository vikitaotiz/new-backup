<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_details')->insert([
            'name' => 'Mediclinic',
            'email' => 'mediclinic@hospitalnote.com',
            'uuid' => Str::uuid()->toString(),
            'phone' => '789654',
            'address' => 'Main Street 1234',
            'industry' => 'Health',
            'more_info' => 'Test Company',
            'logo' => 'noimage.png',
            'user_id' => 1,
            'status' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
