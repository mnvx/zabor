<?php

namespace Mnv\Reminder\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTableMnvSurveyQuestions extends Migration
{
    protected $table = 'mnv_survey_questions';

    public function up()
    {
        Schema::create($this->table, function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('post_id')->index();
            $table->foreign('post_id')->references('id')->on('rainlab_blog_posts')->onUpdate('cascade')->onDelete('cascade');
            $table->string('question');
            $table->integer('type');
            $table->integer('item_order');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}