<?php

namespace Mnv\Reminder\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTableNewsRead extends Migration
{
    protected $table = 'mnv_news_read';

    public function up()
    {
        Schema::create($this->table, function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('news_id');
            $table->foreign('news_id')->references('id')->on('rainlab_blog_posts')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamp('created_at');
            $table->timestamp('updated_at');

            $table->index([
                'news_id',
                'user_id',
                'updated_at',
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}