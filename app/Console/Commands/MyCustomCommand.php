<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MyCustomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'now-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get now time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        $this->info('Current Date & Time: ' . $now->toDateTimeString());
        $this->info('Timezone: ' . $now->getTimezone()->getName());
    }
}
