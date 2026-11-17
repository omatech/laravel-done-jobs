<?php
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Schema;
use Omatech\LaravelDoneJobs\DoneJob;
use Omatech\LaravelDoneJobs\Job;
use Omatech\LaravelDoneJobs\LaravelDoneJobsServiceProvider;
use Orchestra\Testbench\TestCase;

final class ProcessedJobsTest extends TestCase
{
    protected function getPackageProviders($app): array { return [LaravelDoneJobsServiceProvider::class]; }
    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
    }
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate')->assertExitCode(0);
        Schema::create('jobs', function (Blueprint $table): void { $table->id(); $table->string('queue'); $table->text('payload'); $table->unsignedInteger('attempts'); $table->unsignedInteger('available_at'); $table->unsignedInteger('created_at'); });
    }
    public function test_successful_jobs_are_recorded_and_can_be_replayed_once(): void
    {
        $payload = ['data' => ['commandName' => 'SyntheticJob'], 'attempts' => 2];
        $job = Mockery::mock(JobContract::class);
        $job->shouldReceive('getConnectionName')->andReturn('database');
        $job->shouldReceive('getQueue')->andReturn('synthetic');
        $job->shouldReceive('payload')->andReturn($payload);
        $job->shouldReceive('attempts')->andReturn(2);
        event(new JobProcessed('database', $job));
        $done = DoneJob::sole();
        self::assertSame('synthetic', $done->queue);
        self::assertSame('SyntheticJob', $done->job);
        $this->artisan('queue:rejob', ['id' => [$done->id]])->assertExitCode(0);
        self::assertSame(0, DoneJob::count());
        $queued = Job::sole();
        self::assertSame('synthetic', $queued->queue);
        self::assertSame(0, $queued->attempts);
        self::assertSame(['data' => ['commandName' => 'SyntheticJob'], 'attempts' => 0], json_decode($queued->payload, true, 512, JSON_THROW_ON_ERROR));
    }
}
