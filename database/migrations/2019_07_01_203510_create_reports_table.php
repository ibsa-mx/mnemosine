<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 250);
            $table->text('description')->nullable();
            $table->string('module', 100);
            $table->string('columns', 400);
            $table->string('all_fields', 400)->nullable();
            $table->string('all_conditions', 200)->nullable();
            $table->string('all_filters', 400)->nullable();
            $table->string('all_selected_filters', 400)->nullable();
            $table->string('fields', 400)->nullable();
            $table->string('conditions', 200)->nullable();
            $table->string('filters', 400)->nullable();
            $table->string('selected_filter', 400)->nullable();
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
        Schema::dropIfExists('reports');
    }
}
