<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); // manager or editor
            $table->string('label')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); // edit-forum or update-post
            $table->string('label')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $statement = 'CREATE INDEX %1$s_on_%2$s_trigram ON %1$s USING gin (%2$s gin_trgm_ops);';

        Schema::getConnection()->statement(sprintf($statement, 'roles', 'name'));
        Schema::getConnection()->statement(sprintf($statement, 'roles', 'label'));
        Schema::getConnection()->statement(sprintf($statement, 'permissions', 'name'));
        Schema::getConnection()->statement(sprintf($statement, 'permissions', 'label'));

        Schema::create('permission_role', function (Blueprint $table) {
            $table->unsignedInteger('permission_id')->index();
            $table->unsignedInteger('role_id')->index();

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('role_id')->index();
            $table->unsignedInteger('user_id')->index();

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary(['role_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user');
        Schema::drop('permission_role');
        Schema::drop('permissions');
        Schema::drop('roles');
    }
}
