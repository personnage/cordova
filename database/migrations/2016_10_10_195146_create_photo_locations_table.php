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

            $table->string('city')->nullable();
        });

        Schema::getConnection()->statement(
            'ALTER TABLE photo_locations ADD location GEOGRAPHY(POINT,4326) NOT NULL;'
        );
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
