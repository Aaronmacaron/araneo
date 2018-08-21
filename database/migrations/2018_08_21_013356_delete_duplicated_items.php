<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class DeleteDuplicatedItems extends Migration
{
    public function up()
    {
        DB::statement("DELETE FROM proxies a USING proxies b WHERE a.id > b.id AND a.ip_address = b.ip_address");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
