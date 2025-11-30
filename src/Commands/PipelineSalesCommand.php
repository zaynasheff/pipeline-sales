<?php

namespace Zaynasheff\PipelineSales\Commands;

use Illuminate\Console\Command;

class PipelineSalesCommand extends Command
{
    public $signature = 'pipeline-sales';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
