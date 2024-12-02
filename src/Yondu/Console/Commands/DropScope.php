<?php

namespace Yondu\Couchbase\Console\Commands;

use Couchbase\ClusterOptions;
use Couchbase\Cluster;

class DropScope extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'couchbase:scope:drop {--scope=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop Scope and collection.';

    public function handle()
    {
        $config = config('database.connections.couchbase');

        $scope = $this->option('scope');

        if(empty($scope))
        {
            $this->fail("Please provide scope name. couchbase:scope:drop --scope=");
        }

        /** @var Connection to couchbase */
        $options = new ClusterOptions();

        $options->credentials($config['username'], $config['password']);

        $cluster = new Cluster("couchbase://".$config['host'], $options);

        $bucket = $cluster->bucket($config['bucket']);

        $collections = $bucket->collections();

        //Drop Scope and Collection
        try {
            $collections->dropScope($scope);
        }
        catch ( \Couchbase\Exception\CouchbaseException $e) {
            $this->fail($e->getMessage());            
        }

        $this->info(sprintf("Couchbase scope '%s' was successfully drop.", $scope));
        
    }
}
