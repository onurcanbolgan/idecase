<?php

namespace App\Providers;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(JobFailed::class, function ($event) {
            $exception = $event->exception;
            $job = $event->job;

            if ($exception instanceof \Exception) {
                // Hata logunu istediğiniz şekilde oluşturun ve log işlemini yapın.
                Log::error('Job failed: ' . get_class($job), ['exception' => $exception]);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
