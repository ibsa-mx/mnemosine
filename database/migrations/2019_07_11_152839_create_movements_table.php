<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('movement_type', ['internal', 'external']);
            $table->enum('itinerant', ['0', '1'])->nullable();

            $table->unsignedBigInteger('institution_id');
            $table->foreign('institution_id')
                  ->references('id')
                  ->on('institutions')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')
                  ->references('id')
                  ->on('contacts')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('exhibition_id');
            $table->foreign('exhibition_id')
                  ->references('id')
                  ->on('exhibitions')
                  ->onDelete('cascade');

            $table->string('venues', 250)->nullable();

            $table->date('departure_date')->nullable();
            $table->text('observations')->nullable();
            $table->date('start_exposure')->nullable();
            $table->date('end_exposure')->nullable();
            $table->mediumText('pieces_ids')->nullable();

            $table->unsignedBigInteger('authorized_by_investigation')->nullable();
            $table->foreign('authorized_by_investigation')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('authorized_by_restoration')->nullable();
            $table->foreign('authorized_by_restoration')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('authorized_by_inventory')->nullable();
            $table->foreign('authorized_by_inventory')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('authorized_by_other')->nullable();
            $table->foreign('authorized_by_other')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('arrival_location_id')->nullable();
            $table->foreign('arrival_location_id')
                  ->references('id')
                  ->on('catalog_elements')
                  ->onDelete('cascade');

            $table->date('arrival_date')->nullable();
            $table->enum('type_arrival', ['partial', 'full'])->nullable();
            $table->mediumText('pieces_ids_arrived')->nullable();
            $table->softdeletes();
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
        Schema::dropIfExists('movements');
    }
}
