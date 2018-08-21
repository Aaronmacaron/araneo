<?php

namespace App\Console\Commands\Crawler;

use App\Proxy;
use Araneo\Sources\GimmeProxy\GimmeProxy;
use Illuminate\Console\Command;

class GimmeProxyCommand extends Command
{
    protected $signature = 'araneo:crawler:gimmeproxy {repeat=1}';
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
        $repeat = $this->argument('repeat');

        $this->info('Trying to fetch data from GimmeProxy.');
        $this->info(sprintf('Repeating this procedure for %s times', $repeat));

        foreach (range(1, $repeat) as $row) {
            $this->crawler();
        }
    }

    private function crawler()
    {
        try {
            $proxy = $this->gimmeProxy->random();
            $this->proxy->updateOrCreate(['ip_address' => $proxy['ip_address']], $proxy);

            $this->info('Successfully stored proxy.');
        } catch (\Exception $exception) {
            $this->error('Error while saving proxy.');
        }
    }
}
