<?php

namespace App\Console\Commands\Crawler;

use App\Proxy;
use Araneo\Sources\FreeProxyList\FreeProxyList;
use Araneo\Sources\GimmeProxy\GimmeProxy;
use Illuminate\Console\Command;

class FreeProxyListCommand extends Command
{
    protected $description = 'Fetch a random proxy using FreeProxyList.';
    protected $freeProxyList;
    protected $proxy;
    protected $signature = 'araneo:crawler:freeproxylist';

    public function __construct(FreeProxyList $freeProxyList, Proxy $proxy)
    {
        $this->freeProxyList = $freeProxyList;
        $this->proxy = $proxy;

        parent::__construct();
    }

    public function handle()
    {
        $this->info('Trying to fetch data from FreeProxyList.');
        $this->info('Crawling website.');

        $proxies = $this->crawler();

        $this->info(sprintf('Found %s proxies from provider.', count($proxies)));

        if (count($proxies) === 0) {
            throw new \Exception('Cannot find any proxy.');
        }

        foreach ($proxies as $proxy) {
            $this->proxy->updateOrCreate(['ip_address' => $proxy['ip_address']], $proxy);
        }

        $this->info('Successfully stored all proxies.');
    }

    private function crawler(): array
    {
        try {
            return $this->freeProxyList->list();
        } catch (\Exception $exception) {
            $this->error('Error while crawling proxies.');
        }

        return [];
    }
}
