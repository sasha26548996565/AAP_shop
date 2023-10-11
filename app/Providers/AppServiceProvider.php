<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Model::preventLazyLoading(app()->isLocal());
        Model::preventSilentlyDiscardingAttributes(app()->isLocal());

        DB::whenQueryingForLongerThan(500, function (Connection $connection) {
            logger()
                ->channel('telegram')
                ->debug('Query Longer ' . $connection->query()->toSql());
        });

        $kernel = app(Kernel::class);
        $kernel->whenRequestLifecycleIsLongerThan(CarbonInterval::seconds(4), function () {
            logger()
                ->channel('telegram')
                ->debug('Request Lifecycle Is Longer Than ' . request()->url());
        });
    }
}
