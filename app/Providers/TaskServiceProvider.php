<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\V1\TaskServiceClass;
use App\Repository\V1\TaskRepository;
use App\Interfaces\Services\V1\TaskServiceClassInterface;

class TaskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TaskServiceClassInterface::class, function ($app) {
            return new TaskServiceClass($app->make(TaskRepository::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
