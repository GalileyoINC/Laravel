<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Device\DeleteDeviceAction;
use App\Domain\Actions\Device\ExportDevicesToCsvAction;
use App\Domain\Actions\Device\GetDeviceListAction;
use App\Domain\Actions\Device\SendPushNotificationAction;
use App\Domain\DTOs\Device\DevicePushRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Device\Web\DeviceIndexRequest;
use App\Http\Requests\Device\Web\PushRequest;
use App\Models\Device\Device;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View as ViewFacade;
use RuntimeException;

class DeviceController extends Controller
{
    public function __construct(
        private readonly GetDeviceListAction $getDeviceListAction,
        private readonly DeleteDeviceAction $deleteDeviceAction,
        private readonly SendPushNotificationAction $sendPushNotificationAction,
        private readonly ExportDevicesToCsvAction $exportDevicesToCsvAction,
    ) {}

    /**
     * Display Devices
     */
    public function index(DeviceIndexRequest $request): View
    {
        $filters = $request->validated();

        $devices = $this->getDeviceListAction->execute(
            page: $filters['page'] ?? 1,
            limit: $filters['limit'] ?? 20,
            search: $filters['search'] ?? null,
            userId: $filters['user_id'] ?? null,
            os: $filters['os'] ?? null,
            pushTokenFill: $filters['push_token_fill'] ?? null,
            pushToken: $filters['push_token'] ?? null,
            pushTurnOn: $filters['push_turn_on'] ?? null,
            updatedAtFrom: $filters['updated_at_from'] ?? null,
            updatedAtTo: $filters['updated_at_to'] ?? null,
        );

        return ViewFacade::make('device.index', [
            'devices' => $devices,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Device Details
     */
    public function show(Device $device): View
    {
        return ViewFacade::make('device.show', [
            'device' => $device,
        ]);
    }

    /**
     * Delete Device
     */
    public function destroy(Device $device): RedirectResponse
    {
        $this->deleteDeviceAction->execute($device->id);

        return redirect()->route('device.index')
            ->with('success', 'Device deleted successfully.');
    }

    /**
     * Show Push Form
     */
    public function push(Device $device): View
    {
        return ViewFacade::make('device.push', [
            'device' => $device,
        ]);
    }

    /**
     * Send Push Notification
     */
    public function sendPush(PushRequest $request, Device $device): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new DevicePushRequestDTO(
            deviceId: $device->id,
            title: $validated['title'],
            body: $validated['body'],
            data: $validated['data'] ?? null
        );

        $this->sendPushNotificationAction->execute($dto->toArray());

        return redirect()->route('device.push', $device)
            ->with('success', 'Push notification sent successfully.');
    }

    /**
     * Export Devices to CSV
     */
    public function export(DeviceIndexRequest $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportDevicesToCsvAction->execute($filters);
        $filename = 'devices_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                throw new RuntimeException('Failed to open output stream');
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
