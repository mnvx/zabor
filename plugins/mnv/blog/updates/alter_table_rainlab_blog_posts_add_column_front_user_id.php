<?php

namespace Mnv\Reminder\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AlterTableRainlabBlogPostsAddColumnFrontUserId extends Migration
{
    protected $table = 'rainlab_blog_posts';

    public function up()
    {
        Schema::table($this->table, function($table)
        {
            $table->integer('front_user_id')->nullable()->index();
            $table->foreign('front_user_id')->references('id')->on('users')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropColumn('front_user_id');
    }
}