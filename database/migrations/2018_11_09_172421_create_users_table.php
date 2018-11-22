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
            $table->integer('code_id')->unsigned();
            $table->foreign('code_id')
                  ->references('id')
                  ->on('codes')
                  ->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('state');
            $table->string('payment_method');
            $table->string('ubicacion_actual');
            $table->double('lat');
            $table->double('lng');
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
        Schema::dropIfExists('users');
    }
}