<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrTablePlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('company_id')->index();
            $table->uuid('category_id')->index();
            $table->string('name');
            $table->string('label');
            $table->text('description')->nullable();
            $table->decimal('value', 15,2);
            $table->integer('expiration');
            $table->boolean('limit_quantity');
            $table->integer('quantity');
            $table->boolean('is_starred');
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }

}
