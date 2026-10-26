<?php

namespace App\Console\Commands;

use App\Models\WorkspaceMember;
use Illuminate\Console\Command;

class CheckWorkspaceExpiryCommand extends Command
{
    protected $signature = 'workspace:check-expiry';

    protected $description = 'Revoke members of expired workspaces';

    public function handle(): int
    {
        WorkspaceMember::query()->whereNull('revoked_at')->whereHas('workspace', fn ($query) => $query->whereNotNull('expires_at')->where('expires_at', '<', now()))->update(['revoked_at' => now()]);

        return self::SUCCESS;
    }
}
