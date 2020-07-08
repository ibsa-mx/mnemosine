<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResearchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('researchs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 250)->nullable();
            $table->string('author_ids', 250)->nullable();

            $table->unsignedBigInteger('set_id')->nullable();
            $table->foreign('set_id')
                  ->references('id')
                  ->on('catalog_elements')
                  ->onDelete('cascade');

            $table->mediumText('technique')->nullable();
            $table->mediumText('materials', 200)->nullable();

            $table->unsignedBigInteger('period_id')->nullable();
            $table->foreign('period_id')
                  ->references('id')
                  ->on('catalog_elements')
                  ->onDelete('cascade');

            $table->string('creation_date', 100)->nullable();

            $table->unsignedBigInteger('place_of_creation_id')->nullable();
            $table->foreign('place_of_creation_id')
                  ->references('id')
                  ->on('catalog_elements')
                  ->onDelete('cascade');

            $table->string('acquisition_form')->nullable();
            $table->string('acquisition_source')->nullable();
            $table->string('acquisition_date')->nullable();
            $table->tinyInteger('firm')->nullable();
            $table->mediumText('firm_description')->nullable();
            $table->mediumText('short_description')->nullable();
            $table->mediumText('formal_description')->nullable();
            $table->mediumText('observation')->nullable();
            $table->mediumText('publications')->nullable();

            $table->unsignedBigInteger('piece_id');
            $table->foreign('piece_id')
                  ->references('id')
                  ->on('pieces')
                  ->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
            
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')
                  ->references('id')
                  ->on('users');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users');

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')
                  ->references('id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('researchs');
    }
}
