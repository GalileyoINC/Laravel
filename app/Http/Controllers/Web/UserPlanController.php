<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\UserPlan\ExportUnpaidUserPlansToCsvAction;
use App\Domain\Actions\UserPlan\GetUnpaidUserPlansAction;
use App\Domain\Actions\UserPlan\UpdateUserPlanAction;
use App\Domain\DTOs\UserPlan\UserPlanUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Web\UserPlanRequest;
use App\Http\Requests\Users\Web\UserPlanUnpaidIndexRequest;
use App\Models\Finance\Service;
use App\Models\User\UserPlan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View as ViewFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserPlanController extends Controller
{
    public function __construct(
        private readonly GetUnpaidUserPlansAction $getUnpaidUserPlansAction,
        private readonly ExportUnpaidUserPlansToCsvAction $exportUnpaidUserPlansToCsvAction,
        private readonly UpdateUserPlanAction $updateUserPlanAction,
    ) {}

    /**
     * Display Unpaid User Plans
     */
    public function unpaid(UserPlanUnpaidIndexRequest $request): View
    {
        $filters = $request->validated();
        $userPlans = $this->getUnpaidUserPlansAction->execute($filters, 20);

        // Get services for filter dropdown
        $services = Service::where('type', Service::TYPE_SUBSCRIBE)
            ->where('is_active', true)
            ->pluck('name', 'id');

        return ViewFacade::make('user-plan.unpaid', [
            'userPlans' => $userPlans,
            'services' => $services,
            'expDate' => $filters['exp_date'] ?? 30,
            'filters' => $filters,
        ]);
    }

    /**
     * Show User Plan Edit Form
     */
    public function edit(UserPlan $userPlan): View
    {
        return ViewFacade::make('user-plan.edit', [
            'userPlan' => $userPlan,
        ]);
    }

    /**
     * Update User Plan
     */
    public function update(UserPlanRequest $request, UserPlan $userPlan): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new UserPlanUpdateDTO(
            id: $userPlan->id,
            endAt: (string) $validated['end_at'],
        );

        $this->updateUserPlanAction->execute($dto);

        return redirect()->route('user.show', $userPlan->user)
            ->with('success', 'User plan updated successfully.');
    }

    /**
     * Export Unpaid User Plans to CSV
     */
    public function exportUnpaid(UserPlanUnpaidIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportUnpaidUserPlansToCsvAction->execute($filters);

        $filename = 'unpaid_user_plans_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
            }
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
