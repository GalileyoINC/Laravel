<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

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
    /**
     * Display subscription products
     */
    public function subscription(Request $request): View
    {
        $query = Service::where('type', Service::TYPE_SUBSCRIBE);

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

        $subscriptions = $query->orderBy('id')->paginate(20);

        // Load custom parameters
        $customParams = Service::loadCustomParams();

        return ViewFacade::make('web.product.subscription', [
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
            return redirect()->route('web.product.edit-custom-subscription', $service);
        }

        return ViewFacade::make('web.product.edit-subscription', [
            'service' => $service,
        ]);
    }

    /**
     * Update subscription
     */
    public function updateSubscription(ProductSubscriptionRequest $request, Service $service): Response
    {
        try {
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

            return redirect()->route('web.product.subscription')
                ->with('success', 'Subscription updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update subscription: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show service settings
     */
    public function settings(): View
    {
        return ViewFacade::make('web.product.settings');
    }

    /**
     * Update service settings
     */
    public function updateSettings(ServiceSettingsRequest $request): Response
    {
        try {
            $validated = $request->validated();

            // Update service settings logic here
            // This would typically update configuration values

            return redirect()->route('web.product.subscription')
                ->with('success', 'Settings updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update settings: '.$e->getMessage()])
                ->withInput();
        }
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

        return ViewFacade::make('web.product.alert', [
            'alerts' => $alerts,
            'filters' => $request->only(['search', 'is_active']),
        ]);
    }

    /**
     * Show the form for editing alert
     */
    public function editAlert(Service $service): View
    {
        return ViewFacade::make('web.product.edit-subscription', [
            'service' => $service,
        ]);
    }

    /**
     * Update alert
     */
    public function updateAlert(ProductSubscriptionRequest $request, Service $service): Response
    {
        try {
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

            return redirect()->route('web.product.alert')
                ->with('success', 'Alert updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update alert: '.$e->getMessage()])
                ->withInput();
        }
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

        return ViewFacade::make('web.product.device', [
            'devices' => $devices,
            'filters' => $request->only(['search', 'is_active', 'price_min', 'price_max']),
        ]);
    }

    /**
     * Show the form for creating a new device
     */
    public function createDevice(): View
    {
        return ViewFacade::make('web.product.create-device');
    }

    /**
     * Store a newly created device
     */
    public function storeDevice(ProductDeviceRequest $request): Response
    {
        try {
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

            return redirect()->route('web.product.device')
                ->with('success', 'Device created successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create device: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified device
     */
    public function editDevice(Device $device): View
    {
        $device->load(['photos', 'devicePlans']);

        $activePlans = DevicePlan::where('is_active', 1)->paginate(20);

        return ViewFacade::make('web.product.edit-device', [
            'device' => $device,
            'activePlans' => $activePlans,
        ]);
    }

    /**
     * Update the specified device
     */
    public function updateDevice(ProductDeviceRequest $request, Device $device): Response
    {
        try {
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

            return redirect()->route('web.product.device')
                ->with('success', 'Device updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update device: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display device photos
     */
    public function photos(Device $device): View
    {
        $device->load('photos');

        return ViewFacade::make('web.product._photos', [
            'device' => $device,
        ]);
    }

    /**
     * Delete device photo
     */
    public function deletePhoto(Request $request): Response
    {
        try {
            $photoId = $request->get('id');

            // Delete photo logic here

            return response()->json(['success' => 'Photo deleted successfully']);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete photo: '.$e->getMessage()], 500);
        }
    }

    /**
     * Set main photo
     */
    public function setMainPhoto(Request $request): Response
    {
        try {
            $photoId = $request->get('id');

            // Set main photo logic here

            return response()->json(['success' => 'Main photo set successfully']);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to set main photo: '.$e->getMessage()], 500);
        }
    }

    /**
     * Upload device photo
     */
    public function uploadPhoto(Request $request, Device $device): Response
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $file = $request->file('file');
            $path = $file->store('devices', 'public');

            return response()->json([
                'initialPreview' => [Storage::url($path)],
                'initialPreviewConfig' => [[
                    'caption' => $file->getClientOriginalName(),
                    'url' => route('web.product.delete-photo'),
                    'key' => 'temp_key',
                    'type' => 'image',
                ]],
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to upload photo: '.$e->getMessage()], 500);
        }
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

        return ViewFacade::make('web.product.device-plan', [
            'plans' => $plans,
            'filters' => $request->only(['search', 'is_active']),
        ]);
    }

    /**
     * Show the form for creating a new device plan
     */
    public function createPlan(): View
    {
        return ViewFacade::make('web.product.create-device-plan');
    }

    /**
     * Store a newly created device plan
     */
    public function storePlan(ProductDevicePlanRequest $request): Response
    {
        try {
            $validated = $request->validated();

            $data = [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'is_active' => $validated['is_active'] ?? true,
            ];

            $plan = DevicePlan::create($data);

            return redirect()->route('web.product.plan')
                ->with('success', 'Device plan created successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create device plan: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified device plan
     */
    public function editPlan(DevicePlan $plan): View
    {
        return ViewFacade::make('web.product.edit-device-plan', [
            'plan' => $plan,
        ]);
    }

    /**
     * Update the specified device plan
     */
    public function updatePlan(ProductDevicePlanRequest $request, DevicePlan $plan): Response
    {
        try {
            $validated = $request->validated();

            $data = [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'is_active' => $validated['is_active'] ?? $plan->is_active,
            ];

            $plan->update($data);

            return redirect()->route('web.product.plan')
                ->with('success', 'Device plan updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update device plan: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Attach plan to device
     */
    public function attachPlan(Request $request, DevicePlan $plan, Device $device): Response
    {
        try {
            $validated = $request->validate([
                'price' => ['nullable', 'numeric', 'min:0'],
                'is_default' => ['nullable', 'boolean'],
            ]);

            // Attach plan logic here

            return redirect()->route('web.product.edit-device', $device)
                ->with('success', 'Plan attached successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to attach plan: '.$e->getMessage()]);
        }
    }

    /**
     * Detach plan from device
     */
    public function detachPlan(DevicePlan $plan, Device $device): Response
    {
        try {
            // Detach plan logic here

            return redirect()->route('web.product.edit-device', $device)
                ->with('success', 'Plan detached successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to detach plan: '.$e->getMessage()]);
        }
    }
}
