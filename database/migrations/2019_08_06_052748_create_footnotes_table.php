<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFootnotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('footnotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title')->nullable();
            $table->text('author')->nullable();
            $table->text('article')->nullable();
            $table->text('chapter')->nullable();
            $table->text('editorial')->nullable();
            $table->string('vol_no', 200)->nullable();
            $table->text('city_country')->nullable();
            $table->string('pages', 45)->nullable();
            $table->timestamp('publication_date')->nullable();
            $table->text('description')->nullable();

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
        Schema::dropIfExists('footnotes');
    }
}
