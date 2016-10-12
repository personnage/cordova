<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Init extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # https://www.postgresql.org/docs/9.5/static/textsearch-tables.html
        Schema::getConnection()->statement('CREATE EXTENSION IF NOT EXISTS pg_trgm;');

        # http://trac.osgeo.org/postgis/wiki/UsersWikiPostGIS22UbuntuPGSQL95Apt
        Schema::getConnection()->statement('CREATE EXTENSION IF NOT EXISTS postgis;');
        Schema::getConnection()->statement('CREATE EXTENSION IF NOT EXISTS pgrouting;');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
