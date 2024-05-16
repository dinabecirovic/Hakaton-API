<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $schedule->command('user:sync-cdn-permissions')
            ->twiceDaily()
            ->sentryMonitor();

        $schedule->command('woocommerce:create-missing')
            ->dailyAt("03:00")
            ->sentryMonitor();

        $schedule->command('woocommerce:sync')
            ->dailyAt("04:00")
            ->sentryMonitor();

        $schedule->command('devrev:regenerate')
            ->dailyAt("05:00")
            ->sentryMonitor();

        $schedule->command('subscription:reminders')
            ->dailyAt("07:00")
            ->sentryMonitor();

        $schedule->command('mautic:sync')
            ->dailyAt("08:00")
            ->sentryMonitor();

        $schedule->command('woocommerce:mails')
            ->everyMinute()
            ->sentryMonitor();

        $schedule->command('photoshop:update-filesize')
            ->dailyAt("09:00")
            ->sentryMonitor(
                maxRuntime: 60,
            );

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
