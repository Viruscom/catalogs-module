<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsMainTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs_main_translation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_catalog_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->string('filename');
            $table->string('thumbnail');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['main_catalog_id', 'locale']);
            $table->foreign('main_catalog_id')->references('id')->on('catalogs_main')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogs_main_translation');
    }
}
