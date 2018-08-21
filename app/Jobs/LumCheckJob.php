<?php

namespace App\Jobs;

use App\Proxy;
use Araneo\Testers\LumTest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Log\Logger;

class LumCheckJob extends Job
{
    protected $proxies;

    public function __construct(Collection $proxies)
    {
        $this->proxies = $proxies;
    }

    public function handle(Logger $logger, LumTest $lumTest)
    {
        $logger->info('Checking for proxies.', [
            'count' => $this->proxies->count(),
        ]);

        $lumTest->testAndSaveBatch($this->proxies);

        $logger->info('All proxies have been checked.', [
            'count' => $this->proxies->count(),
        ]);
    }
}
