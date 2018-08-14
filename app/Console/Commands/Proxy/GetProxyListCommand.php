<?php

namespace App\Console\Commands\Proxy;

use App\Proxy;
use Araneo\Sources\GetProxyList\GetProxyList;
use Illuminate\Console\Command;

class GetProxyListCommand extends Command
{
    protected $signature = 'araneo:proxy:getproxylist';
    protected $description = 'Fetch a random proxy using GetProxyList';
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
        $this->info('Trying to fetch data from GetProxyList.');

        $proxy = $this->getProxyList->random();

        if (!$this->proxy->create($proxy)) {
            $this->error('Error while saving proxy.');

            return false;
        }

        $this->info('Successfully stored proxy.');

        return true;
    }
}
