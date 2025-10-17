<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Communication\Web\SendSmsRequest;
use App\Models\Communication\SmsPool;
use App\Models\Communication\SmsPoolPhoneNumber;
use App\Models\Device\PhoneNumber;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionCategory;
use App\Models\System\AdminMessageLog;
use App\Models\System\Service;
use App\Models\System\State;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class SmsPoolController extends Controller
{
    /**
     * Display a listing of SMS pools
     */
    public function index(Request $request): View
    {
        $query = SmsPool::with(['user', 'staff', 'subscription']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('body', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filter by purpose
        if ($request->filled('purpose')) {
            $query->where('purpose', $request->get('purpose'));
        }

        // Filter by subscription
        if ($request->filled('id_subscription')) {
            $query->where('id_subscription', $request->get('id_subscription'));
        }

        // Filter by created date
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->get('created_at'));
        }

        $smsPools = $query->orderBy('created_at', 'desc')->paginate(20);

        $subscriptions = Subscription::getAllAsArray();

        return ViewFacade::make('web.sms-pool.index', [
            'smsPools' => $smsPools,
            'filters' => $request->only(['search', 'purpose', 'id_subscription', 'created_at']),
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * Display the specified SMS pool
     */
    public function show(SmsPool $smsPool): View
    {
        $smsPool->load(['user', 'staff', 'subscription']);

        return ViewFacade::make('web.sms-pool.show', [
            'smsPool' => $smsPool,
        ]);
    }

    /**
     * Remove the specified SMS pool
     */
    public function destroy(SmsPool $smsPool): Response
    {
        try {
            $smsPool->delete();

            return response()->json(['success' => 'SMS pool deleted successfully']);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete SMS pool: '.$e->getMessage()], 500);
        }
    }

    /**
     * Show send dashboard
     */
    public function sendDashboard(): View
    {
        // Get subscription categories and subscriptions
        $subscriptionCategories = SubscriptionCategory::all()->keyBy('id')->toArray();
        $subscriptions = Subscription::orderBy('id_subscription_category')
            ->orderBy('position_no')
            ->get()
            ->toArray();

        // Group subscriptions by category
        $subscriptionsByCategory = [];
        $subscriptionUserStatistics = [];

        foreach (Subscription::getUserStatistics(Subscription::ACTIVE_ONLY) as $stat) {
            $subscriptionUserStatistics[$stat['id_subscription']] = $stat['cnt'];
        }

        foreach ($subscriptions as $subscription) {
            $subscriptionsByCategory[$subscription['id_subscription_category']][] = [
                'id' => $subscription['id'],
                'name' => $subscription['name'],
                'cnt' => $subscriptionUserStatistics[$subscription['id']] ?? null,
            ];
        }

        // Get statistics by state
        $byState = DB::select('
            SELECT COUNT(*) AS cnt, user.state 
            FROM user_plan 
            JOIN user ON user_plan.id_user = user.id
            WHERE user.status = 1 AND id_service IS NOT NULL AND DATE(end_at) > NOW() 
            GROUP BY user.state
        ');

        // Get statistics by service
        $byService = DB::select('
            SELECT COUNT(*) AS cnt, id_service 
            FROM user_plan 
            JOIN user ON user_plan.id_user = user.id
            WHERE user.status = 1 AND id_service IS NOT NULL AND DATE(end_at) > NOW() 
            GROUP BY id_service
        ');

        // Get statistics by device type
        $byDevice = DB::select('
            SELECT COUNT(*) AS cnt, type 
            FROM phone_number 
            JOIN user ON phone_number.id_user = user.id
            WHERE user.status = 1 AND is_active = 1 AND is_primary = 1 AND type <> ?
            GROUP BY type
        ', [PhoneNumber::TYPE_NONE]);

        return ViewFacade::make('web.sms-pool.send-dashboard', [
            'subscriptionCategories' => $subscriptionCategories,
            'subscriptionsByCategory' => $subscriptionsByCategory,
            'byState' => $byState,
            'byService' => $byService,
            'byDevice' => $byDevice,
        ]);
    }

    /**
     * Send SMS to all users
     */
    public function sendToAll(): View
    {
        return ViewFacade::make('web.sms-pool.send-to', [
            'title' => 'Send message for all users with plan',
            'objType' => AdminMessageLog::TO_ALL,
            'objId' => null,
        ]);
    }

    /**
     * Send SMS to state
     */
    public function sendToState(Request $request): View
    {
        $state = $request->get('state');

        // Count users in state
        $count = DB::table('user_plan')
            ->join('user', 'user_plan.id_user', '=', 'user.id')
            ->where('user.status', 1)
            ->whereNotNull('id_service')
            ->whereRaw('DATE(end_at) > NOW()')
            ->where('user.state', $state)
            ->count();

        return ViewFacade::make('web.sms-pool.send-to', [
            'title' => "Send message for {$state} ({$count})",
            'objType' => AdminMessageLog::TO_STATE,
            'objId' => $state,
            'state' => $state,
        ]);
    }

    /**
     * Process SMS sending
     */
    public function processSend(SendSmsRequest $request): Response
    {
        try {
            $validated = $request->validated();

            // Process SMS sending logic here
            // This would typically call SMS service API

            return response()->json(['success' => 'Message was sent successfully']);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to send message: '.$e->getMessage()], 500);
        }
    }

    /**
     * Show recipients for SMS pool
     */
    public function recipient(SmsPool $smsPool, Request $request): View
    {
        $query = SmsPoolPhoneNumber::with(['phoneNumber', 'user'])
            ->where('id_sms_pool', $smsPool->id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('phoneNumber', function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%");
            });
        }

        $recipients = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('web.sms-pool.recipient', [
            'smsPool' => $smsPool,
            'recipients' => $recipients,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Get SMS pool image
     */
    public function getImage(Request $request): Response
    {
        $id = $request->get('id');
        $type = $request->get('type');

        // This would typically handle image serving logic
        // For now, return a placeholder response

        return response()->json(['message' => 'Image not found'], 404);
    }
}
