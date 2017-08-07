<?php

namespace Mnv\Zabor\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateTableElectricMeter extends Migration
{
    protected $table = 'mnv_electric_meter';

    public function up()
    {
        Schema::create($this->table, function($table)
        {
            /** @var Blueprint $table */
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedInteger('counter_number');
            $table->unsignedInteger('tariff_number');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}