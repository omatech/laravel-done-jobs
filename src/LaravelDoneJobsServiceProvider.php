<?php

namespace Omatech\LaravelDoneJobs;

use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;

class LaravelDoneJobsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->commands(ReJobCommand::class);

        Queue::after(function (JobProcessed $event) {
            DoneJob::create([
                'connection' => $event->job->getConnectionName(),
                'queue' => $event->job->getQueue(),
                'job' => $event->job->payload()['data']['commandName'],
                'payload' => json_encode($event->job->payload()),
                'attempts' => $event->job->attempts(),
                'done_at' => Carbon::now()
            ]);
        });
    }
}
