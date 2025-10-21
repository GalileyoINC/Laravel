<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Settings\FlushSettingsAction;
use App\Domain\Actions\Settings\UpdateSettingsAction;
use App\Domain\DTOs\Settings\SettingsUpdateRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Web\SettingsApiRequest;
use App\Http\Requests\Settings\Web\SettingsAppRequest;
use App\Http\Requests\Settings\Web\SettingsMainRequest;
use App\Http\Requests\Settings\Web\SettingsPublicRequest;
use App\Http\Requests\Settings\Web\SettingsSmsRequest;
use App\Http\Requests\Users\Web\UserPointSettingsRequest;
use App\Models\System\Settings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View as ViewFacade;

class SettingsController extends Controller
{
    public function __construct(
        private readonly FlushSettingsAction $flushSettingsAction,
        private readonly UpdateSettingsAction $updateSettingsAction
    ) {}

    /**
     * Flush settings cache
     */
    public function flush(): RedirectResponse
    {
        $this->flushSettingsAction->execute([]);

        return redirect()->route('settings.index')
            ->with('success', 'Settings cache flushed successfully.');
    }

    /**
     * Display settings index
     */
    public function index(): View
    {
        // Check if user can view settings
        $currentUser = Auth::user();
        $canViewSettings = $currentUser && \method_exists($currentUser, 'showSettings')
            ? (bool) $currentUser->showSettings()
            : true;
        if (! $currentUser || ! $canViewSettings) {
            abort(404, 'The requested page does not exist.');
        }

        $smsForm = new SettingsSmsRequest();
        $mainForm = new SettingsMainRequest();
        $apiForm = new SettingsApiRequest();
        $appForm = new SettingsAppRequest();

        $smsForm->initValues();
        $mainForm->initValues();
        $apiForm->initValues();
        $appForm->initValues();

        return ViewFacade::make('settings.index', [
            'smsForm' => $smsForm,
            'mainForm' => $mainForm,
            'apiForm' => $apiForm,
            'appForm' => $appForm,
        ]);
    }

    /**
     * Update main settings
     */
    public function updateMain(SettingsMainRequest $request): RedirectResponse
    {
        $currentUser = Auth::user();
        $isReadOnly = $currentUser && \method_exists($currentUser, 'showSettingsRO')
            ? (bool) $currentUser->showSettingsRO()
            : false;
        if ($currentUser && ! $isReadOnly) {
            $validated = $request->validated();

            $dto = new SettingsUpdateRequestDTO(
                settings: $validated
            );

            $this->updateSettingsAction->execute($dto->toArray());

            return redirect()->route('settings.index', ['#' => 'main'])
                ->with('success', 'Main settings updated successfully.');
        }

        return redirect()->back()
            ->withErrors(['error' => 'You do not have permission to update settings.']);
    }

    /**
     * Update SMS settings
     */
    public function updateSms(SettingsSmsRequest $request): RedirectResponse
    {
        $currentUser = Auth::user();
        $isReadOnly = $currentUser && \method_exists($currentUser, 'showSettingsRO')
            ? (bool) $currentUser->showSettingsRO()
            : false;
        if ($currentUser && ! $isReadOnly) {
            $validated = $request->validated();

            $dto = new SettingsUpdateRequestDTO(
                settings: $validated,
                sms_settings: $validated
            );

            $this->updateSettingsAction->execute($dto->toArray());

            return redirect()->route('settings.index', ['#' => 'sms'])
                ->with('success', 'SMS settings updated successfully.');
        }

        return redirect()->back()
            ->withErrors(['error' => 'You do not have permission to update settings.']);
    }

    /**
     * Update API settings
     */
    public function updateApi(SettingsApiRequest $request): RedirectResponse
    {
        $currentUser = Auth::user();
        $isReadOnly = $currentUser && \method_exists($currentUser, 'showSettingsRO')
            ? (bool) $currentUser->showSettingsRO()
            : false;
        if ($currentUser && ! $isReadOnly) {
            $validated = $request->validated();

            $dto = new SettingsUpdateRequestDTO(
                settings: $validated,
                api_settings: $validated
            );

            $this->updateSettingsAction->execute($dto->toArray());

            return redirect()->route('settings.index', ['#' => 'api'])
                ->with('success', 'API settings updated successfully.');
        }

        return redirect()->back()
            ->withErrors(['error' => 'You do not have permission to update settings.']);
    }

    /**
     * Update app settings
     */
    public function updateApp(SettingsAppRequest $request): RedirectResponse
    {
        $currentUser = Auth::user();
        $isReadOnly = $currentUser && \method_exists($currentUser, 'showSettingsRO')
            ? (bool) $currentUser->showSettingsRO()
            : false;
        if ($currentUser && ! $isReadOnly) {
            $validated = $request->validated();

            $dto = new SettingsUpdateRequestDTO(
                settings: $validated,
                app_settings: $validated
            );

            $this->updateSettingsAction->execute($dto->toArray());

            return redirect()->route('settings.index', ['#' => 'app'])
                ->with('success', 'App settings updated successfully.');
        }

        return redirect()->back()
            ->withErrors(['error' => 'You do not have permission to update settings.']);
    }

    /**
     * Display public settings
     */
    public function public(): View
    {
        $userPointSettingsForm = new UserPointSettingsRequest();
        $model = new SettingsPublicRequest();

        $userPointSettingsForm->initValues();
        $model->initValues();

        return ViewFacade::make('settings.public', [
            'model' => $model,
            'userPointSettingsForm' => $userPointSettingsForm,
        ]);
    }

    /**
     * Update public settings
     */
    public function updatePublic(SettingsPublicRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new SettingsUpdateRequestDTO(
            settings: $validated
        );

        $this->updateSettingsAction->execute($dto->toArray());

        return redirect()->route('settings.public')
            ->with('success', 'Public settings updated successfully.');
    }

    /**
     * Update user point settings
     */
    public function updateUserPoint(UserPointSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new SettingsUpdateRequestDTO(
            settings: $validated
        );

        $this->updateSettingsAction->execute($dto->toArray());

        return redirect()->route('settings.public')
            ->with('success', 'User point settings updated successfully.');
    }

    /**
     * Generate BitPay configuration
     */
    public function bitpayGeneration(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $pairingCode = $this->generateBitPayConfig();

            return response()->json($pairingCode);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    private function generateBitPayConfig(): string
    {
        // BitPay configuration generation logic here
        return 'generated_pairing_code';
    }
}
