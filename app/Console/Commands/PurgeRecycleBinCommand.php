<?php

namespace App\Console\Commands;

use App\Services\RecycleBinService;
use Illuminate\Console\Command;

class PurgeRecycleBinCommand extends Command
{
    protected $signature = 'recycle:purge';

    protected $description = 'Permanently purge expired recycle-bin items';

    public function handle(RecycleBinService $recycleBins): int
    {
        $this->info(sprintf('Purged %d recycle-bin items.', $recycleBins->forcePurge()));

        return self::SUCCESS;
    }
}
