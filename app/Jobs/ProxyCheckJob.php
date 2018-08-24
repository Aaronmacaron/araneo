<?php

namespace App\Jobs;

use App\Proxy;
use Araneo\Contracts\TesterInterface;
use Araneo\Testers\LumTest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Log\Logger;

class ProxyCheckJob extends Job
{
    protected $proxies;
    protected $tester;

    public function __construct(Collection $proxies, string $tester)
    {
        $this->proxies = $proxies;
        $this->tester = $tester;
    }

    public function handle(Logger $logger)
    {
        if (!$this->proxies) {
            $logger->info('There are no proxies to check.');

            return false;
        }

        $logger->info('Checking for proxies.', [
            'count' => $this->proxies->count(),
        ]);

        app($this->tester)->testAndSaveBatch($this->proxies);

        $logger->info('All proxies have been checked.', [
            'count' => $this->proxies->count(),
        ]);
    }
}
