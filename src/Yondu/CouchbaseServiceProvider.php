<?php

namespace Yondu\Couchbase;

use Illuminate\Support\ServiceProvider;
use Yondu\Couchbase\Eloquent\Model;
use Yondu\Couchbase\Console\Commands\BucketList;
use Yondu\Couchbase\Console\Commands\BucketRunQuery;
use Yondu\Couchbase\Console\Commands\BucketCreatePrimaryIndex;

use Yondu\Couchbase\Console\Commands\CreateScope;
use Yondu\Couchbase\Console\Commands\ListScope;
use Yondu\Couchbase\Console\Commands\DropScope;

class CouchbaseServiceProvider extends ServiceProvider
{
    public const DATABASE_CONFIG_PATH = __DIR__ . '/../../config/database.php';

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {

        Model::setConnectionResolver($this->app['db']);

        Model::setEventDispatcher($this->app['events']);
        $this->publishes([ self::DATABASE_CONFIG_PATH => config_path('database.php')], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateScope::class,
                ListScope::class,
                DropScope::class,
                BucketRunQuery::class,
                BucketList::class,
                BucketCreatePrimaryIndex::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(self::DATABASE_CONFIG_PATH, 'database');

        $registerSingletonForConnection = function (string $name, array $config = null) {
            static $registeredConnections;

            if (! isset($registeredConnections)) {
                $registeredConnections = [];
            }

            if (! isset($registeredConnections[$name])) {
                $config = $config ?? config('database.connections.' . $name);

                if (isset($config['driver']) && $config['driver'] === 'couchbase') {
                    $config['name'] = $name;
                    $this->app->singleton('couchbase.connection.' . $name, function ($app) use ($config) {
                        return new Connection($config);
                    });
                }

                $registeredConnections[$name] = true;
            }
        };

        $this->app->resolving('couchbase.connection', function () use (&$registerSingletonForConnection) {
            $name = config('database.default');
            $registerSingletonForConnection($name);

            return app('database.connection.' . $name);
        });

        $this->app->resolving('db', function ($db) use (&$registerSingletonForConnection) {
            $db->extend('couchbase', function ($config, $name) use (&$registerSingletonForConnection) {
                $registerSingletonForConnection($name, $config);

                return app('couchbase.connection.' . $name);
            });
        });
    }
}
