<?php

namespace App\Console\Commands\Test;

use App\Proxy;
use Araneo\Testers\IPApiTest;
use Araneo\Testers\LumTest;
use Illuminate\Console\Command;

class IPApiTestCommand extends Command
{
    protected $description = 'Test target proxy against IpAPI.';
    protected $IPApiTest;
    protected $proxy;
    protected $signature = 'araneo:test:ipapi {target}';

    public function __construct(IPApiTest $IPApiTest, Proxy $proxy)
    {
        $this->IPApiTest = $IPApiTest;
        $this->proxy = $proxy;

        parent::__construct();
    }

    public function handle()
    {
        $target = $this->argument('target');
        $proxy = $this->proxy->findOrFail($target);

        $this->info(sprintf('Trying to test proxy #%s against IPApi.', $proxy->id));

        if (!$this->IPApiTest->singleRequest($proxy)) {
            $this->error('Proxy is not working.');

            return false;
        }

        $this->info('Proxy is working.');

        return true;
    }
}
