<?php

namespace Tobya\SaloonForge\Commands;

use Illuminate\Console\Command;

class SaloonForgeCommand extends Command
{
    public $signature = 'saloonforge';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
