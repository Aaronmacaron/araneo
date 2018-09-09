<?php

namespace App\Console\Commands\Check;

use App\Jobs\ProxyCheckJob;
use App\Proxy;
use Araneo\Testers\LumTest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LumCheckCommand extends Command
{
    const BATCH_SIZE = 10;

    protected $description = 'Test all proxies by a given amount of time.';
    protected $proxy;
    protected $signature = 'araneo:check:lumtest {minutes}';

    public function __construct(Proxy $proxy)
    {
        $this->proxy = $proxy;

        parent::__construct();
    }

    public function handle()
    {
        $minutes = $this->argument('minutes');
        $acceptableTTL = Carbon::now()->subMinutes($minutes);

        $this->info(sprintf('Searching for proxies with last checked at is older than %s.', $acceptableTTL));

        $proxies = $this->proxy
            ->whereDate('last_checked_at', '<=', $acceptableTTL)
            ->orWhereNull('last_checked_at')
            ->get();

        $this->info(sprintf('Found %s proxies.', $proxies->count()));
        $this->info(sprintf('Using thunks of %s.', self::BATCH_SIZE));

        foreach ($proxies->chunk(self::BATCH_SIZE) as $batch) {
            dispatch(new ProxyCheckJob($batch, LumTest::class));
        }

        $this->info('Job is done.');
    }
}
