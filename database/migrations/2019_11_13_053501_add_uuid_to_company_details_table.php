<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;


class AddUuidToCompanyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_details', function (Blueprint $table) {
            if (Schema::hasTable('company_details') && !Schema::hasColumn('company_details', 'uuid')) {                
                Schema::table('company_details', function (Blueprint $table) {
                    $table->string('uuid')->nullable()->after('id');
                });
                $company_details = App\CompanyDetail::get();
                foreach ($company_details as $company) {
                    $company->uuid = Str::uuid()->toString();
                    $company->save();
                }
                Schema::table('company_details', function (Blueprint $table) {
                    $table->string('uuid')->nullable(false)->unique()->change();
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('company_details') && Schema::hasColumn('company_details', 'uuid')) {
            Schema::table('company_details', function (Blueprint $table) {
                $table->dropColumn('uuid');
            });            
        }
    }
}
