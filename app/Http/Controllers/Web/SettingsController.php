<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Web\SettingsApiRequest;
use App\Http\Requests\Settings\Web\SettingsAppRequest;
use App\Http\Requests\Settings\Web\SettingsMainRequest;
use App\Http\Requests\Settings\Web\SettingsPublicRequest;
use App\Http\Requests\Settings\Web\SettingsSmsRequest;
use App\Http\Requests\Users\Web\UserPointSettingsRequest;
use App\Models\System\Settings;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Flush settings cache
     */
    public function flush(): Response
    {
        try {
            Settings::flush();

            return redirect()->route('web.settings.index')
                ->with('success', 'Settings cache flushed successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to flush settings: '.$e->getMessage()]);
        }
    }

    /**
     * Display settings index
     */
    public function index(): View
    {
        // Check if user can view settings
        if (! auth()->user()->showSettings()) {
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

        return ViewFacade::make('web.settings.index', [
            'smsForm' => $smsForm,
            'mainForm' => $mainForm,
            'apiForm' => $apiForm,
            'appForm' => $appForm,
        ]);
    }

    /**
     * Update main settings
     */
    public function updateMain(SettingsMainRequest $request): Response
    {
        try {
            if (! auth()->user()->showSettingsRO()) {
                $validated = $request->validated();
                $this->saveMainSettings($validated);

                return redirect()->route('web.settings.index', ['#' => 'main'])
                    ->with('success', 'Main settings updated successfully.');
            }

            return redirect()->back()
                ->withErrors(['error' => 'You do not have permission to update settings.']);

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update main settings: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update SMS settings
     */
    public function updateSms(SettingsSmsRequest $request): Response
    {
        try {
            if (! auth()->user()->showSettingsRO()) {
                $validated = $request->validated();
                $this->saveSmsSettings($validated);

                return redirect()->route('web.settings.index', ['#' => 'sms'])
                    ->with('success', 'SMS settings updated successfully.');
            }

            return redirect()->back()
                ->withErrors(['error' => 'You do not have permission to update settings.']);

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update SMS settings: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update API settings
     */
    public function updateApi(SettingsApiRequest $request): Response
    {
        try {
            if (! auth()->user()->showSettingsRO()) {
                $validated = $request->validated();
                $this->saveApiSettings($validated);

                return redirect()->route('web.settings.index', ['#' => 'api'])
                    ->with('success', 'API settings updated successfully.');
            }

            return redirect()->back()
                ->withErrors(['error' => 'You do not have permission to update settings.']);

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update API settings: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update app settings
     */
    public function updateApp(SettingsAppRequest $request): Response
    {
        try {
            if (! auth()->user()->showSettingsRO()) {
                $validated = $request->validated();
                $this->saveAppSettings($validated);

                return redirect()->route('web.settings.index', ['#' => 'app'])
                    ->with('success', 'App settings updated successfully.');
            }

            return redirect()->back()
                ->withErrors(['error' => 'You do not have permission to update settings.']);

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update app settings: '.$e->getMessage()])
                ->withInput();
        }
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

        return ViewFacade::make('web.settings.public', [
            'model' => $model,
            'userPointSettingsForm' => $userPointSettingsForm,
        ]);
    }

    /**
     * Update public settings
     */
    public function updatePublic(SettingsPublicRequest $request): Response
    {
        try {
            $validated = $request->validated();
            $this->savePublicSettings($validated);

            return redirect()->route('web.settings.public')
                ->with('success', 'Public settings updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update public settings: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update user point settings
     */
    public function updateUserPoint(UserPointSettingsRequest $request): Response
    {
        try {
            $validated = $request->validated();
            $this->saveUserPointSettings($validated);

            return redirect()->route('web.settings.public')
                ->with('success', 'User point settings updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update user point settings: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Generate BitPay configuration
     */
    public function bitpayGeneration(Request $request): Response
    {
        try {
            if ($request->ajax()) {
                // BitPay client generation logic here
                $pairingCode = $this->generateBitPayConfig();

                return response()->json($pairingCode);
            }

            return response()->json(['error' => 'Invalid request'], 400);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to generate BitPay config: '.$e->getMessage()], 500);
        }
    }

    // Helper methods
    private function saveMainSettings(array $data): void
    {
        // Save main settings logic here
        foreach ($data as $key => $value) {
            Settings::set($key, $value);
        }
    }

    private function saveSmsSettings(array $data): void
    {
        // Save SMS settings logic here
        foreach ($data as $key => $value) {
            Settings::set($key, $value);
        }
    }

    private function saveApiSettings(array $data): void
    {
        // Save API settings logic here
        foreach ($data as $key => $value) {
            Settings::set($key, $value);
        }
    }

    private function saveAppSettings(array $data): void
    {
        // Save app settings logic here
        foreach ($data as $key => $value) {
            Settings::set($key, $value);
        }
    }

    private function savePublicSettings(array $data): void
    {
        // Save public settings logic here
        foreach ($data as $key => $value) {
            Settings::set($key, $value);
        }
    }

    private function saveUserPointSettings(array $data): void
    {
        // Save user point settings logic here
        foreach ($data as $key => $value) {
            Settings::set($key, $value);
        }
    }

    private function generateBitPayConfig(): string
    {
        // BitPay configuration generation logic here
        return 'generated_pairing_code';
    }
}
