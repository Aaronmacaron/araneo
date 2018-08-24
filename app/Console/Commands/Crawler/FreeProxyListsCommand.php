<?php

namespace App\Console\Commands\Crawler;

use App\Proxy;
use Araneo\Sources\FreeProxyLists\FreeProxyLists;
use Illuminate\Console\Command;

class FreeProxyListsCommand extends Command
{
    protected $description = 'Fetch a random proxy using FreeProxyLists.';
    protected $freeProxyLists;
    protected $proxy;
    protected $signature = 'araneo:crawler:freeproxylists';

    public function __construct(FreeProxyLists $freeProxyLists, Proxy $proxy)
    {
        $this->freeProxyLists = $freeProxyLists;
        $this->proxy = $proxy;

        parent::__construct();
    }

    public function handle()
    {
        $this->info('Trying to fetch data from FreeProxyLists.');
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
            return $this->freeProxyLists->list();
        } catch (\Exception $exception) {
            $this->error('Error while crawling proxies.');
        }

        return [];
    }
}
