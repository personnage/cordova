<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnableExtensionPgTrgm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # https://www.postgresql.org/docs/9.5/static/textsearch-tables.html
        Schema::getConnection()->statement('CREATE EXTENSION pg_trgm;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::getConnection()->statement('DROP EXTENSION pg_trgm;');
    }
}
