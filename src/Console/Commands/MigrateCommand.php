<?php

declare(strict_types=1);

namespace TheArtizan\Subscriptions\Console\Commands;

use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theartizan:migrate:subscriptions {--f|force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate TheArtizan Subscriptions Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $path = config('theartizan.subscriptions.autoload_migrations') ?
            'vendor/theartizan/laravel-subscriptions/database/migrations' :
            'database/migrations/theartizan/laravel-subscriptions';

        if (file_exists($path)) {
            $this->call('migrate', [
                '--step' => true,
                '--path' => $path,
                '--force' => $this->option('force'),
            ]);
        } else {
            $this->warn('No migrations found! Consider publish them first: <fg=green>php artisan theartizan:publish:subscriptions</>');
        }

        $this->line('');
    }
}
