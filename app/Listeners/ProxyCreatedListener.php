<?php

namespace App\Listeners;

use App\Events\ProxyCreatedEvent;
use Araneo\Testers\LumTest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Log\Logger;
use Illuminate\Queue\InteractsWithQueue;

class ProxyCreatedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'high';

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

        $this->test->testAndSave($event->proxy);

        $this->logger->info('Proxy has been checked.');
    }
}
