<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Product\AttachPlanToDeviceAction;
use App\Domain\Actions\Product\CreateDeviceAction;
use App\Domain\Actions\Product\CreateDevicePlanAction;
use App\Domain\Actions\Product\DeleteDevicePhotoAction;
use App\Domain\Actions\Product\DetachPlanFromDeviceAction;
use App\Domain\Actions\Product\GetAlertServiceListAction;
use App\Domain\Actions\Product\GetDevicePlanListAction;
use App\Domain\Actions\Product\GetProductDeviceListAction;
use App\Domain\Actions\Product\GetSubscriptionServiceListAction;
use App\Domain\Actions\Product\SetMainDevicePhotoAction;
use App\Domain\Actions\Product\UpdateDeviceAction;
use App\Domain\Actions\Product\UpdateDevicePlanAction;
use App\Domain\Actions\Product\UpdateSubscriptionServiceAction;
use App\Domain\Actions\Product\UploadDevicePhotoAction;
use App\Domain\DTOs\Product\AttachPlanToDeviceDTO;
use App\Domain\DTOs\Product\DetachPlanFromDeviceDTO;
use App\Domain\DTOs\Product\DeviceCreateDTO;
use App\Domain\DTOs\Product\DevicePhotoDeleteDTO;
use App\Domain\DTOs\Product\DevicePhotoSetMainDTO;
use App\Domain\DTOs\Product\DevicePhotoUploadDTO;
use App\Domain\DTOs\Product\DevicePlanCreateDTO;
use App\Domain\DTOs\Product\DevicePlanUpdateDTO;
use App\Domain\DTOs\Product\DeviceUpdateDTO;
use App\Domain\DTOs\Product\SubscriptionUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Web\ProductAlertIndexRequest;
use App\Http\Requests\Product\Web\ProductDeviceIndexRequest;
use App\Http\Requests\Product\Web\ProductDevicePlanIndexRequest;
use App\Http\Requests\Product\Web\ProductDevicePlanRequest;
use App\Http\Requests\Product\Web\ProductDeviceRequest;
use App\Http\Requests\Product\Web\ProductSubscriptionIndexRequest;
use App\Http\Requests\Product\Web\ProductSubscriptionRequest;
use App\Http\Requests\Service\Web\ServiceSettingsRequest;
use App\Models\Device\Device;
use App\Models\Device\DevicePlan;
use App\Models\Finance\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;

class ProductController extends Controller
{
    public function __construct(
        private readonly GetSubscriptionServiceListAction $getSubscriptionServiceListAction,
        private readonly GetAlertServiceListAction $getAlertServiceListAction,
        private readonly GetProductDeviceListAction $getProductDeviceListAction,
        private readonly GetDevicePlanListAction $getDevicePlanListAction,
        private readonly CreateDeviceAction $createDeviceAction,
        private readonly UpdateDeviceAction $updateDeviceAction,
        private readonly CreateDevicePlanAction $createDevicePlanAction,
        private readonly UpdateDevicePlanAction $updateDevicePlanAction,
        private readonly AttachPlanToDeviceAction $attachPlanToDeviceAction,
        private readonly DetachPlanFromDeviceAction $detachPlanFromDeviceAction,
        private readonly UploadDevicePhotoAction $uploadDevicePhotoAction,
        private readonly DeleteDevicePhotoAction $deleteDevicePhotoAction,
        private readonly SetMainDevicePhotoAction $setMainDevicePhotoAction,
        private readonly UpdateSubscriptionServiceAction $updateSubscriptionServiceAction,
    ) {}

    /**
     * Display subscription products
     */
    public function subscription(ProductSubscriptionIndexRequest $request): View
    {
        $filters = $request->validated();
        $subscriptions = $this->getSubscriptionServiceListAction->execute($filters, 20);

        $customParams = Service::loadCustomParams();

        return ViewFacade::make('product.subscription', [
            'subscriptions' => $subscriptions,
            'filters' => $filters,
            'customParams' => $customParams,
        ]);
    }

