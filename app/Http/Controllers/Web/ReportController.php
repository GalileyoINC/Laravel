<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Report\GetDevicesPlansListAction;
use App\Domain\Actions\Report\GetLoginStatisticAction;
use App\Domain\Actions\Report\GetLoginStatisticByDayAction;
use App\Domain\Actions\Report\GetSmsReportListAction;
use App\Domain\Actions\Report\GetSoldDevicesListAction;
use App\Domain\Actions\Report\GetUserPointReportAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\Web\SmsIndexRequest;
use App\Http\Requests\Report\Web\SoldDevicesIndexRequest;
use App\Models\Device\PhoneNumber;
use App\Models\Finance\ContractLine;
use App\Models\Finance\Service;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View as ViewFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(
        private readonly GetSoldDevicesListAction $getSoldDevicesListAction,
        private readonly GetSmsReportListAction $getSmsReportListAction,
        private readonly GetDevicesPlansListAction $getDevicesPlansListAction,
        private readonly GetLoginStatisticAction $getLoginStatisticAction,
        private readonly GetLoginStatisticByDayAction $getLoginStatisticByDayAction,
        private readonly GetUserPointReportAction $getUserPointReportAction,
    ) {}

    /**
     * Display login statistics
     */
    public function loginStatistic(Request $request, ?string $date = null): View
    {
        if ($date) {
            $data = $this->getLoginStatisticByDayAction->execute($date);
            $title = Carbon::parse($date)->format('M d, Y');
        } else {
            $data = $this->getLoginStatisticAction->execute();
            $title = Carbon::now()->format('F Y');
        }

        return ViewFacade::make('report.login-statistic', [
            'date' => $date,
            'data' => $data,
            'title' => $title,
        ]);
    }

    /**
     * Display sold devices report
     */
    public function soldDevices(SoldDevicesIndexRequest $request): View
    {
        $filters = $request->validated();
        $devices = $this->getSoldDevicesListAction->execute($filters, 20);

        return ViewFacade::make('report.sold-devices', [
            'devices' => $devices,
            'filters' => $filters,
        ]);
    }

    /**
     * Display influencer total report
     */
    public function influencerTotal(Request $request, ?string $name = null, bool $csv = false): View|StreamedResponse
    {
        $data = $this->getInfluencerTotalReport($name);

        if ($csv) {
            return $this->exportInfluencerTotalCsv($data);
        }

        return ViewFacade::make('report.influencer-total', [
            'name' => $name,
            'data' => $data,
        ]);
    }

    /**
     * Display referral report
     */
    public function referral(Request $request, ?string $month = null): View
    {
        $date = $month ? Carbon::parse($month) : Carbon::now();
        $reportDates = $this->getReportDates();
        $currDate = array_search($date->format('F Y'), $reportDates);

        $services = Service::where('type', Service::TYPE_SUBSCRIBE)->get()->toArray();
        $report = $this->getReferralReport($date);

        return ViewFacade::make('report.referral', [
            'currDate' => $currDate,
            'reportDates' => $reportDates,
            'services' => $services,
            'report' => $report,
        ]);
    }

    /**
     * Export referral report to CSV
     */
    public function refToCsv(Request $request, ?string $month = null): StreamedResponse
    {
        $date = $month ? Carbon::parse($month) : Carbon::now();
        $report = $this->getReferralReport($date);
        $services = Service::where('type', Service::TYPE_SUBSCRIBE)->get()->toArray();

        $delimiter = ',';
        $headerContent = "Influencer{$delimiter}Total # of posts published this month{$delimiter}";

        foreach ($services as $service) {
            $headerContent .= "# of referrals on {$service['name']} plan{$delimiter}";
            $headerContent .= "Compensation for {$service['name']} plan referrals{$delimiter}";
        }

        $headerContent .= "Total # of referrals{$delimiter}Total influencer compensation".PHP_EOL;

        $mainContent = '';
        foreach ($report['data'] as $value) {
            $total0 = $total1 = 0;
            $mainContent .= $value['name'].$delimiter.$value['alerts'].$delimiter;

            foreach ($services as $service) {
                $total0 += $value[$service['id']][0] ?? 0;
                $total1 += $value[$service['id']][1] ?? 0;

                $mainContent .= ($value[$service['id']][1] ?? 0).$delimiter;
                $mainContent .= number_format($this->getCompensation($value['alerts'], $value[$service['id']][0] ?? 0), 2).$delimiter;
            }

            $mainContent .= $total1.$delimiter.number_format($this->getCompensation($value['alerts'], $total0), 2).PHP_EOL;
        }

        $content = $headerContent.$mainContent;
        $fileName = 'report_referral_'.$date->format('F_Y').'.csv';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    /**
     * Display SPS termination report
     */
    public function spsTermination(): View
    {
        $users = User::whereNotNull('sps_terminated_at')
            ->orderBy('sps_terminated_at', 'desc')
            ->get();

        return ViewFacade::make('report.sps-termination', [
            'users' => $users,
        ]);
    }

    /**
     * Display statistics report
     */
    public function statistic(Request $request, ?string $date = null): View
    {
        $months = $this->getMonthsList();
        $nowDate = Carbon::now();

        if (empty($date)) {
            $date = $nowDate->format('Y-m-d H:i:s');
        }

        $baseDate = Carbon::parse($date);
        $firstDay = $baseDate->copy()->startOfMonth()->format('Y-m-d');
        $yesterDay = $nowDate->copy()->subDay()->format('Y-m-d');
        $inMonth = 0;

        if ($baseDate->format('Y-m') === $nowDate->format('Y-m')) {
            $lastDay = $nowDate->copy()->endOfDay()->format('Y-m-d H:i:s');
        } else {
            $lastDay = $baseDate->copy()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
            $inMonth = $baseDate->copy()->endOfMonth()->day;
        }

        // Get statistics data
        $activeUserCount = $this->getActiveUserCount($baseDate, $nowDate, $lastDay);
        $activePlanCountAll = $this->getActivePlanCount($baseDate, $nowDate, $lastDay);
        $activePlanCountAnnual = $this->getActivePlanCount($baseDate, $nowDate, $lastDay, 12);
        $activePlanCountMonth = $this->getActivePlanCount($baseDate, $nowDate, $lastDay, 1);
        $spsUsers = $this->getSpsUsersCount($baseDate, $nowDate, $lastDay);
        $sps = $this->getSpsData($baseDate, $nowDate, $lastDay);
        $newPlans = $this->getNewPlansData($firstDay, $lastDay);
        $newPlansSps = $this->getNewPlansSpsData($firstDay, $lastDay);
        $yesterdayPlans = $this->getYesterdayPlansData($baseDate, $nowDate, $yesterDay);
        $currentPlans = $this->getCurrentPlansData($baseDate, $nowDate, $lastDay);
        $bivyCount = $this->getPhoneCount(PhoneNumber::TYPE_BIVY, $lastDay);
        $pivotelCount = $this->getPhoneCount(PhoneNumber::TYPE_PIVOTEL, $lastDay);
        $satelliteCount = $this->getPhoneCount(PhoneNumber::TYPE_SATELLITE, $lastDay);

        return ViewFacade::make('report.statistic', [
            'date' => $date,
            'months' => $months,
            'inMonth' => $inMonth,
            'activeUserCount' => $activeUserCount,
            'activePlanCountAll' => $activePlanCountAll,
            'activePlanCountAnnual' => $activePlanCountAnnual,
            'activePlanCountMonth' => $activePlanCountMonth,
            'sps' => $sps,
            'newPlans' => $newPlans,
            'newPlansSps' => $newPlansSps,
            'yesterdayPlans' => $yesterdayPlans,
            'currentPlans' => $currentPlans,
            'spsUsers' => $spsUsers,
            'bivyCount' => $bivyCount,
            'pivotelCount' => $pivotelCount,
            'satelliteCount' => $satelliteCount,
        ]);
    }

    /**
     * Display ended report
     */
    public function ended(Request $request, ?string $name = null, bool $csv = false): View|StreamedResponse
    {
        $data = $this->getEndedReport($name);

        if ($csv) {
            return $this->exportEndedCsv($data);
        }

        return ViewFacade::make('report.ended', [
            'name' => $name,
            'data' => $data,
        ]);
    }

    /**
     * Display reaction report
     */
    public function reaction(Request $request, ?string $name = null): View
    {
        $data = $this->getReactionReport($name);

        return ViewFacade::make('report.reaction', [
            'name' => $name,
            'data' => $data,
        ]);
    }

    /**
     * Display devices plans report
     */
    public function devicesPlans(Request $request, ?string $date = null): View
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $devices = $this->getDevicesPlansListAction->execute($date->format('Y-m-d'), $request->only(['search']));

        return ViewFacade::make('report.devices-plans', [
            'devices' => $devices,
            'date' => $date,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Display SMS report
     */
    public function sms(SmsIndexRequest $request): View
    {
        $filters = $request->validated();
        $sms = $this->getSmsReportListAction->execute($filters, 20);

        return ViewFacade::make('report.sms', [
            'sms' => $sms,
            'filters' => $filters,
        ]);
    }

    /**
     * Display customer source report
     */
    public function customerSource(Request $request): View
    {
        $reportByMonth = $this->getCustomerSourceReportByMonth($request->all());
        $reportByYears = $this->getCustomerSourceReportByYears($request->all());

        return ViewFacade::make('report.customer-source', [
            'reportByMonth' => $reportByMonth,
            'reportByYears' => $reportByYears,
        ]);
    }

    /**
     * Display user point report
     */
    public function userPoint(Request $request, ?string $name = null): View
    {
        $data = $this->getUserPointReportAction->execute($name);

        return ViewFacade::make('report.user-point', [
            'name' => $name,
            'data' => $data,
        ]);
    }

    // Helper methods

    /**
     * @return array<int, array{name: string, posts_month: int, referrals_total: int, comp_total: float}>
     */
    private function getInfluencerTotalReport(?string $name): array
    {
        $query = User::query()
            ->where('is_influencer', true);

        if ($name) {
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'like', "%{$name}%")
                    ->orWhere('last_name', 'like', "%{$name}%");
            });
        }

        $influencers = $query->with(['contractLines'])->get();

        $data = [];

        foreach ($influencers as $influencer) {
            // Get total posts for current month (placeholder - would need relationship)
            $postsMonth = 0; // No smsPoolReports relationship available

            // Get total referrals (users who were invited by this influencer)
            $referralsTotal = User::where('id_inviter', $influencer->id)->count();

            // Get total compensation from contract lines (only non-terminated)
            $compTotal = $influencer->contractLines
                ->whereNull('terminated_at')
                ->sum(fn ($cl) => (float) ($cl->period_price ?? 0));

            $data[] = [
                'name' => "{$influencer->first_name} {$influencer->last_name}",
                'posts_month' => $postsMonth,
                'referrals_total' => $referralsTotal,
                'comp_total' => $compTotal,
            ];
        }

        return $data;
    }

    private function exportInfluencerTotalCsv(array $report): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="influencer-total-'.now()->format('Y-m-d').'.csv"',
        ];

        $callback = function () use ($report) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
            }

            // Write headers
            fputcsv($file, ['Influencer', 'Total Posts (Month)', 'Total Referrals', 'Total Compensation']);

            // Write data
            foreach ($report as $row) {
                fputcsv($file, [
                    $row['name'],
                    $row['posts_month'],
                    $row['referrals_total'],
                    number_format($row['comp_total'], 2),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * @return array<string, string>
     */
    private function getReportDates(): array
    {
        $dates = [];
        $current = Carbon::now();

        for ($i = 0; $i < 12; $i++) {
            $dates[$current->format('Y-m')] = $current->format('F Y');
            $current->subMonth();
        }

        return $dates;
    }

    /**
     * @return array<string, mixed>
     */
    private function getReferralReport(Carbon $date): array
    {
        // Implementation for referral report
        return ['data' => []];
    }

    private function getCompensation(int $alerts, int $referrals): float
    {
        // Implementation for compensation calculation
        return $alerts * $referrals * 0.1;
    }

    /**
     * @return array<string, string>
     */
    private function getMonthsList(): array
    {
        $months = [];
        $current = Carbon::now();

        for ($i = 0; $i < 12; $i++) {
            $months[$current->format('Y-m')] = $current->format('F Y');
            $current->subMonth();
        }

        return $months;
    }

    private function getActiveUserCount(Carbon $baseDate, Carbon $nowDate, string $lastDay): int
    {
        if ($baseDate->format('Y-m') === $nowDate->format('Y-m')) {
            return User::where('status', User::STATUS_ACTIVE)->count();
        }

        return User::whereRaw('(cancel_at IS NULL OR DATE(cancel_at) > ?)', [$lastDay])->count();
    }

    private function getActivePlanCount(Carbon $baseDate, Carbon $nowDate, string $lastDay, ?int $payInterval = null): int
    {
        $query = ContractLine::join('user', 'user.id', '=', 'contract_line.id_user')
            ->whereRaw('DATE(begin_at) <= ?', [$lastDay])
            ->whereRaw('(DATE(end_at) > ? OR end_at IS NULL)', [$lastDay])
            ->whereRaw('(terminated_at IS NULL OR terminated_at > ?)', [$lastDay]);

        if ($baseDate->format('Y-m') === $nowDate->format('Y-m')) {
            $query->where('user.status', User::STATUS_ACTIVE);
        } else {
            $query->whereRaw('(user.cancel_at IS NULL OR DATE(user.cancel_at) > ?)', [$lastDay]);
        }

        if ($payInterval) {
            $query->where('pay_interval', $payInterval);
        }

        return $query->count();
    }

    private function getSpsUsersCount(Carbon $baseDate, Carbon $nowDate, string $lastDay): int
    {
        if ($baseDate->format('Y-m') === $nowDate->format('Y-m')) {
            $count = DB::selectOne('
                SELECT COUNT(*) as cnt FROM (
                    SELECT DISTINCT id_user AS cnt FROM user
                    JOIN contract_line ON contract_line.id_user = user.id
                    WHERE is_sps_line = 1 
                        AND DATE(begin_at) <= ? 
                        AND (DATE(end_at) > ? OR end_at IS NULL) 
                        AND (terminated_at IS NULL OR terminated_at > ?)
                        AND user.status = ?
                ) as t
            ', [$lastDay, $lastDay, $lastDay, User::STATUS_ACTIVE]);
        } else {
            $count = DB::selectOne('
                SELECT COUNT(*) as cnt FROM (
                    SELECT DISTINCT id_user AS cnt FROM user
                    JOIN contract_line ON contract_line.id_user = user.id
                    WHERE is_sps_line = 1 
                        AND DATE(begin_at) <= ? 
                        AND (DATE(end_at) > ? OR end_at IS NULL) 
                        AND (terminated_at IS NULL OR terminated_at > ?)
                        AND (user.cancel_at IS NULL OR DATE(user.cancel_at) > ?)
                ) as t
            ', [$lastDay, $lastDay, $lastDay, $lastDay]);
        }

        return $count->cnt ?? 0;
    }

    /**
     * @return array<string, mixed>
     */
    private function getSpsData(Carbon $baseDate, Carbon $nowDate, string $lastDay): array
    {
        if ($baseDate->format('Y-m') === $nowDate->format('Y-m')) {
            return DB::select('
                SELECT COUNT(*) AS cnt, id_service, pay_interval 
                FROM contract_line
                JOIN user ON contract_line.id_user = user.id
                JOIN service s ON contract_line.id_service = s.id
                WHERE is_sps_line = 1 
                    AND id_service IS NOT NULL 
                    AND DATE(begin_at) <= ? 
                    AND (terminated_at IS NULL OR terminated_at > ?)
                    AND user.status = ?
                GROUP BY id_service, pay_interval 
                ORDER BY s.price
            ', [$lastDay, $lastDay, User::STATUS_ACTIVE]);
        }

        return DB::select('
            SELECT COUNT(*) AS cnt, id_service, pay_interval 
            FROM contract_line
            JOIN user ON contract_line.id_user = user.id
            JOIN service s ON contract_line.id_service = s.id
            WHERE is_sps_line = 1 
                AND id_service IS NOT NULL 
                AND DATE(begin_at) <= ? 
                AND (terminated_at IS NULL OR terminated_at > ?)
                AND (user.cancel_at IS NULL OR DATE(user.cancel_at) > ?)
            GROUP BY id_service, pay_interval 
            ORDER BY s.price
        ', [$lastDay, $lastDay, $lastDay]);
    }

    /**
     * @return array<string, mixed>
     */
    private function getNewPlansData(string $firstDay, string $lastDay): array
    {
        return DB::select('
            SELECT COUNT(*) AS cnt, id_service, pay_interval 
            FROM contract_line
            JOIN user ON contract_line.id_user = user.id
            JOIN service s ON contract_line.id_service = s.id
            WHERE id_service IS NOT NULL 
                AND (DATE(begin_at) >= ? AND DATE(begin_at) <= ?) 
                AND (DATE(user.created_at) >= ? AND DATE(user.created_at) <= ?)
            GROUP BY id_service, pay_interval 
            ORDER BY s.price
        ', [$firstDay, $lastDay, $firstDay, $lastDay]);
    }

    /**
     * @return array<string, mixed>
     */
    private function getNewPlansSpsData(string $firstDay, string $lastDay): array
    {
        return DB::select('
            SELECT COUNT(*) AS cnt, id_service, pay_interval 
            FROM contract_line
            JOIN user ON contract_line.id_user = user.id
            JOIN service s ON contract_line.id_service = s.id
            WHERE id_service IS NOT NULL 
                AND (DATE(begin_at) >= ? AND DATE(begin_at) <= ?) 
                AND (DATE(user.created_at) >= ? AND DATE(user.created_at) <= ?)
                AND is_sps_line = 1
            GROUP BY id_service, pay_interval 
            ORDER BY s.price
        ', [$firstDay, $lastDay, $firstDay, $lastDay]);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function getYesterdayPlansData(Carbon $baseDate, Carbon $nowDate, string $yesterDay): ?array
    {
        if ($baseDate->format('Y-m') !== $nowDate->format('Y-m')) {
            return null;
        }

        return DB::select('
            SELECT COUNT(*) AS cnt, id_service, pay_interval 
            FROM contract_line
            JOIN user ON contract_line.id_user = user.id
            JOIN service s ON contract_line.id_service = s.id
            WHERE id_service IS NOT NULL 
                AND DATE(begin_at) = ? 
                AND DATE(user.created_at) = ?
            GROUP BY id_service, pay_interval 
            ORDER BY s.price
        ', [$yesterDay, $yesterDay]);
    }

    /**
     * @return array<string, mixed>
     */
    private function getCurrentPlansData(Carbon $baseDate, Carbon $nowDate, string $lastDay): array
    {
        if ($baseDate->format('Y-m') === $nowDate->format('Y-m')) {
            return DB::select('
                SELECT COUNT(*) AS cnt, id_service, pay_interval 
                FROM contract_line
                JOIN user ON contract_line.id_user = user.id
                JOIN service s ON contract_line.id_service = s.id
                WHERE id_service IS NOT NULL 
                    AND DATE(begin_at) <= ? 
                    AND (DATE(end_at) > ? OR end_at IS NULL) 
                    AND (terminated_at IS NULL OR terminated_at > ?)
                    AND user.status = ?
                GROUP BY id_service, pay_interval 
                ORDER BY s.price DESC
            ', [$lastDay, $lastDay, $lastDay, User::STATUS_ACTIVE]);
        }

        return DB::select('
            SELECT COUNT(*) AS cnt, id_service, pay_interval 
            FROM contract_line
            JOIN user ON contract_line.id_user = user.id
            JOIN service s ON contract_line.id_service = s.id
            WHERE id_service IS NOT NULL 
                AND DATE(begin_at) <= ? 
                AND (DATE(end_at) > ? OR end_at IS NULL) 
                AND (terminated_at IS NULL OR terminated_at > ?)
                AND (user.cancel_at IS NULL OR DATE(user.cancel_at) > ?)
            GROUP BY id_service, pay_interval 
            ORDER BY s.price DESC
        ', [$lastDay, $lastDay, $lastDay, $lastDay]);
    }

    private function getPhoneCount(int $type, string $lastDay): int
    {
        return PhoneNumber::join('user', 'user.id', '=', 'phone_number.id_user')
            ->where('phone_number.type', $type)
            ->where('phone_number.is_active', 1)
            ->where('user.status', 1)
            ->whereRaw('DATE(phone_number.created_at) < ?', [$lastDay])
            ->whereRaw('(user.cancel_at IS NULL OR DATE(user.cancel_at) > ?)', [$lastDay])
            ->count();
    }

    /**
     * @return array<int, array{name: string, service: string, ended_at: string, reason: string}>
     */
    private function getEndedReport(?string $name): array
    {
        $query = ContractLine::with(['user', 'service'])
            ->where(function ($q) {
                $q->whereNotNull('terminated_at')
                    ->orWhere(function ($subQ) {
                        $subQ->whereNotNull('end_at')
                            ->where('end_at', '<', now());
                    });
            });

        if ($name) {
            $query->whereHas('user', function ($q) use ($name) {
                $q->where('first_name', 'like', "%{$name}%")
                    ->orWhere('last_name', 'like', "%{$name}%");
            });
        }

        $contractLines = $query->orderBy('terminated_at', 'desc')
            ->orderBy('end_at', 'desc')
            ->get();

        $data = [];

        foreach ($contractLines as $contractLine) {
            $endedAt = $contractLine->terminated_at ?? $contractLine->end_at;
            $reason = $contractLine->terminated_at ? 'Terminated' : 'Ended';

            $data[] = [
                'name' => $contractLine->user ? "{$contractLine->user->first_name} {$contractLine->user->last_name}" : 'Unknown',
                'service' => $contractLine->service ? $contractLine->service->name : 'N/A',
                'ended_at' => $endedAt ? $endedAt->format('Y-m-d') : '-',
                'reason' => $reason,
            ];
        }

        return $data;
    }

    /**
     * @param  array<int, array{name: string, service: string, ended_at: string, reason: string}>  $report
     */
    private function exportEndedCsv(array $report): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="ended-report-'.now()->format('Y-m-d').'.csv"',
        ];

        $callback = function () use ($report) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
            }

            // Write headers
            fputcsv($file, ['Name', 'Service', 'Ended At', 'Reason']);

            // Write data
            foreach ($report as $row) {
                fputcsv($file, [
                    $row['name'],
                    $row['service'],
                    $row['ended_at'],
                    $row['reason'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * @return array<int, array{name: string, type: string, count: int, date: string}>
     */
    private function getReactionReport(?string $name): array
    {
        $query = DB::table('sms_pool_reaction')
            ->join('sms_pool', 'sms_pool_reaction.id_sms_pool', '=', 'sms_pool.id')
            ->join('user', 'sms_pool.id_user', '=', 'user.id')
            ->leftJoin('reaction', 'sms_pool_reaction.id_reaction', '=', 'reaction.id')
            ->where('user.is_influencer', true)
            ->select(
                DB::raw('CONCAT(user.first_name, " ", user.last_name) as name'),
                DB::raw('COALESCE(reaction.emoji, "Unknown") as type'),
                DB::raw('COUNT(*) as count'),
                DB::raw('MAX(sms_pool_reaction.created_at) as date')
            )
            ->groupBy('user.id', 'sms_pool.id_user', 'reaction.id', 'reaction.emoji')
            ->orderBy('date', 'desc');

        if ($name) {
            $query->where(function ($q) use ($name) {
                $q->where('user.first_name', 'like', "%{$name}%")
                    ->orWhere('user.last_name', 'like', "%{$name}%");
            });
        }

        $results = $query->get();

        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'name' => $result->name ?? 'Unknown',
                'type' => $result->type ?? 'Unknown',
                'count' => (int) ($result->count ?? 0),
                'date' => $result->date ?? '-',
            ];
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    private function getCustomerSourceReportByMonth(array $params): array
    {
        // Implementation for customer source report by month
        return [];
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    private function getCustomerSourceReportByYears(array $params): array
    {
        // Implementation for customer source report by years
        return [];
    }
}
