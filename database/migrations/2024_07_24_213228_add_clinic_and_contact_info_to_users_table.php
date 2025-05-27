<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClinicAndContactInfoToUsersTable extends Migration
{
    /**
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('website')->nullable();
            $table->string('specialties')->nullable();
            $table->string('business_license')->nullable();
            $table->string('tax_identification')->nullable();
            $table->string('operating_hours')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_position')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->boolean('approve_user')->default(0);
        });
    }

    /**
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'website',
                'specialties',
                'business_license',
                'tax_identification',
                'operating_hours',
                'contact_name',
                'contact_position',
                'contact_phone',
                'contact_email',
                
            ]);
        });
    }
}
