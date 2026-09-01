<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Database\\Factories\\Models\\'.class_basename($modelName).'Factory'
        );

        Gate::define('admin', fn (User $user) => $user->isAdmin());

        //not used anymore as it caused issues
        RateLimiter::for('summarize', function(object $job){
            return Limit::perMinute(1)->by($job->blog->author_id);
        });
    }
}
