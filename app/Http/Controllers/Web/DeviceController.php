<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Device\DeleteDeviceAction;
use App\Domain\Actions\Device\GetDeviceAction;
use App\Domain\Actions\Device\GetDeviceListAction;
use App\Domain\Actions\Device\SendPushNotificationAction;
use App\Domain\DTOs\Device\DeviceListRequestDTO;
use App\Domain\DTOs\Device\DevicePushRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Device\Web\PushRequest;
use App\Models\Device\Device;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class DeviceController extends Controller
{
    public function __construct(
        private readonly GetDeviceListAction $getDeviceListAction,
        private readonly GetDeviceAction $getDeviceAction,
        private readonly DeleteDeviceAction $deleteDeviceAction,
        private readonly SendPushNotificationAction $sendPushNotificationAction
    ) {}

    /**
     * Display Devices
     */
    public function index(Request $request): View
    {
            $dto = new DeviceListRequestDTO(
                page: $request->get('page', 1),
                limit: $request->get('limit', 20),
                search: $request->get('search'),
                user_id: $request->get('user_id'),
                os: $request->get('os'),
                pushTokenFill: $request->get('push_token_fill'),
                pushToken: $request->get('push_token'),
                pushTurnOn: $request->get('push_turn_on'),
                updatedAtFrom: $request->get('updated_at_from'),
                updatedAtTo: $request->get('updated_at_to')
            );

            $devices = $this->getDeviceListAction->execute($dto->toArray());

            return ViewFacade::make('device.index', [
                'devices' => $devices,
                'filters' => $request->only(['search', 'push_token', 'push_token_fill', 'push_turn_on', 'os', 'updated_at_from', 'updated_at_to']),
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
            $dto = new \App\Domain\DTOs\Device\DeviceDeleteRequestDTO(
                id: $device->id
            );

            $this->deleteDeviceAction->execute($dto->toArray());

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
    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
            $query = Device::with(['user']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('uuid', 'like', "%{$search}%")
                        ->orWhere('os', 'like', "%{$search}%")
                        ->orWhere('push_token', 'like', "%{$search}%")
                        ->orWhere('access_token', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('email', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('push_token_fill')) {
                if ($request->get('push_token_fill') === 0) {
                    $query->where(function ($q) {
                        $q->whereNull('push_token')->orWhere('push_token', '');
                    });
                } else {
                    $query->whereNotNull('push_token')->where('push_token', '!=', '');
                }
            }

            if ($request->filled('push_token')) {
                $query->where('push_token', 'like', "%{$request->get('push_token')}%");
            }

            if ($request->filled('push_turn_on')) {
                $query->where('push_turn_on', $request->get('push_turn_on'));
            }

            if ($request->filled('os')) {
                $query->where('os', $request->get('os'));
            }

            if ($request->filled('updated_at_from')) {
                $query->whereDate('updated_at', '>=', $request->get('updated_at_from'));
            }
            if ($request->filled('updated_at_to')) {
                $query->whereDate('updated_at', '<=', $request->get('updated_at_to'));
            }

            $devices = $query->orderBy('updated_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'User Email', 'User ID', 'Push Turn On', 'UUID', 'OS', 'Push Token', 'Access Token', 'Updated At'];

            foreach ($devices as $device) {
                $csvData[] = [
                    $device->id,
                    $device->user->email ?? '',
                    $device->user->id ?? '',
                    $device->push_turn_on ? 'Yes' : 'No',
                    $device->uuid,
                    $device->os,
                    $device->push_token,
                    $device->access_token,
                    $device->updated_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'devices_'.now()->format('Y-m-d_H-i-s').'.csv';

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
