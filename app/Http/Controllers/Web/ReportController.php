<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Report\GetSmsReportListAction;
use App\Domain\Actions\Report\GetSoldDevicesListAction;
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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View as ViewFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(
        private readonly GetSoldDevicesListAction $getSoldDevicesListAction,
        private readonly GetSmsReportListAction $getSmsReportListAction,
    ) {}

    /**
     * Display login statistics
     */
    public function loginStatistic(Request $request, ?string $date = null): View
    {
        // This would typically use a LoginStatisticReport model
        // For now, we'll create a basic structure

        if ($date) {
            return ViewFacade::make('report.login-statistic-by-day', [
                'date' => $date,
                'data' => $this->getLoginStatisticByDay($request, $date),
            ]);
        }

        return ViewFacade::make('report.login-statistic', [
            'data' => $this->getLoginStatistic($request),
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
    public function influencerTotal(Request $request, ?string $name = null, bool $csv = false): View|Response
    {
        $report = $this->getInfluencerTotalReport($name);

        if ($csv) {
            return $this->exportInfluencerTotalCsv($report);
        }

        // TODO: implement influencer total report search logic
        $data = [];

        return ViewFacade::make('report.influencer-total', [
            'name' => $name,
            'report' => $report,
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
    public function ended(Request $request, ?string $name = null, bool $csv = false): View|Response
    {
        $report = $this->getEndedReport($name);

        if ($csv) {
            return $this->exportEndedCsv($report);
        }

        // TODO: implement ended report search logic
        $data = [];
        $services = Service::all()->keyBy('id')->toArray();

        return ViewFacade::make('report.ended', [
            'name' => $name,
            'report' => $report,
            'data' => $data,
            'services' => $services,
        ]);
    }

    /**
     * Display reaction report
     */
    public function reaction(Request $request, ?string $name = null): View
    {
        $report = $this->getReactionReport($name);
        // TODO: implement reaction report search logic
        $data = [];

        return ViewFacade::make('report.reaction', [
            'name' => $name,
            'report' => $report,
            'data' => $data,
        ]);
    }

    /**
     * Display devices plans report
     */
    public function devicesPlans(Request $request, ?string $date = null): View
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $query = DB::table('invoice_line')
            ->join('invoice', 'invoice_line.id_invoice', '=', 'invoice.id')
            ->join('user', 'invoice.id_user', '=', 'user.id')
            ->where('invoice_line.type', 'device')
            ->whereDate('invoice.created_at', $date->format('Y-m-d'))
            ->select('invoice_line.*', 'user.first_name', 'user.last_name', 'invoice.created_at');

        $devices = $query->orderBy('invoice.created_at', 'desc')->paginate(20);

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
        $report = $this->getUserPointReport($name);
        // TODO: implement user point report search logic
        $data = [];

        return ViewFacade::make('report.user-point', [
            'name' => $name,
            'report' => $report,
            'data' => $data,
        ]);
    }

    // Helper methods
    /**
     * @return array<string, mixed>
     */
    private function getLoginStatistic(Request $request): array
    {
        // Implementation for login statistics
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    private function getLoginStatisticByDay(Request $request, string $date): array
    {
        // Implementation for login statistics by day
        return [];
    }

    private function getInfluencerTotalReport(?string $name): object
    {
        // Implementation for influencer total report
        return (object) [];
    }

    private function exportInfluencerTotalCsv(object $report): Response
    {
        // Implementation for CSV export
        return response('', 200);
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
        // Implementation for SPS users count
        return 0;
    }

    /**
     * @return array<string, mixed>
     */
    private function getSpsData(Carbon $baseDate, Carbon $nowDate, string $lastDay): array
    {
        // Implementation for SPS data
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    private function getNewPlansData(string $firstDay, string $lastDay): array
    {
        // Implementation for new plans data
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    private function getNewPlansSpsData(string $firstDay, string $lastDay): array
    {
        // Implementation for new plans SPS data
        return [];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function getYesterdayPlansData(Carbon $baseDate, Carbon $nowDate, string $yesterDay): ?array
    {
        if ($baseDate->format('Y-m') !== $nowDate->format('Y-m')) {
            return null;
        }

        // Implementation for yesterday plans data
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    private function getCurrentPlansData(Carbon $baseDate, Carbon $nowDate, string $lastDay): array
    {
        // Implementation for current plans data
        return [];
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

    private function getEndedReport(?string $name): object
    {
        // Implementation for ended report
        return (object) [];
    }

    private function exportEndedCsv(object $report): Response
    {
        // Implementation for CSV export
        return response('', 200);
    }

    private function getReactionReport(?string $name): object
    {
        // Implementation for reaction report
        return (object) [];
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

    private function getUserPointReport(?string $name): object
    {
        // Implementation for user point report
        return (object) [];
    }
}
