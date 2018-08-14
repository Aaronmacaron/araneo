<?php

namespace App\Console\Commands\Check;

use App\Proxy;
use Araneo\Testers\LumTest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LumCheckCommand extends Command
{
    protected $signature = 'araneo:check:lumtest {minutes}';
    protected $description = 'Test all proxies by a given amount of time.';
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
        $minutes = $this->argument('minutes');
        $acceptableTTL = Carbon::now()->subMinutes($minutes);

        $proxies = $this->proxy
            ->whereDate('last_checked_at', '>=', $acceptableTTL)
            ->get();

        foreach ($proxies as $proxy) {
            $this->check($proxy);
        }
    }

    private function check(Proxy $proxy)
    {
        $this->info(sprintf('Checking proxy #%s', $proxy->id));

        if ($this->lumTest->test($proxy)) {
            $proxy->last_status = Proxy::STATUS_WORKING;
            $this->info('Proxy is working.');
        } else {
            $proxy->last_status = Proxy::STATUS_FAILED;
            $this->error('Proxy is not working.');
        }

        $proxy->last_checked_at = Carbon::now();
        $proxy->save();
    }
}
