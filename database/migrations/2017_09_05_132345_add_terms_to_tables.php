<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTermsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->json('terms')->nullable()->after('state');
        });

        Schema::table('professionals', function (Blueprint $table) {
            $table->json('terms')->nullable()->after('remember_token');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->json('terms')->nullable()->after('level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('terms');
        });

        Schema::table('professionals', function (Blueprint $table) {
            $table->dropColumn('terms');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('terms');
        });
    }
}
