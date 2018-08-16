<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LastWorkedAtProperty extends Migration
{
    public function up()
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->timestamp('last_worked_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->dropColumn('last_worked_at');
        });
    }
}
