<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\Check\LumCheckCommand::class,
        Commands\Proxy\GetProxyListCommand::class,
        Commands\Proxy\GimmeProxyCommand::class,
        Commands\Test\LumTestCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command(Commands\Proxy\GimmeProxyCommand::class)
            ->everyTenMinutes();

        $schedule->command(Commands\Proxy\GetProxyListCommand::class)
            ->everyTenMinutes();
    }
}
