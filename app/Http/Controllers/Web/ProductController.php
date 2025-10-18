<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Product\GetProductAlertsAction;
use App\Domain\Actions\Product\GetProductListAction;
use App\Domain\Actions\Product\ProcessApplePurchaseAction;
use App\Domain\DTOs\Product\ProductListRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Web\ProductDevicePlanRequest;
use App\Http\Requests\Product\Web\ProductDeviceRequest;
use App\Http\Requests\Product\Web\ProductSubscriptionRequest;
use App\Http\Requests\Service\Web\ServiceSettingsRequest;
use App\Models\Device\Device;
use App\Models\Device\DevicePlan;
use App\Models\System\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly GetProductListAction $getProductListAction,
        private readonly GetProductAlertsAction $getProductAlertsAction,
        private readonly ProcessApplePurchaseAction $processApplePurchaseAction
    ) {}

    /**
     * Display subscription products
     */
    public function subscription(Request $request): View
    {
        $dto = new ProductListRequestDTO(
            limit: $request->get('limit', 20),
            offset: $request->get('offset', 0),
            filter: [
                'type' => Service::TYPE_SUBSCRIBE,
                'search' => $request->get('search'),
                'is_active' => $request->get('is_active'),
                'price_min' => $request->get('price_min'),
                'price_max' => $request->get('price_max'),
            ]
        );

        $subscriptions = $this->getProductListAction->execute($dto->toArray());

        $customParams = Service::loadCustomParams();

        return ViewFacade::make('product.subscription', [
            'subscriptions' => $subscriptions,
            'filters' => $request->only(['search', 'is_active', 'price_min', 'price_max']),
            'customParams' => $customParams,
        ]);
    }

    /**
     * Show the form for editing subscription
     */
    public function editSubscription(Service $service): View
    {
        if ($service->isCustom()) {
            return redirect()->route('product.edit-custom-subscription', $service);
        }

        return ViewFacade::make('product.edit-subscription', [
            'service' => $service,
        ]);
    }

    /**
     * Update subscription
     */
    public function updateSubscription(ProductSubscriptionRequest $request, Service $service): Response
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'special_price' => $validated['special_price'] ?? null,
            'is_special_price' => $validated['is_special_price'] ?? false,
            'is_active' => $validated['is_active'] ?? true,
            'settings' => $validated['settings'] ?? [],
        ];

        $service->update($data);

        return redirect()->route('product.subscription')
            ->with('success', 'Subscription updated successfully.');
    }

    /**
     * Show service settings
     */
    public function settings(): View
    {
        return ViewFacade::make('product.settings');
    }

    /**
     * Update service settings
     */
    public function updateSettings(ServiceSettingsRequest $request): Response
    {
        $validated = $request->validated();

        return redirect()->route('product.subscription')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Display alert products
     */
    public function alert(Request $request): View
    {
        $query = Service::where('type', Service::TYPE_ALERT);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        $alerts = $query->orderBy('id')->paginate(20);

        return ViewFacade::make('product.alert', [
            'alerts' => $alerts,
            'filters' => $request->only(['search', 'is_active']),
        ]);
    }

    /**
     * Show the form for editing alert
     */
    public function editAlert(Service $service): View
    {
        return ViewFacade::make('product.edit-subscription', [
            'service' => $service,
        ]);
    }

    /**
     * Update alert
     */
    public function updateAlert(ProductSubscriptionRequest $request, Service $service): Response
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'special_price' => $validated['special_price'] ?? null,
            'is_special_price' => $validated['is_special_price'] ?? false,
            'is_active' => $validated['is_active'] ?? true,
            'settings' => $validated['settings'] ?? [],
        ];

        $service->update($data);

        return redirect()->route('product.alert')
            ->with('success', 'Alert updated successfully.');
    }

    /**
     * Display devices
     */
    public function device(Request $request): View
    {
        $query = Device::with('mainPhoto');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->get('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->get('price_max'));
        }

        $devices = $query->orderBy('id')->paginate(20);

        return ViewFacade::make('product.device', [
            'devices' => $devices,
            'filters' => $request->only(['search', 'is_active', 'price_min', 'price_max']),
        ]);
    }

    /**
     * Show the form for creating a new device
     */
    public function createDevice(): View
    {
        return ViewFacade::make('product.create-device');
    }

    /**
     * Store a newly created device
     */
    public function storeDevice(ProductDeviceRequest $request): Response
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'special_price' => $validated['special_price'] ?? null,
            'is_special_price' => $validated['is_special_price'] ?? false,
            'is_active' => $validated['is_active'] ?? true,
        ];

        $device = Device::create($data);

        return redirect()->route('product.device')
            ->with('success', 'Device created successfully.');
    }

    /**
     * Show the form for editing the specified device
     */
    public function editDevice(Device $device): View
    {
        $device->load(['photos', 'devicePlans']);

        $activePlans = DevicePlan::where('is_active', 1)->paginate(20);

        return ViewFacade::make('product.edit-device', [
            'device' => $device,
            'activePlans' => $activePlans,
        ]);
    }

    /**
     * Update the specified device
     */
    public function updateDevice(ProductDeviceRequest $request, Device $device): Response
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'special_price' => $validated['special_price'] ?? null,
            'is_special_price' => $validated['is_special_price'] ?? false,
            'is_active' => $validated['is_active'] ?? $device->is_active,
        ];

        $device->update($data);

        return redirect()->route('product.device')
            ->with('success', 'Device updated successfully.');
    }

    /**
     * Display device photos
     */
    public function photos(Device $device): View
    {
        $device->load('photos');

        return ViewFacade::make('product._photos', [
            'device' => $device,
        ]);
    }

    /**
     * Delete device photo
     */
    public function deletePhoto(Request $request): Response
    {
            $photoId = $request->get('id');

            // Delete photo logic here

            return response()->json(['success' => 'Photo deleted successfully']);
    }

    /**
     * Set main photo
     */
    public function setMainPhoto(Request $request): Response
    {
            $photoId = $request->get('id');

            // Set main photo logic here

            return response()->json(['success' => 'Main photo set successfully']);
    }

    /**
     * Upload device photo
     */
    public function uploadPhoto(Request $request, Device $device): Response
    {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $file = $request->file('file');
            $path = $file->store('devices', 'public');

            return response()->json([
                'initialPreview' => [Storage::url($path)],
                'initialPreviewConfig' => [[
                    'caption' => $file->getClientOriginalName(),
                    'url' => route('product.delete-photo'),
                    'key' => 'temp_key',
                    'type' => 'image',
                ]],
            ]);
    }

    /**
     * Display device plans
     */
    public function plan(Request $request): View
    {
        $query = DevicePlan::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        $plans = $query->orderBy('id')->paginate(20);

        return ViewFacade::make('product.device-plan', [
            'plans' => $plans,
            'filters' => $request->only(['search', 'is_active']),
        ]);
    }

    /**
     * Show the form for creating a new device plan
     */
    public function createPlan(): View
    {
        return ViewFacade::make('product.create-device-plan');
    }

    /**
     * Store a newly created device plan
     */
    public function storePlan(ProductDevicePlanRequest $request): Response
    {
            $validated = $request->validated();

            $data = [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'is_active' => $validated['is_active'] ?? true,
            ];

            $plan = DevicePlan::create($data);

            return redirect()->route('product.plan')
                ->with('success', 'Device plan created successfully.');
    }

    /**
     * Show the form for editing the specified device plan
     */
    public function editPlan(DevicePlan $plan): View
    {
        return ViewFacade::make('product.edit-device-plan', [
            'plan' => $plan,
        ]);
    }

    /**
     * Update the specified device plan
     */
    public function updatePlan(ProductDevicePlanRequest $request, DevicePlan $plan): Response
    {
            $validated = $request->validated();

            $data = [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'is_active' => $validated['is_active'] ?? $plan->is_active,
            ];

            $plan->update($data);

            return redirect()->route('product.plan')
                ->with('success', 'Device plan updated successfully.');
    }

    /**
     * Attach plan to device
     */
    public function attachPlan(Request $request, DevicePlan $plan, Device $device): Response
    {
            $validated = $request->validate([
                'price' => ['nullable', 'numeric', 'min:0'],
                'is_default' => ['nullable', 'boolean'],
            ]);

            // Attach plan logic here

            return redirect()->route('product.edit-device', $device)
                ->with('success', 'Plan attached successfully.');
    }

    /**
     * Detach plan from device
     */
    public function detachPlan(DevicePlan $plan, Device $device): Response
    {
            // Detach plan logic here

            return redirect()->route('product.edit-device', $device)
                ->with('success', 'Plan detached successfully.');
    }
}
