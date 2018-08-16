<?php

namespace App\Jobs;

use App\Proxy;
use Araneo\Testers\LumTest;
use Carbon\Carbon;
use Illuminate\Log\Logger;

class LumCheckJob extends Job
{
    protected $proxy;

    public function __construct(Proxy $proxy)
    {
        $this->proxy = $proxy;
    }

    public function handle(Logger $logger, LumTest $lumTest)
    {
        $logger->info('Checking proxy.');

        $proxy = $this->proxy;
        $proxy->last_status = Proxy::STATUS_FAILED;

        if ($lumTest->test($proxy)) {
            $proxy->last_status = Proxy::STATUS_WORKING;
        }

        $proxy->last_checked_at = Carbon::now();
        $proxy->save();

        $logger->info('Proxy has been checked.');
    }
}
