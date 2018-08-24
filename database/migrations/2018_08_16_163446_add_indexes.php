<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexes extends Migration
{
    public function up()
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->index('anonymity_level', 'anonymity_level_index');
            $table->index('country', 'country_index');
            $table->index('last_checked_at', 'last_checked_at_index');
            $table->index('last_status', 'last_status_index');
        });
    }

    public function down()
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->dropIndex(['anonymity_level_index', 'country_index', 'last_checked_at_index', 'last_status_index']);
        });
    }
}
