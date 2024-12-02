<?php

namespace Yondu\Couchbase\Console\Commands;

use Couchbase\ClusterOptions;
use Couchbase\Cluster;
#use Couchbase\Management\CollectionSpec;

class CreateScope extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'couchbase:scope:create {--scope=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Scope and Collection.';

    public function handle()
    {
        $config = config('database.connections.couchbase');

        $scope = $this->option('scope');

        if(empty($scope))
        {
            $this->fail("Please provide scope name. couchbase:scope:create --scope=");
        }

        /** @var Connection to couchbase */
        $options = new ClusterOptions();

        $options->credentials($config['username'], $config['password']);

        $cluster = new Cluster("couchbase://".$config['host'], $options);

        $bucket = $cluster->bucket($config['bucket']);

        $collections = $bucket->collections();

        //Create Scope
        try {
            $collections->createScope($scope);
        }
        catch ( \Couchbase\Exception\CouchbaseException $e) {
            $this->fail($e->getMessage());            
        }

        //create Collection
        try {
            $collections->createCollection($scope, $scope."_data");
        }
        catch ( \Couchbase\Exception\CouchbaseException $e) {
            $this->fail($e->getMessage());            
        }
       
        $this->info(sprintf("Couchbase scopeName '%s' created with %s collection name", $scope, $scope."_data"));
    }
}
