<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrTableSiteArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_articles', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('slug');
            $table->string('title');
            $table->longText('content');
            $table->string('path');
            $table->boolean('is_published');
            $table->integer('views');
            $table->integer('shares');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_articles');
    }
}
