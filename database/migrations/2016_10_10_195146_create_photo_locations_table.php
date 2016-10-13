<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('photo_id');

            $table->foreign('photo_id')
                ->references('id')
                ->on('photos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('place_id')->nullable();
            $table->string('location'); // add location as string. after convert to point.
            $table->string('location_type', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photo_locations');
    }
}
