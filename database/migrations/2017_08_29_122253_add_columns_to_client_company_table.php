<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToClientCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_company', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(0);
            $table->uuid('deleted_by_id')->nullable();
            $table->string('deleted_by_type')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_company', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
            $table->dropColumn('deleted_by_id');
            $table->dropColumn('deleted_by_type');
            $table->dropColumn('deleted_at');
        });
    }
}
