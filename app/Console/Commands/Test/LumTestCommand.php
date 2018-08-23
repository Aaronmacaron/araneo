<?php

namespace App\Console\Commands\Test;

use App\Proxy;
use Araneo\Testers\LumTest;
use Illuminate\Console\Command;

class LumTestCommand extends Command
{
    protected $signature = 'araneo:test:lumtest {target}';
    protected $description = 'Test target proxy against lumtest.';
    protected $lumTest;
    protected $proxy;

    public function __construct(LumTest $lumTest, Proxy $proxy)
    {
        $this->lumTest = $lumTest;
        $this->proxy = $proxy;

        parent::__construct();
    }

    public function handle()
    {
        $target = $this->argument('target');
        $proxy = $this->proxy->findOrFail($target);

        $this->info(sprintf('Trying to test proxy #%s against LumTest.', $proxy->id));

        if (!$this->lumTest->singleRequest($proxy)) {
            $this->error('Proxy is not working.');

            return false;
        }

        $this->info('Proxy is working.');

        return true;
    }
}
