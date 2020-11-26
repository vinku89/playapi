<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Contus\Base\Schedulers\Scheduler;
use Exception;
use Closure;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];
    /**
     * Class property to hold the scheduler configuration.
     *
     * @var array
     */
    protected $schedulerConfig = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->schedulerConfig = config('contus.base.scheduler') ?: [];

        if (is_array($this->schedulerConfig)) {
            $this->defineScheduler($schedule);
        }
    }
    /**
     * Define the configured Scheduler.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function defineScheduler(Schedule $schedule)
    {
        foreach ($this->schedulerConfig as $scheduler) {
            if ($schedulerInstance = $this->createSchedulerInstance($scheduler)) {
                $callable = $schedulerInstance->call();

                if ($callable instanceof Closure) {
                    $schedulerInstance->frequency($schedule->call($callable));
                }
            }
        }
    }
    /**
     * Create Scheduler instance.
     *
     * @param  string $scheduler
     * @return \Mara\Schedulers\Scheduler | null
     * @throws \Exception
     */
    protected function createSchedulerInstance($scheduler)
    {
        $schedulerInstance = null;

        try {
            $schedulerInstance = new $scheduler;
        } catch (Exception $e) {
            app()->make('log')->error($e->getMessage());
        }

        if (!is_null($schedulerInstance) && !$schedulerInstance instanceof Scheduler) {
            app()->make('log')->error("[$scheduler] should be child class of the [Contus\Base\Schedulers\Scheduler]");
            throw new Exception("[$scheduler] should be child class of the [Contus\Base\Schedulers\Scheduler]");
        }

        return $schedulerInstance;
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }
}
