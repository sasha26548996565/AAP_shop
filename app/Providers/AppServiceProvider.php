<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Model::shouldBeStrict(app()->isLocal());

        if (app()->isProduction()) {
            DB::whenQueryingForLongerThan(CarbonInterval::seconds(5), static function (Connection $connection) {
                logger()
                    ->channel('telegram')
                    ->debug('Connection Longer ' . $connection->totalQueryDuration());
            });

            DB::listen(static function (QueryExecuted $query) {
                if ($query->time > 500) {
                    logger()
                        ->channel('telegram')
                        ->debug('Query Longer ' . $query->sql, $query->bindings);
                }
            });

            $kernel = app(Kernel::class);
            $kernel->whenRequestLifecycleIsLongerThan(CarbonInterval::seconds(4), static function () {
                logger()
                    ->channel('telegram')
                    ->debug('Request Lifecycle Is Longer Than ' . request()->url());
            });
        }
    }
}
