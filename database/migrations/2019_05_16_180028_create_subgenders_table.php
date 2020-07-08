<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubgendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subgenders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description', 400)->nullable();

            $table->unsignedBigInteger('gender_id');
            $table->foreign('gender_id')
                  ->references('id')
                  ->on('genders')
                  ->onUpdate('cascade');
                  
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
        Schema::dropIfExists('subgenders');
    }
}
