<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->text('description')->default('');

            $table->timestamps();
            $table->softDeletes();
        });

        foreach (['name', 'description',] as $attr) {
            Schema::getConnection()->statement(
                sprintf('CREATE INDEX photo_categories_%1$s_trigram ON photo_categories USING gin (%1$s gin_trgm_ops);', $attr)
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photo_categories');
    }
}
