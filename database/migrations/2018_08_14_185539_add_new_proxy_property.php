<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewProxyProperty extends Migration
{
    public function up()
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->boolean('supports_custom_headers')->default('false');
        });
    }

    public function down()
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->dropColumn('supports_custom_headers');
        });
    }
}
