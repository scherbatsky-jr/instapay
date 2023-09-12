<?php

namespace App\Providers;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class QueryLogServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $log_query = config('logging.query');

        if ('debug' === $log_query || 'verbose' === $log_query) {
            DB::connection()->enableQueryLog();

            Event::listen(
                RequestHandled::class,
                function (RequestHandled $event) {
                    $queries = DB::getQueryLog();

                    $queries_count = count($queries);

                    if ($queries_count) {
                        Log::channel('query')->info($event->request->fullUrl());

                        Log::channel('query')->info('No of queries: '.$queries_count);

                        if ('verbose' === config('logging.query')) {
                            Log::channel('query')->info($queries);
                        }
                    }
                }
            );

            Event::Listen(
                CommandFinished::class,
                function (CommandFinished $event) {
                    $queries = DB::getQueryLog();

                    $queries_count = count($queries);

                    if ($queries_count) {
                        Log::channel('query')->info($event->command);

                        Log::channel('query')->info('No of queries: '.$queries_count);

                        if ('verbose' === config('logging.query')) {
                            Log::channel('query')->info($queries);
                        }
                    }
                }
            );
        }
    }
}
