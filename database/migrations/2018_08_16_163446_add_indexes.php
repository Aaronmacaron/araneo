<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexes extends Migration
{
    public function up()
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->index(['anonymity_level', 'country', 'last_checked_at', 'last_status']);
        });
    }

    public function down()
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->dropIndex(['anonymity_level', 'country', 'last_checked_at', 'last_status']);
        });
    }
}
