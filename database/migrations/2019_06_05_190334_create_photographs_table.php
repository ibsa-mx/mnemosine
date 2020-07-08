<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotographsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photographs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('photographed_at')->nullable();
            $table->string('photographer', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('file_name', 100)->nullable();
            $table->unsignedBigInteger('size');
            $table->string('mime_type', 30);

            $table->unsignedBigInteger('piece_id');
            $table->foreign('piece_id')
                  ->references('id')
                  ->on('pieces')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('module_id');
            $table->foreign('module_id')
                  ->references('id')
                  ->on('modules')
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
        Schema::dropIfExists('photographs');
    }
}
