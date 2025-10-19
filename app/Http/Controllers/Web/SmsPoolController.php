<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\SmsPool\GetSmsPoolListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Communication\Web\SendSmsRequest;
use App\Http\Requests\SmsPool\Web\SmsPoolIndexRequest;
use App\Http\Requests\SmsPool\Web\SmsPoolRecipientIndexRequest;
use App\Models\Communication\SmsPool;
use App\Models\Communication\SmsPoolPhoneNumber;
use App\Models\Device\PhoneNumber;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionCategory;
use App\Models\System\AdminMessageLog;
use App\Models\System\Service;
use App\Models\System\State;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View as ViewFacade;

class SmsPoolController extends Controller
{
    public function __construct(
        private readonly GetSmsPoolListAction $getSmsPoolListAction,
    ) {}

    /**
     * Display a listing of SMS pools
     */
    public function index(SmsPoolIndexRequest $request): View
    {
        $filters = $request->validated();
        $smsPools = $this->getSmsPoolListAction->execute($filters, 20);

        $subscriptions = Subscription::getForDropDown();

        return ViewFacade::make('sms-pool.index', [
            'smsPools' => $smsPools,
            'filters' => $filters,
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * Display the specified SMS pool
     */
    public function show(SmsPool $smsPool): View
    {
        $smsPool->load(['user', 'staff', 'subscription']);

        return ViewFacade::make('sms-pool.show', [
            'smsPool' => $smsPool,
        ]);
    }

    /**
     * Remove the specified SMS pool
     */
    public function destroy(SmsPool $smsPool): JsonResponse
    {
        $smsPool->delete();

        return response()->json(['success' => 'SMS pool deleted successfully']);
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

        return ViewFacade::make('sms-pool.send-dashboard', [
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
        return ViewFacade::make('sms-pool.send-to', [
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

        return ViewFacade::make('sms-pool.send-to', [
            'title' => "Send message for {$state} ({$count})",
            'objType' => AdminMessageLog::TO_STATE,
            'objId' => $state,
            'state' => $state,
        ]);
    }

    /**
     * Process SMS sending
     */
    public function processSend(SendSmsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Process SMS sending logic here
        // This would typically call SMS service API

        return response()->json(['success' => 'Message was sent successfully']);
    }

    /**
     * Show recipients for SMS pool
     */
    public function recipient(SmsPool $smsPool, SmsPoolRecipientIndexRequest $request): View
    {
        $filters = $request->validated();
        $query = SmsPoolPhoneNumber::with(['phoneNumber', 'user'])
            ->where('id_sms_pool', $smsPool->id);
        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->whereHas('phoneNumber', function ($q) use ($search) {
                $q->where('phone_number', 'like', "%{$search}%");
            });
        }
        $recipients = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('sms-pool.recipient', [
            'smsPool' => $smsPool,
            'recipients' => $recipients,
            'filters' => $filters,
        ]);
    }

    /**
     * Get SMS pool image
     */
    public function getImage(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $type = $request->get('type');

        // This would typically handle image serving logic
        // For now, return a placeholder response

        return response()->json(['message' => 'Image not found'], 404);
    }
}
