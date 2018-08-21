<?php

namespace App\Console\Commands\Crawler;

use App\Proxy;
use Araneo\Sources\GetProxyList\GetProxyList;
use Illuminate\Console\Command;

class GetProxyListCommand extends Command
{
    protected $signature = 'araneo:crawler:getproxylist {repeat=1}';
    protected $description = 'Fetch a random proxy using GetProxyList.';
    protected $getProxyList;
    protected $proxy;

    public function __construct(GetProxyList $getProxyList , Proxy $proxy)
    {
        $this->getProxyList = $getProxyList;
        $this->proxy = $proxy;

        parent::__construct();
    }

    public function handle()
    {
        $repeat = $this->argument('repeat');

        $this->info('Trying to fetch data from GetProxyList.');
        $this->info(sprintf('Repeating this procedure for %s times', $repeat));

        foreach (range(1, $repeat) as $row) {
            $this->crawler();
        }
    }

    private function crawler()
    {
        try {
            $proxy = $this->getProxyList->random();
            $this->proxy->updateOrCreate(['ip_address' => $proxy['ip_address']], $proxy);

            $this->info('Successfully stored proxy.');
        } catch (\Exception $exception) {
            $this->error('Error while saving proxy.');
        }
    }
}
