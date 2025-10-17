<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Settings\FlushSettingsAction;
use App\Domain\Actions\Settings\GenerateBitpayConfigAction;
use App\Domain\Actions\Settings\GetPublicSettingsAction;
use App\Domain\Actions\Settings\GetSettingsAction;
use App\Domain\Actions\Settings\UpdateSettingsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\SettingsPublicRequest;
use App\Http\Requests\Settings\SettingsUpdateRequest;
use App\Http\Resources\ErrorResource;
use Exception;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function index(GetSettingsAction $action): JsonResponse
    {
        try {
            $result = $action->execute([]);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function update(SettingsUpdateRequest $request, UpdateSettingsAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Settings updated successfully',
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function flush(FlushSettingsAction $action): JsonResponse
    {
        try {
            $result = $action->execute([]);

            return response()->json([
                'status' => 'success',
                'message' => 'Settings flushed successfully',
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function public(SettingsPublicRequest $request, GetPublicSettingsAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function bitpayGeneration(GenerateBitpayConfigAction $action): JsonResponse
    {
        try {
            $result = $action->execute([]);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }
}
