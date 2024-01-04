<?php

declare(strict_types=1);

namespace Nigel\Subscriptions\Providers;

use Nigel\Subscriptions\Models\Plan;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Nigel\Subscriptions\Models\PlanFeature;
use Nigel\Subscriptions\Models\PlanSubscription;
use Nigel\Subscriptions\Models\PlanSubscriptionUsage;
use Nigel\Subscriptions\Console\Commands\MigrateCommand;
use Nigel\Subscriptions\Console\Commands\PublishCommand;
use Nigel\Subscriptions\Console\Commands\RollbackCommand;

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
        $this->mergeConfigFrom(realpath(__DIR__ . '/../../config/config.php'), 'nigel.subscriptions');

        // Bind eloquent models to IoC container
        $this->registerModels([
            'nigel.subscriptions.plan' => Plan::class,
            'nigel.subscriptions.plan_feature' => PlanFeature::class,
            'nigel.subscriptions.plan_subscription' => PlanSubscription::class,
            'nigel.subscriptions.plan_subscription_usage' => PlanSubscriptionUsage::class,
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
