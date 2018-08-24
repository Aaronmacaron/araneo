<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\Check\IPApiCheckCommand::class,
        Commands\Check\LumCheckCommand::class,
        Commands\Crawler\FreeProxyListCommand::class,
        Commands\Crawler\FreeProxyListsCommand::class,
        Commands\Crawler\GetProxyListCommand::class,
        Commands\Crawler\GimmeProxyCommand::class,
        Commands\PurgeCommand::class,
        Commands\Test\IPApiTestCommand::class,
        Commands\Test\LumTestCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        //
    }
}
