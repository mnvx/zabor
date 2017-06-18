<?php

namespace Mnv\User\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AlterTableUsersAddContactFields extends Migration
{
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('zabor_stead');
            $table->string('zabor_phone');
            $table->text('zabor_about')->nullable();
            $table->string('zabor_webpage')->nullable();
            $table->string('zabor_blog')->nullable();
            $table->string('zabor_facebook')->nullable();
            $table->string('zabor_twitter')->nullable();
            $table->string('zabor_skype')->nullable();
        });
    }
    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn([
                'zabor_stead',
                'zabor_phone',
                'zabor_about',
                'zabor_webpage',
                'zabor_blog',
                'zabor_facebook',
                'zabor_twitter',
                'zabor_skype',
            ]);
        });
    }
}