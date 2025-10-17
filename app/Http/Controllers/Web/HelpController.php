<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\System\ApiLog;
use App\Models\System\Settings;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class HelpController extends Controller
{
    /**
     * Display help index
     */
    public function index(): View
    {
        return ViewFacade::make('web.help.index');
    }

    /**
     * Test action
     */
    public function test(): Response
    {
        try {
            // Test API functionality
            $api = new \App\Services\BivyStickService();
            $result = $api->update();

            return response()->json($result);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Check SPS
     */
    public function checkSps(): Response
    {
        try {
            $sps = new \App\Services\SpsApiService();
            $result = $sps->checkIp();

            return response()->json($result);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display logs
     */
    public function log(): View
    {
        $logFiles = $this->getLogFiles();

        return ViewFacade::make('web.help.log', [
            'logFiles' => $logFiles,
        ]);
    }

    /**
     * Download log file
     */
    public function download(Request $request): Response
    {
        try {
            $alias = $request->get('alias');

            // Validate alias for security
            $allowedPaths = [
                storage_path('logs'),
                base_path('storage/logs'),
            ];

            $isValid = false;
            foreach ($allowedPaths as $path) {
                if (str_contains((string) $alias, $path)) {
                    $isValid = true;
                    break;
                }
            }

            if (! $isValid) {
                abort(403, 'Access denied');
            }

            if (! file_exists($alias)) {
                abort(404, 'File not found');
            }

            return response()->download($alias);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Read log file
     */
    public function readLog(Request $request): View
    {
        $alias = $request->get('alias');

        if (! file_exists($alias)) {
            abort(404, 'File not found');
        }

        $content = file_get_contents($alias);

        return ViewFacade::make('web.help.read-log', [
            'alias' => $alias,
            'content' => $content,
        ]);
    }

    /**
     * Test modal
     */
    public function testModal(): View
    {
        return ViewFacade::make('web.help.test-modal');
    }

    /**
     * Test alert
     */
    public function testAlert(Request $request): Response
    {
        $type = $request->get('type', 'info');
        $message = 'Test alert';

        return response()->json([
            'type' => $type,
            'message' => $message,
        ]);
    }

    /**
     * Test mail
     */
    public function testMail(): Response
    {
        try {
            $sandboxEmail = Settings::get('mail__sandbox_email');
            $adminEmail = Settings::get('mail__admin_email');

            if (! $sandboxEmail || ! $adminEmail) {
                return response()->json(['error' => 'Email settings not configured'], 400);
            }

            Mail::raw('Test Body', function ($message) use ($sandboxEmail, $adminEmail) {
                $message->to($sandboxEmail)
                    ->from($adminEmail)
                    ->subject('Galileyo test letter');
            });

            return response()->json(['success' => 'Mail sent successfully']);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to send mail: '.$e->getMessage()], 500);
        }
    }

    /**
     * Twilio lookup
     */
    public function twilioLookup(Request $request): Response
    {
        try {
            $number = $request->get('number');

            if (! $number) {
                return response()->json(['error' => 'Phone number is required'], 400);
            }

            $twilioSid = Settings::get('sms__twilio_sid');
            $twilioToken = Settings::get('sms__twilio_token');

            if (! $twilioSid || ! $twilioToken) {
                return response()->json(['error' => 'Twilio credentials not configured'], 400);
            }

            $twilio = new \Twilio\Rest\Client($twilioSid, $twilioToken);
            $result = $twilio->lookups->v1->phoneNumbers($number)->fetch(['type' => ['carrier']]);

            return response()->json($result->toArray());

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Twilio send SMS
     */
    public function twilioSend(Request $request): Response
    {
        try {
            $number = $request->get('number');
            $text = $request->get('text');

            if (! $number || ! $text) {
                return response()->json(['error' => 'Phone number and text are required'], 400);
            }

            $twilioSid = Settings::get('sms__twilio_sid');
            $twilioToken = Settings::get('sms__twilio_token');
            $twilioFrom = Settings::get('sms__twilio_from');

            if (! $twilioSid || ! $twilioToken || ! $twilioFrom) {
                return response()->json(['error' => 'Twilio credentials not configured'], 400);
            }

            $twilio = new \Twilio\Rest\Client($twilioSid, $twilioToken);
            $message = $twilio->api->account->messages->create($number, [
                'from' => $twilioFrom,
                'body' => $text,
            ]);

            return response()->json($message->toArray());

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * IEX test
     */
    public function iexTest(Request $request): Response
    {
        try {
            $uri = $request->get('uri', 'stock/aapl/ohlc');

            $iexService = new \App\Services\IexCloudService();
            $result = $iexService->get($uri);

            return response()->json($result);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API log diff
     */
    public function apiLogDiff(Request $request): View
    {
        $key = $request->get('key');

        if (! $key) {
            abort(400, 'Key is required');
        }

        $apiLogs = ApiLog::where('key', $key)->orderBy('created_at')->get();

        return ViewFacade::make('web.help.api-log-diff', [
            'apiLogs' => $apiLogs,
        ]);
    }

    /**
     * SMS test
     */
    public function sms(Request $request): Response
    {
        try {
            $provider = $request->get('provider');
            $number = $request->get('number');
            $body = $request->get('body');

            if (! $provider || ! $number || ! $body) {
                return response()->json(['error' => 'Provider, number and body are required'], 400);
            }

            $smsService = new \App\Services\SmsService($provider);
            $result = $smsService->send($number, $body);

            return response()->json([
                'success' => $result,
                'response' => $smsService->getResponse(),
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Test push notification
     */
    public function testPush(Request $request): Response
    {
        try {
            $token = $request->get('token');
            $body = $request->get('body');
            $isProd = $request->get('is_prod', false);

            if (! $token || ! $body) {
                return response()->json(['error' => 'Token and body are required'], 400);
            }

            $pushService = new \App\Services\PushIosService();
            $result = $pushService->send([$token], $body, '', $isProd);

            return response()->json(['success' => $result]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Chat test
     */
    public function chat(Request $request): View
    {
        $idFirstUser = $request->get('id_first_user');
        $idSecondUser = $request->get('id_second_user');

        return ViewFacade::make('web.help.chat', [
            'idFirstUser' => $idFirstUser,
            'idSecondUser' => $idSecondUser,
        ]);
    }

    // Helper methods
    private function getLogFiles(): array
    {
        $logFiles = [];
        $logPath = storage_path('logs');

        if (is_dir($logPath)) {
            $files = glob($logPath.'/*.log');
            foreach ($files as $file) {
                $logFiles[] = [
                    'path' => $file,
                    'name' => basename($file),
                    'size' => filesize($file),
                    'modified' => filemtime($file),
                ];
            }
        }

        return $logFiles;
    }
}
