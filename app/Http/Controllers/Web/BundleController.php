<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Bundle\CreateBundleAction;
use App\Domain\Actions\Bundle\GetBundleListAction;
use App\Domain\Actions\Bundle\UpdateBundleAction;
use App\Domain\DTOs\Bundle\BundleListRequestDTO;
use App\Domain\DTOs\Bundle\CreateBundleDTO;
use App\Domain\DTOs\Bundle\UpdateBundleDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bundle\Web\BundleRequest;
use App\Models\Finance\Bundle;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class BundleController extends Controller
{
    public function __construct(
        private readonly CreateBundleAction $createBundleAction,
        private readonly GetBundleListAction $getBundleListAction,
        private readonly UpdateBundleAction $updateBundleAction
    ) {}

    /**
     * Display a listing of bundles
     */
    public function index(Request $request): View
    {
            $dto = new BundleListRequestDTO(
                page: $request->get('page', 1),
                limit: $request->get('limit', 20),
                search: $request->get('search'),
                status: $request->get('status')
            );

            $result = $this->getBundleListAction->execute($dto->toArray());
            $bundles = $result->getData()->data;

            return ViewFacade::make('bundle.index', [
                'bundles' => $bundles,
                'filters' => $request->only(['search', 'status']),
            ]);
    }

    /**
     * Show the form for creating a new bundle
     */
    public function create(): View
    {
        return ViewFacade::make('bundle.create');
    }

    /**
     * Store a newly created bundle
     */
    public function store(BundleRequest $request): RedirectResponse
    {
            $validated = $request->validated();

            $dto = new CreateBundleDTO(
                title: $validated['title'],
                type: $validated['type'] ?? 1,
                payInterval: $validated['pay_interval'] ?? 1,
                isActive: $validated['is_active'] ?? true,
                total: $validated['total']
            );

            $result = $this->createBundleAction->execute($dto);

            if ($result->getData()->success) {
                return Redirect::to(route('bundle.index'))
                    ->with('success', 'Bundle created successfully.');
            }

            return Redirect::back()
                ->withErrors(['error' => $result->getData()->message ?? 'Failed to create bundle.'])
                ->withInput();
    }

    /**
     * Display the specified bundle
     */
    public function show(Bundle $bundle): View
    {
        $bundle->load(['bundle_items', 'invoice_lines']);

        return ViewFacade::make('bundle.show', [
            'bundle' => $bundle,
        ]);
    }

    /**
     * Show the form for editing the specified bundle
     */
    public function edit(Bundle $bundle): View
    {
        return ViewFacade::make('bundle.edit', [
            'bundle' => $bundle,
        ]);
    }

    /**
     * Update the specified bundle
     */
    public function update(BundleRequest $request, Bundle $bundle): RedirectResponse
    {
            $validated = $request->validated();

            $dto = new UpdateBundleDTO(
                id: $bundle->id,
                title: $validated['title'],
                type: $validated['type'] ?? $bundle->type,
                payInterval: $validated['pay_interval'] ?? $bundle->pay_interval,
                isActive: $validated['is_active'] ?? $bundle->is_active,
                total: $validated['total']
            );

            $result = $this->updateBundleAction->execute($dto);

            if ($result->getData()->success) {
                return Redirect::to(route('bundle.index'))
                    ->with('success', 'Bundle updated successfully.');
            }

            return Redirect::back()
                ->withErrors(['error' => $result->getData()->message ?? 'Failed to update bundle.'])
                ->withInput();
    }

    /**
     * Remove the specified bundle
     */
    public function destroy(Bundle $bundle): RedirectResponse
    {
            // Check if bundle has associated items
            if ($bundle->bundle_items()->count() > 0) {
                return Redirect::back()
                    ->withErrors(['error' => 'Cannot delete bundle with associated items.']);
            }

            $bundle->delete();

            return Redirect::to(route('bundle.index'))
                ->with('success', 'Bundle deleted successfully.');
    }

    /**
     * Toggle bundle status
     */
    public function toggleStatus(Bundle $bundle): RedirectResponse
    {
            $bundle->update([
                'is_active' => ! $bundle->is_active,
            ]);

            $status = $bundle->is_active ? 'activated' : 'deactivated';

            return Redirect::back()
                ->with('success', "Bundle {$status} successfully.");
    }

    /**
     * Get device data for bundle creation
     */
    public function getDeviceData(Request $request)
    {
            $deviceId = $request->input('idDevice');

            $action = new \App\Domain\Actions\Bundle\GetBundleDeviceDataAction(
                app(\App\Domain\Services\Bundle\BundleServiceInterface::class)
            );

            $dto = new \App\Domain\DTOs\Bundle\BundleDeviceDataRequestDTO(
                idDevice: $deviceId
            );

            $result = $action->execute($dto);

            return response()->json($result->getData());
    }
}
