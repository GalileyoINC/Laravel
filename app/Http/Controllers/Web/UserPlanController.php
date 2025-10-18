<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Web\UserPlanRequest;
use App\Models\Finance\Service;
use App\Models\User\UserPlan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class UserPlanController extends Controller
{
    /**
     * Display Unpaid User Plans
     */
    public function unpaid(Request $request): View
    {
        $expDate = $request->get('exp_date', 30);

        $query = UserPlan::with(['user', 'service'])
            ->whereHas('user', function ($userQuery) {
                $userQuery->where('status', 1); // Active users only
            })
            ->where('exp_date', '<=', now()->subDays($expDate));

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by service
        if ($request->filled('id_service')) {
            $query->where('id_service', $request->get('id_service'));
        }

        // Filter by pay interval
        if ($request->filled('pay_interval')) {
            $query->where('pay_interval', $request->get('pay_interval'));
        }

        $userPlans = $query->orderBy('exp_date', 'asc')->paginate(20);

        // Get services for filter dropdown
        $services = Service::where('type', Service::TYPE_SUBSCRIBE ?? 1)
            ->where('is_active', true)
            ->pluck('name', 'id');

        return ViewFacade::make('user-plan.unpaid', [
            'userPlans' => $userPlans,
            'services' => $services,
            'expDate' => $expDate,
            'filters' => $request->only(['search', 'id_service', 'pay_interval', 'exp_date']),
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
    public function update(UserPlanRequest $request, UserPlan $userPlan): Response
    {
            $userPlan->update($request->validated());

            return redirect()->route('user.show', $userPlan->user)
                ->with('success', 'User plan updated successfully.');
    }

    /**
     * Export Unpaid User Plans to CSV
     */
    public function exportUnpaid(Request $request): Response
    {
            $expDate = $request->get('exp_date', 30);

            $query = UserPlan::with(['user', 'service'])
                ->whereHas('user', function ($userQuery) {
                    $userQuery->where('status', 1); // Active users only
                })
                ->where('exp_date', '<=', now()->subDays($expDate));

            // Apply same filters as unpaid
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('id_service')) {
                $query->where('id_service', $request->get('id_service'));
            }

            if ($request->filled('pay_interval')) {
                $query->where('pay_interval', $request->get('pay_interval'));
            }

            $userPlans = $query->orderBy('exp_date', 'asc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'First Name', 'Last Name', 'Email', 'Service', 'Pay Interval', 'Exp Date'];

            foreach ($userPlans as $userPlan) {
                $csvData[] = [
                    $userPlan->id,
                    $userPlan->user ? $userPlan->user->first_name : '',
                    $userPlan->user ? $userPlan->user->last_name : '',
                    $userPlan->user ? $userPlan->user->email : '',
                    $userPlan->service ? $userPlan->service->name : '',
                    $userPlan->pay_interval,
                    $userPlan->exp_date->format('Y-m-d'),
                ];
            }

            $filename = 'unpaid_user_plans_'.now()->format('Y-m-d_H-i-s').'.csv';

            return response()->streamDownload(function () use ($csvData) {
                $file = fopen('php://output', 'w');
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
