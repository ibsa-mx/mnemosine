<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dimensions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('height_with_base', 10)->nullable();
            $table->string('width_with_base', 10)->nullable();
            $table->string('depth_with_base', 10)->nullable();
            $table->string('diameter_with_base', 10)->nullable();
            $table->string('height', 10)->nullable();
            $table->string('width', 10)->nullable();
            $table->string('depth', 10)->nullable();
            $table->string('diameter', 10)->nullable();
            
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
        Schema::dropIfExists('dimensions');
    }
}
