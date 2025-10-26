<?php

declare(strict_types=1);

namespace App\Domain\Actions\Chat;

use App\Models\System\Staff;
use Illuminate\Support\Facades\Cache;

final class CheckActiveAdminAction
{
    /**
     * Check if there is an active admin online
     */
    public function execute(): bool
    {
        // Check cache first
        $cached = Cache::get('admin_online_status');

        if ($cached !== null) {
            return $cached === true;
        }

        // Check if any staff member is active with recent session activity
        // We consider "recent" to be within the last 5 minutes
        $activeCount = Staff::where('status', Staff::STATUS_ACTIVE)
            ->where(function ($query) {
                // Check if they have recent session activity
                // You might need to add a last_activity_at column to track this
                $query->where('updated_at', '>=', now()->subMinutes(5))
                    ->orWhere('created_at', '>=', now()->subMinutes(5));
            })
            ->count();

        $isOnline = $activeCount > 0;

        // Cache the result for 1 minute
        Cache::put('admin_online_status', $isOnline, now()->addMinutes(1));

        return $isOnline;
    }
}
