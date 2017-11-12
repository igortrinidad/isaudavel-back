<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToProfessional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->boolean('email_confirmed')->after('remember_token')->default(0);
            $table->boolean('is_active')->after('remember_token')->default(0);
            $table->string('state')->after('email_confirmed')->nullable();
            $table->string('city')->after('email_confirmed')->nullable();
            $table->float('lng', 10, 6)->after('email_confirmed')->nullable();
            $table->float('lat', 10, 6)->after('email_confirmed')->nullable();
            $table->json('address')->after('email_confirmed')->nullable();
            $table->boolean('is_delivery')->after('email_confirmed')->nullable();
            $table->boolean('address_is_available')->after('email_confirmed')->nullable();
            $table->string('whatsapp')->after('phone')->nullable();
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
            $table->dropColumn('email_confirmed');
            $table->dropColumn('is_active');
            $table->dropColumn('address');
            $table->dropColumn('lat');
            $table->dropColumn('lng');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('is_delivery');
            $table->dropColumn('address_is_available');
            $table->dropColumn('whatsapp');
        });
    }
}
