<?php

declare(strict_types=1);

namespace App\Services\Report;

use App\DTOs\Report\ReportCsvRequestDTO;
use App\DTOs\Report\ReportStatisticRequestDTO;
use App\Models\Communication\SmsPool;
use App\Models\Device\Device\PhoneNumber;
use App\Models\Finance\ContractLine;
use App\Models\User\User;

class ReportService implements ReportServiceInterface
{
    public function getLoginStatistic(ReportStatisticRequestDTO $dto): array
    {
        $query = User::query();

        if ($dto->date) {
            $query->whereDate('created_at', $dto->date);
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate($dto->limit, ['*'], 'page', $dto->page);

        return [
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
            'summary' => [
                'total_users' => User::count(),
                'active_users' => User::where('status', 1)->count(),
                'new_users_today' => User::whereDate('created_at', today())->count(),
            ],
        ];
    }

    public function getSoldDevices(ReportStatisticRequestDTO $dto): array
    {
        $query = ContractLine::query()
            ->with(['user', 'service'])
            ->where('status', 'active');

        if ($dto->date) {
            $query->whereDate('created_at', $dto->date);
        }

        $devices = $query->orderBy('created_at', 'desc')
            ->paginate($dto->limit, ['*'], 'page', $dto->page);

        return [
            'data' => $devices->items(),
            'pagination' => [
                'current_page' => $devices->currentPage(),
                'last_page' => $devices->lastPage(),
                'per_page' => $devices->perPage(),
                'total' => $devices->total(),
            ],
        ];
    }

    public function getInfluencerTotal(ReportCsvRequestDTO $dto): array
    {
        $query = User::query()
            ->where('is_influencer', true)
            ->with(['contractLines']);

        if ($dto->name) {
            $query->where(function ($q) use ($dto) {
                $q->where('first_name', 'like', '%'.$dto->name.'%')
                    ->orWhere('last_name', 'like', '%'.$dto->name.'%');
            });
        }

        $influencers = $query->orderBy('created_at', 'desc')
            ->paginate($dto->limit, ['*'], 'page', $dto->page);

        return [
            'data' => $influencers->items(),
            'pagination' => [
                'current_page' => $influencers->currentPage(),
                'last_page' => $influencers->lastPage(),
                'per_page' => $influencers->perPage(),
                'total' => $influencers->total(),
            ],
            'csv_available' => $dto->csv,
        ];
    }

    public function getReferralReport(ReportCsvRequestDTO $dto): array
    {
        $query = User::query()
            ->whereNotNull('refer_type')
            ->with(['contractLines']);

        if ($dto->name) {
            $query->where(function ($q) use ($dto) {
                $q->where('first_name', 'like', '%'.$dto->name.'%')
                    ->orWhere('last_name', 'like', '%'.$dto->name.'%');
            });
        }

        $referrals = $query->orderBy('created_at', 'desc')
            ->paginate($dto->limit, ['*'], 'page', $dto->page);

        return [
            'data' => $referrals->items(),
            'pagination' => [
                'current_page' => $referrals->currentPage(),
                'last_page' => $referrals->lastPage(),
                'per_page' => $referrals->perPage(),
                'total' => $referrals->total(),
            ],
            'csv_available' => $dto->csv,
        ];
    }

    public function getStatistic(ReportStatisticRequestDTO $dto): array
    {
        $date = $dto->date ? \Carbon\Carbon::parse($dto->date) : now();

        $stats = [
            'date' => $date->format('Y-m-d'),
            'users' => [
                'total' => User::count(),
                'active' => User::where('status', 1)->count(),
                'new_today' => User::whereDate('created_at', $date)->count(),
            ],
            'contracts' => [
                'total' => ContractLine::count(),
                'active' => ContractLine::where('status', 'active')->count(),
                'new_today' => ContractLine::whereDate('created_at', $date)->count(),
            ],
            'devices' => [
                'total' => PhoneNumber::count(),
                'active' => PhoneNumber::where('is_active', 1)->count(),
                'new_today' => PhoneNumber::whereDate('created_at', $date)->count(),
            ],
            'sms' => [
                'total' => SmsPool::count(),
                'today' => SmsPool::whereDate('created_at', $date)->count(),
            ],
        ];

        return $stats;
    }

    public function getSmsReport(ReportStatisticRequestDTO $dto): array
    {
        $query = SmsPool::query()
            ->with(['user']);

        if ($dto->date) {
            $query->whereDate('created_at', $dto->date);
        }

        $sms = $query->orderBy('created_at', 'desc')
            ->paginate($dto->limit, ['*'], 'page', $dto->page);

        return [
            'data' => $sms->items(),
            'pagination' => [
                'current_page' => $sms->currentPage(),
                'last_page' => $sms->lastPage(),
                'per_page' => $sms->perPage(),
                'total' => $sms->total(),
            ],
            'summary' => [
                'total_sms' => SmsPool::count(),
                'today_sms' => SmsPool::whereDate('created_at', today())->count(),
                'by_purpose' => SmsPool::selectRaw('purpose, COUNT(*) as count')
                    ->groupBy('purpose')
                    ->get()
                    ->pluck('count', 'purpose'),
            ],
        ];
    }
}
