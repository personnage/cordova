<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('set null');

            // Surrogate string identifier.
            $table->string('label');

            // if server is null - photo not exist
            $table->string('server')->index()->nullable();

            $table->string('title')->default('');
            $table->text('description')->default('');

            $table->string('provider')->nullable();
            $table->string('extern_id')->nullable();
            $table->unique(['provider', 'extern_id']);

            $table->timestamps();
            $table->softDeletes();
        });

        foreach (['title', 'description',] as $attr) {
            Schema::getConnection()->statement(
                sprintf('CREATE INDEX photos_%1$s_trigram ON photos USING gin (%1$s gin_trgm_ops);', $attr)
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
        Schema::dropIfExists('photos');
    }
}
