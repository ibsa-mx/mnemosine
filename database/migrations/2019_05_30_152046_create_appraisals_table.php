<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('currency', ['MXN', 'USD'])->nullable();
            $table->decimal('appraisal', 13, 4);
            $table->decimal('exchange_rate', 2, 2)->nullable();
            $table->decimal('increase', 13, 4)->nullable();

            $table->unsignedBigInteger('piece_id');
            $table->foreign('piece_id')
                  ->references('id')
                  ->on('pieces')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('authorized_by')->nullable();
            $table->foreign('authorized_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->string('observation', 400)->nullable();
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
        Schema::dropIfExists('appraisals');
    }
}
