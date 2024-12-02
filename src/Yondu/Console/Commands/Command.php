<?php

namespace Yondu\Couchbase\Console\Commands;

use Symfony\Component\Process\Process;

class Command extends \Illuminate\Console\Command
{
    protected function displayErrors(Process $process)
    {
        if (! $process->isSuccessful()) {
            $process->run(function ($type, $buffer) {
                $this->displayOutput($buffer);
            });
        }
    }

    protected function displayOutput($buffer)
    {
        $this->comment($buffer);
    }
}
