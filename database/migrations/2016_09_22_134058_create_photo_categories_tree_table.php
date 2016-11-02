<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoCategoriesTreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_categories_tree', function (Blueprint $table) {
            $table->integer('ancestor');
            $table->integer('descendant');
            $table->smallInteger('length')->default(0);

            $table->primary(['ancestor', 'descendant']);

            $table->foreign('ancestor')
                ->references('id')
                ->on('photo_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('descendant')
                ->references('id')
                ->on('photo_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photo_categories_tree');
    }
}
