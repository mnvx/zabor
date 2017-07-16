<?php

namespace Mnv\Reminder\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTableMnvSurveyAnswers extends Migration
{
    protected $table = 'mnv_survey_answers';

    public function up()
    {
        Schema::create($this->table, function($table)
        {
            /** @var Blueprint $table */
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('front_user_id')->nullable()->index();
            $table->foreign('front_user_id')->references('id')->on('users')->onUpdate('restrict')->onDelete('restrict');

            $table->integer('question_id')->index();
            $table->foreign('question_id')->references('id')->on('mnv_survey_questions')->onUpdate('cascade')->onDelete('cascade');

            $table->string('value');

            $table->string('stringValue')->nullable();
            $table->boolean('booleanValue')->nullable();
            $table->integer('integerValue')->nullable();
            $table->float('decimalValue')->nullable();

            $table->timestamps();

            $table->unique(['question_id', 'front_user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}