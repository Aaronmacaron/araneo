<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\Check\LumCheckCommand::class,
        Commands\Crawler\GetProxyListCommand::class,
        Commands\Crawler\GimmeProxyCommand::class,
        Commands\PurgeCommand::class,
        Commands\Test\LumTestCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command(Commands\Crawler\GimmeProxyCommand::class)
            ->everyTenMinutes();

        $schedule->command(Commands\Crawler\GetProxyListCommand::class)
            ->everyTenMinutes();
    }
}
