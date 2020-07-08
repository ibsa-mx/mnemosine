<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBibliographiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bibliographies', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('reference_type_id')->nullable();
            $table->foreign('reference_type_id')
                  ->references('id')
                  ->on('catalog_elements')
                  ->onDelete('cascade');

            $table->text('title');
            $table->text('author')->nullable();
            $table->text('article')->nullable();
            $table->text('chapter')->nullable();
            $table->text('editorial')->nullable();
            $table->string('vol_no', 200)->nullable();
            $table->text('city_country')->nullable();
            $table->string('pages', 45)->nullable();
            $table->string('publication_date', 30)->nullable();
            $table->text('editor')->nullable();
            $table->text('webpage')->nullable();
            $table->string('identifier', 250)->nullable();

            $table->unsignedBigInteger('research_id');
            $table->foreign('research_id')
                  ->references('id')
                  ->on('researchs')
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
        Schema::dropIfExists('bibliographies');
    }
}
