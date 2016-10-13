<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLocationToPhotoLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::getConnection()->statement(
            'ALTER TABLE photo_locations ALTER COLUMN location TYPE GEOGRAPHY(POINT,4326) USING location::GEOGRAPHY(POINT,4326);'
        );

        // CREATE INDEX global_points_gix ON photo_locations USING GIST (location);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::getConnection()->statement(
            'ALTER TABLE photo_locations ALTER COLUMN location TYPE VARCHAR USING location::VARCHAR;'
        );
    }
}
