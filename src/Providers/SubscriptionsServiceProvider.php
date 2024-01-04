<?php

declare(strict_types=1);

namespace TheArtizan\Subscriptions\Providers;

use TheArtizan\Subscriptions\Models\Plan;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use TheArtizan\Subscriptions\Models\PlanFeature;
use TheArtizan\Subscriptions\Models\PlanSubscription;
use TheArtizan\Subscriptions\Models\PlanSubscriptionUsage;
use TheArtizan\Subscriptions\Console\Commands\MigrateCommand;
use TheArtizan\Subscriptions\Console\Commands\PublishCommand;
use TheArtizan\Subscriptions\Console\Commands\RollbackCommand;

class SubscriptionsServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class,
        PublishCommand::class,
        RollbackCommand::class,
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(realpath(__DIR__ . '/../../config/config.php'), 'theartizan.subscriptions');

        // Bind eloquent models to IoC container
        $this->registerModels([
            'theartizan.subscriptions.plan' => Plan::class,
            'theartizan.subscriptions.plan_feature' => PlanFeature::class,
            'theartizan.subscriptions.plan_subscription' => PlanSubscription::class,
            'theartizan.subscriptions.plan_subscription_usage' => PlanSubscriptionUsage::class,
        ]);


    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish Resources
        $this->publishes([
            realpath(__DIR__ . '/../../config/config.php') => config_path('laravel-subscriptions.php'),
        ]);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register console commands


        if ($this->app->runningInConsole()) {
            $this->commands(
                $this->commands
            );
        }
    }
}
