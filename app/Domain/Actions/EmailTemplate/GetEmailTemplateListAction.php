<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailTemplate;

use App\Models\Communication\EmailTemplate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetEmailTemplateListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, EmailTemplate>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = EmailTemplate::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('from', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
