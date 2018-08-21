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
        $logger->info('Checking proxy.', [
            'proxy_id' => $this->proxy->id,
        ]);

        $lumTest->testAndSave($this->proxy);

        $logger->info('Proxy has been checked.', [
            'proxy_id' => $this->proxy->id,
        ]);
    }

    public function failed(\Exception $exception, Logger $logger)
    {
        $logger->error('Failed to run LumCheck.', [
            'exception' => $exception->getMessage(),
        ]);

        $this->proxy->status = Proxy::STATUS_FAILED;
        $this->proxy->save();
    }
}
