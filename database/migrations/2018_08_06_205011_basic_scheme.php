<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BasicScheme extends Migration
{
    public function up()
    {
        Schema::create('proxies', function (Blueprint $table) {
            $table->increments('id');

            $table->ipAddress('ip_address');
            $table->string('country', 2);
            $table->string('protocol', 10);
            $table->unsignedInteger('port');
            $table->unsignedTinyInteger('anonymity_level');

            $table->boolean('supports_method_get');
            $table->boolean('supports_method_post');
            $table->boolean('supports_cookies');
            $table->boolean('supports_referer');
            $table->boolean('supports_user_agent');
            $table->boolean('supports_https');

            $table->string('last_status', 10)->default('new');
            $table->timestamp('last_checked_at')->useCurrent();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('proxies');
    }
}
