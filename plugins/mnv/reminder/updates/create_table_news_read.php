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
            $table->integer('post_id');
            $table->foreign('post_id')->references('id')->on('rainlab_blog_posts')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamp('read_at');

            $table->primary([
                'post_id',
                'user_id',
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}