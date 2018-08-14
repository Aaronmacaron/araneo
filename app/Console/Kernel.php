<?php

namespace App\Console;

use App\Console\Commands\Proxy\GimmeProxyCommand;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        GimmeProxyCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        //
    }
}