    /**
     * Show the form for editing subscription
     */
    public function editSubscription(Service $service): View|RedirectResponse
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
    public function updateSubscription(ProductSubscriptionRequest $request, Service $service): RedirectResponse
    {
        $validated = $request->validated();
        $dto = new SubscriptionUpdateDTO(
            serviceId: $service->id,
            name: $validated['name'],
            description: $validated['description'] ?? null,
            price: (float) $validated['price'],
            specialPrice: isset($validated['special_price']) ? (float) $validated['special_price'] : null,
            isSpecialPrice: (bool) ($validated['is_special_price'] ?? false),
            isActive: (bool) ($validated['is_active'] ?? true),
            settings: $validated['settings'] ?? [],
        );

        $this->updateSubscriptionServiceAction->execute($dto);

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
    public function updateSettings(ServiceSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        return redirect()->route('product.subscription')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Display alert products
     */
    public function alert(ProductAlertIndexRequest $request): View
    {
        $filters = $request->validated();
        $alerts = $this->getAlertServiceListAction->execute($filters, 20);

        return ViewFacade::make('product.alert', [
            'alerts' => $alerts,
            'filters' => $filters,
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
    public function updateAlert(ProductSubscriptionRequest $request, Service $service): RedirectResponse
    {
        $validated = $request->validated();
        $dto = new SubscriptionUpdateDTO(
            serviceId: $service->id,
            name: $validated['name'],
            description: $validated['description'] ?? null,
            price: (float) $validated['price'],
            specialPrice: isset($validated['special_price']) ? (float) $validated['special_price'] : null,
            isSpecialPrice: (bool) ($validated['is_special_price'] ?? false),
            isActive: (bool) ($validated['is_active'] ?? true),
            settings: $validated['settings'] ?? [],
        );

        $this->updateSubscriptionServiceAction->execute($dto);

        return redirect()->route('product.alert')
            ->with('success', 'Alert updated successfully.');
    }

    /**
     * Display devices
     */
    public function device(ProductDeviceIndexRequest $request): View
    {
        $filters = $request->validated();
        $devices = $this->getProductDeviceListAction->execute($filters, 20);

        return ViewFacade::make('product.device', [
            'devices' => $devices,
            'filters' => $filters,
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
    public function storeDevice(ProductDeviceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $dto = new DeviceCreateDTO(
            name: $validated['name'],
            description: $validated['description'] ?? null,
            price: (float) $validated['price'],
            specialPrice: isset($validated['special_price']) ? (float) $validated['special_price'] : null,
            isSpecialPrice: (bool) ($validated['is_special_price'] ?? false),
            isActive: (bool) ($validated['is_active'] ?? true),
        );

        $this->createDeviceAction->execute($dto);

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
    public function updateDevice(ProductDeviceRequest $request, Device $device): RedirectResponse
    {
        $validated = $request->validated();
        $dto = new DeviceUpdateDTO(
            id: $device->id,
            name: $validated['name'],
            description: $validated['description'] ?? null,
            price: (float) $validated['price'],
            specialPrice: isset($validated['special_price']) ? (float) $validated['special_price'] : null,
            isSpecialPrice: (bool) ($validated['is_special_price'] ?? false),
            isActive: (bool) ($validated['is_active'] ?? (bool) $device->getAttribute('is_active')),
        );

        $this->updateDeviceAction->execute($dto);

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
    public function deletePhoto(Request $request): JsonResponse
    {
        $photoId = (string) $request->get('id');
        $dto = new DevicePhotoDeleteDTO(photoId: $photoId);
        $_ = $this->deleteDevicePhotoAction->execute($dto);

        return response()->json(['success' => 'Photo deleted successfully']);
    }

    /**
     * Set main photo
     */
    public function setMainPhoto(Request $request): JsonResponse
    {
        $photoId = (string) $request->get('id');
        $dto = new DevicePhotoSetMainDTO(photoId: $photoId);
        $_ = $this->setMainDevicePhotoAction->execute($dto);

        return response()->json(['success' => 'Main photo set successfully']);
    }

    /**
     * Upload device photo
     */
    public function uploadPhoto(Request $request, Device $device): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $file = $request->file('file');
        $dto = new DevicePhotoUploadDTO(deviceId: $device->id, file: $file);
        $result = $this->uploadDevicePhotoAction->execute($dto);

        return response()->json([
            'initialPreview' => [$result['url']],
            'initialPreviewConfig' => [[
                'caption' => $result['caption'],
                'url' => route('product.delete-photo'),
                'key' => $result['path'],
                'type' => 'image',
            ]],
        ]);
    }

    /**
     * Display device plans
     */
    public function plan(ProductDevicePlanIndexRequest $request): View
    {
        $filters = $request->validated();
        $plans = $this->getDevicePlanListAction->execute($filters, 20);

        return ViewFacade::make('product.device-plan', [
            'plans' => $plans,
            'filters' => $filters,
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
    public function storePlan(ProductDevicePlanRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $dto = new DevicePlanCreateDTO(
            name: $validated['name'],
            description: $validated['description'] ?? null,
            price: (float) $validated['price'],
            isActive: (bool) ($validated['is_active'] ?? true),
        );

        $this->createDevicePlanAction->execute($dto);

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
    public function updatePlan(ProductDevicePlanRequest $request, DevicePlan $plan): RedirectResponse
    {
        $validated = $request->validated();
        $dto = new DevicePlanUpdateDTO(
            id: $plan->id,
            name: $validated['name'],
            description: $validated['description'] ?? null,
            price: (float) $validated['price'],
            isActive: (bool) ($validated['is_active'] ?? (bool) $plan->getAttribute('is_active')),
        );

        $this->updateDevicePlanAction->execute($dto);

        return redirect()->route('product.plan')
            ->with('success', 'Device plan updated successfully.');
    }

    /**
     * Attach plan to device
     */
    public function attachPlan(Request $request, DevicePlan $plan, Device $device): RedirectResponse
    {
        $validated = $request->validate([
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $dto = new AttachPlanToDeviceDTO(
            deviceId: $device->id,
            planId: $plan->id,
            price: isset($validated['price']) ? (float) $validated['price'] : null,
            isDefault: isset($validated['is_default']) ? (bool) $validated['is_default'] : null,
        );
        $_ = $this->attachPlanToDeviceAction->execute($dto);

        return redirect()->route('product.edit-device', $device)
            ->with('success', 'Plan attached successfully.');
    }

    /**
     * Detach plan from device
     */
    public function detachPlan(DevicePlan $plan, Device $device): RedirectResponse
    {
        $dto = new DetachPlanFromDeviceDTO(deviceId: $device->id, planId: $plan->id);
        $_ = $this->detachPlanFromDeviceAction->execute($dto);

        return redirect()->route('product.edit-device', $device)
            ->with('success', 'Plan detached successfully.');
    }
}
