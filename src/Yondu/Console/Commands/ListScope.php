<?php

namespace Yondu\Couchbase\Console\Commands;

use Couchbase\ClusterOptions;
use Couchbase\Cluster;

class ListScope extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'couchbase:scope:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display the list of Scope and Collection.';

    public function handle()
    {
        $config = config('database.connections.couchbase');

        $bucket = (!empty($config['bucket'])) ? $config['bucket'] : null;

        if(empty($bucket))
        {
            $this->fail("Bucket was not specify. Please add bucket in your env.");
        }

        /** @var Connection to couchbase */
        $options = new ClusterOptions();

        $options->credentials($config['username'], $config['password']);

        $cluster = new Cluster("couchbase://".$config['host'], $options);

        $bucket = $cluster->bucket($config['bucket']);

        $collections = $bucket->collections();

        //List of Scope and Collection

        $this->info("Here are the list of Scope with corresponding collections");

        $scopes = $collections->getAllScopes();
        foreach ($scopes as $scope) {
            print "ScopeName: {$scope->name()}\n";
            
            foreach ($scope->collections() as $collection) {
                print " - {$collection->name()}\n";
            }
        }               
        
    }
}
