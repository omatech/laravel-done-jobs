<?php

namespace Omatech\LaravelDoneJobs;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Console\Command;

class ReJobCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'queue:rejob {id* : The ID of the done job or "all" to retry all jobs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push again a done queue job';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->getJobIds() as $id) {
            $job = DoneJob::find($id);

            if (is_null($job)) {
                $this->error("Unable to find done job with ID [{$id}].");
            } else {
                $this->retryJob($job);

                $this->info("The done job [{$id}] has been pushed again onto the queue!");

                $job->delete();
            }
        }
    }

    /**
     * Get the job IDs to be retried.
     *
     * @return array
     */
    protected function getJobIds()
    {
        $ids = (array) $this->argument('id');

        if (count($ids) === 1 && $ids[0] === 'all') {
            $ids = Arr::pluck(DoneJob::all(), 'id');
        }

        return $ids;
    }

    /**
     * Retry the queue job.
     *
     * @param  \stdClass  $job
     * @return void
     */
    protected function retryJob($job)
    {
        Job::create([
            'queue' => $job->queue,
            'payload' => $this->resetAttempts($job->payload),
            'attempts' => 0,
            'available_at' => Carbon::now()->timestamp,
            'created_at' => Carbon::now()->timestamp
        ]);
    }

    /**
     * Reset the payload attempts.
     *
     * Applicable to Redis jobs which store attempts in their payload.
     *
     * @param  string  $payload
     * @return string
     */
    protected function resetAttempts($payload)
    {
        $payload = json_decode($payload, true);

        if (isset($payload['attempts'])) {
            $payload['attempts'] = 0;
        }

        return json_encode($payload);
    }
}
