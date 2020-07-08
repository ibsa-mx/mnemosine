<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestorationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restorations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->mediumText('preliminary_examination')->nullable();
            $table->mediumText('laboratory_analysis')->nullable();
            $table->mediumText('proposal_of_treatment')->nullable();
            $table->mediumText('treatment_description')->nullable();
            $table->mediumText('results')->nullable();
            $table->mediumText('observations')->nullable();
            $table->timestamp('treatment_date')->nullable();

            $table->unsignedBigInteger('responsible_restorer')->nullable();
            $table->foreign('responsible_restorer')
                  ->references('id')
                  ->on('catalog_elements')
                  ->onDelete('cascade');

            $table->date('initial_date')->nullable();
            $table->date('end_date')->nullable();

            $table->unsignedBigInteger('piece_id');
            $table->foreign('piece_id')
                  ->references('id')
                  ->on('pieces')
                  ->onDelete('cascade');

            $table->string('documents_ids', 250)->nullable();
            $table->string('photographs_ids', 250)->nullable();
            $table->unsignedBigInteger('height')->nullable();
            $table->unsignedBigInteger('width')->nullable();
            $table->unsignedBigInteger('depth')->nullable();
            $table->unsignedBigInteger('diameter')->nullable();
            $table->unsignedBigInteger('height_with_base')->nullable();
            $table->unsignedBigInteger('width_with_base')->nullable();
            $table->unsignedBigInteger('depth_with_base')->nullable();
            $table->unsignedBigInteger('diameter_with_base')->nullable();
            $table->enum('base_or_frame', ['base', 'frame'])->default('base');
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
        Schema::dropIfExists('renovations');
    }
}
