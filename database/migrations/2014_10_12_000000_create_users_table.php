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
        Schema::create('RHCom_usuarios_laravel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('no_empleado')->unique();
            $table->string('sede');
            $table->integer('tipo_empleado');
            $table->string('email', 128)->unique();
            $table->string('password');
            $table->boolean('password_update');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('RHCom_usuarios_laravel');
    }
}
