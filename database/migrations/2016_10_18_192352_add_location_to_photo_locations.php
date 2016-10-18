<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationToPhotoLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // http://postgis.net/docs/AddGeometryColumn.html
        Schema::getConnection()->statement(
            "SELECT AddGeometryColumn ('public','photo_locations','location',4326,'POINT',2);"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // http://postgis.net/docs/DropGeometryColumn.html
        Schema::getConnection()->statement(
            "SELECT DropGeometryColumn ('public','photo_locations','location');"
        );
    }
}
