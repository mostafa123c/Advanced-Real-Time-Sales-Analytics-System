<?php

namespace App\Console\Commands;

use App\Events\AnalyticsUpdated;
use Illuminate\Console\Command;

class RefreshAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-analytics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh and broadcast analytics data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        event(new AnalyticsUpdated());
        $this->info('Analytics data refreshed and broadcasted successfully');
    }
}