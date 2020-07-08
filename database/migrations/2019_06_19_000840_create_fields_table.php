<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('label');
            $table->string('placeholder')->nullable();
            $table->tinyInteger('edit')->default(1);
            $table->tinyInteger('required');
            $table->tinyInteger('active');
            $table->tinyInteger('summary_view');
            $table->unsignedTinyInteger('order');
            $table->enum('type', ['text', 'checkbox', 'select', 'multi-select', 'textarea', 'date', 'email', 'file', 'image', 'number', 'password', 'radio', 'range', 'tel', 'time', 'url', 'color']);
            $table->integer('length')->nullable();
            $table->string('editable_in_modules');
            $table->unsignedBigInteger('field_group_id');
            $table->unsignedBigInteger('catalog_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('catalog_id')
                ->references('id')
                ->on('catalogs')
                ->onUpdate('cascade');
            $table->foreign('field_group_id')
                ->references('id')
                ->on('field_groups')
                ->onUpdate('cascade');

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
        Schema::create('field_has_roles', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedBigInteger('field_id');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('field_id')
                ->references('id')
                ->on('fields')
                ->onDelete('cascade');

            $table->primary(['field_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('field_has_roles');
        Schema::dropIfExists('fields');
    }
}
