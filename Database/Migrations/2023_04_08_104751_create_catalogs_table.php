<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('main_catalog_id')->unsigned();
            $table->foreign('main_catalog_id')->references('id')->on('catalogs_main')->onDelete('cascade')->onUpdate('cascade');
            $table->string('module')->nullable()->default(null);
            $table->string('model');
            $table->integer('model_id');
            $table->integer('main_position');
            $table->integer('position');
            $table->integer('creator_user_id')->nullable()->default(null);
            $table->string('filename')->nullable()->default(null);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('catalogs');
    }
}
