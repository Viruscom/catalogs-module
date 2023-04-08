<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_translation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('catalog_id');
            $table->string('locale')->index();
            $table->text('short_description')->nullable()->default(null);
            $table->timestamps();

            $table->unique(['catalog_id', 'locale']);
            $table->foreign('catalog_id')->references('id')->on('catalogs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_translation');
    }
}
