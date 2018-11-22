<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id_event');
            $table->integer('code_id')->unsigned();
            $table->foreign('code_id')
            ->references('id')
            ->on('codes')
            ->onDelete('cascade');
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('place');
            $table->double('lat');
            $table->double('lng');
            $table->double('radio');
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
        Schema::dropIfExists('events');
    }
}