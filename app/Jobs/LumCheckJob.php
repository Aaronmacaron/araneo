<?php

namespace App\Jobs;

use App\Proxy;
use Araneo\Testers\LumTest;
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

        $lumTest->testAndSave($this->proxy);

        $logger->info('Proxy has been checked.');
    }
}
