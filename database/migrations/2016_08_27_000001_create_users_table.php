<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->integer('sign_in_count')->default(0);
            $table->string('current_sign_in_at')->nullable();
            $table->string('last_sign_in_at')->nullable();
            $table->string('current_sign_in_ip')->nullable();
            $table->string('last_sign_in_ip')->nullable();

            $table->integer('failed_attempts')->default(0);

            $table->string('name')->nullable()->index();
            $table->boolean('admin')->default(false)->index();

            $table->string('provider')->nullable();
            $table->string('extern_uid')->nullable();
            $table->unique(['provider', 'extern_uid']);
            $table->string('username')->nullable()->index();

            $table->integer('created_by_id')->nullable();
        });

        foreach (['name', 'email', 'username'] as $name) {
            Schema::getConnection()->statement(
                sprintf('CREATE INDEX users_%1$s_trigram ON users USING gin (%1$s gin_trgm_ops);', $name)
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
        Schema::drop('users');
    }
}
