<?php

namespace App\Console;

use App\Console\Commands\Proxy\GimmeProxyCommand;
use App\Console\Commands\Proxy\GetProxyListCommand;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        GimmeProxyCommand::class,
        GetProxyListCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command(GimmeProxyCommand::class)
            ->everyTenMinutes();

        $schedule->command(GetProxyListCommand::class)
            ->everyTenMinutes();
    }
}
