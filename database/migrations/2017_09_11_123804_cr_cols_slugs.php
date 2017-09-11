<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrColsSlugs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->string('slug')->after('email');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->after('name');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->string('slug')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
