<?php

namespace A909M\FilamentGenerateHelpers\Commands;

use Illuminate\Console\Command;

class FilamentGenerateHelpersCommand extends Command
{
    public $signature = 'filament-generate-helpers';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
