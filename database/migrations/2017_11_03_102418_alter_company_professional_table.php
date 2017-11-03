<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyProfessionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_professional', function (Blueprint $table) {

            $table->boolean('requested_by_professional')->default(0)->after('professional_id');

            //new permissions
            $table->boolean('clients_show')->default(0)->after('is_public');
            $table->boolean('clients_edit')->default(0)->afer('clients_show');
            $table->boolean('calendar_show')->default(0)->after('clients_edit');
            $table->boolean('calendar_edit')->default(0)->afer('calendar_show');
            $table->boolean('photos_show')->default(0)->after('calendar_edit');
            $table->boolean('photos_edit')->default(0)->afer('photos_show');
            $table->boolean('plans_show')->default(0)->after('photos_edit');
            $table->boolean('plans_edit')->default(0)->afer('plans_show');
            $table->boolean('insights_show')->default(0)->after('plans_edit');
            $table->boolean('control_show')->default(0)->after('insights_show');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
