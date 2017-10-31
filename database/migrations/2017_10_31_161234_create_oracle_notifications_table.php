<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOracleNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oracle_notifications', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('oracle_user_id')->index();
            $table->uuid('from_id')->nullable();
            $table->string('from_type')->nullable();
            $table->string('title')->nullable();
            $table->text('content');
            $table->string('button_label')->nullable();
            $table->string('button_action')->nullable();
            $table->boolean('is_readed')->default(0);
            $table->dateTime('readed_at')->nullable();
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oracle_notifications');
    }
}
