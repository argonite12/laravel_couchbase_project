<?php

namespace Yondu\Couchbase\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Yondu\Couchbase\Connection;

class BucketRunQuery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'couchbase:bucket:run-query {--query=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create raw query for Couchbase.';

    public function handle()
    {
        $config = config('database.connections.couchbase');

        if (! $query = $this->option('query')) {
            $this->fail('The --query parameter is required to run a query. ex: --query="SELECT * FROM bucket.scope.collection"');
        }

        /** @var Connection $couchbase */
        $couchbase = DB::connection('couchbase');
        $this->info(print_r($couchbase->runRawQuery($query), true));

        return Command::SUCCESS;
    }
}
