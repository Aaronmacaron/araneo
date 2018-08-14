<?php

namespace App\Console\Commands\Proxy;

use App\Proxy;
use Araneo\Sources\GimmeProxy\GimmeProxy;
use Illuminate\Console\Command;

class GimmeProxyCommand extends Command
{
    protected $signature = 'araneo:proxy:gimmeproxy';
    protected $description = 'Fetch a random proxy using GimmeProxy.';
    protected $gimmeProxy;
    protected $proxy;

    public function __construct(GimmeProxy $gimmeProxy, Proxy $proxy)
    {
        $this->gimmeProxy = $gimmeProxy;
        $this->proxy = $proxy;

        parent::__construct();
    }

    public function handle()
    {
        $this->info('Trying to fetch data from GimmeProxy.');

        $proxy = $this->gimmeProxy->random();

        if (!$this->proxy->create($proxy)) {
            $this->error('Error while saving proxy.');

            return false;
        }

        $this->info('Successfully stored proxy.');

        return true;
    }
}
