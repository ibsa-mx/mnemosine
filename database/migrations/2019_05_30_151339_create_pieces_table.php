<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePiecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pieces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('inventory_number', 20)->nullable();
            $table->string('origin_number', 20)->nullable();
            $table->string('catalog_number', 20)->nullable();
            $table->decimal('appraisal', 13, 2)->nullable();
            $table->mediumText('description_origin')->nullable();

            $table->unsignedBigInteger('gender_id')->nullable();
            $table->foreign('gender_id')
                  ->references('id')
                  ->on('genders')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('subgender_id')->nullable();
            $table->foreign('subgender_id')
                  ->references('id')
                  ->on('subgenders')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('type_object_id')->nullable();
            $table->foreign('type_object_id')
                  ->references('id')
                  ->on('catalog_elements')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')
                  ->references('id')
                  ->on('catalog_elements')
                  ->onDelete('cascade');

            $table->timestamp('admitted_at')->nullable();
            $table->text('tags')->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('depth', 10, 2)->nullable();
            $table->decimal('diameter', 10, 2)->nullable();
            $table->decimal('height_with_base', 10, 2)->nullable();
            $table->decimal('width_with_base', 10, 2)->nullable();
            $table->decimal('depth_with_base', 10, 2)->nullable();
            $table->decimal('diameter_with_base', 10, 2)->nullable();
            $table->enum('base_or_frame', ['base', 'frame'])->nullable();
            $table->tinyInteger('research_info')->default('0');
            $table->tinyInteger('restoration_info')->default('0');
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

            $table->index(['inventory_number', 'origin_number', 'catalog_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pieces');
    }
}
