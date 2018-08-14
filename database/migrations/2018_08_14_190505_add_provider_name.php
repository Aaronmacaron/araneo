<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Araneo\Sources\ProxySource;

class AddProviderName extends Migration
{
    public function up()
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->string('proxy_source', 20)->default(ProxySource::GIMME_PROXY);
        });
    }

    public function down()
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->dropColumn('proxy_source');
        });
    }
}
