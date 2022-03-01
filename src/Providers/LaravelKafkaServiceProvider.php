<?php

declare(strict_types=1);

namespace Chocofamilyme\LaravelKafka\Providers;

use Chocofamilyme\LaravelKafka\BatchRepositories\BatchRepositoryInterface;

final class LaravelKafkaServiceProvider extends \Junges\Kafka\Providers\LaravelKafkaServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->publishConfig();
        $this->registerBatchRepository();
    }

    private function publishConfig(): void
    {
        $this->publishes([
            __DIR__."/../../config/kafka.php" => config_path('kafka.php'),
        ], 'laravel-kafka');
    }

    private function registerBatchRepository(): void
    {
        $batchRepositoryNamespace = config('kafka.batch_repository');

        $this->app->bind(BatchRepositoryInterface::class, $batchRepositoryNamespace);
    }
}
