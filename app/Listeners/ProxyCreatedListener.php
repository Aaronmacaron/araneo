<?php

namespace App\Listeners;

use App\Events\ProxyCreatedEvent;
use App\Proxy;
use Araneo\Testers\LumTest;
use Carbon\Carbon;
use Illuminate\Log\Logger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProxyCreatedListener implements ShouldQueue
{
    use InteractsWithQueue;

    protected $logger;
    protected $test;

    public function __construct(Logger $logger, LumTest $test)
    {
        $this->logger = $logger;
        $this->test = $test;
    }

    public function handle(ProxyCreatedEvent $event)
    {
        $this->logger->info('Proxy created!');

        $proxy = $event->proxy;
        $proxy->last_status = Proxy::STATUS_FAILED;

        if ($this->test->test($event->proxy)) {
            $proxy->last_status = Proxy::STATUS_WORKING;
        }

        $proxy->last_checked_at = Carbon::now();
        $proxy->save();

        $this->logger->info('Proxy has been checked.');
    }
}
