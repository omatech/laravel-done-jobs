<?php

namespace Omatech\LaravelDoneJobs;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Omatech\LaravelDoneJobs\Skeleton\SkeletonClass
 */
class LaravelDoneJobsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-done-jobs';
    }
}
