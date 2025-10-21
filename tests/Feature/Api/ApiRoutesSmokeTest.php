<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

final class ApiRoutesSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_protected_routes_require_auth(): void
    {
        foreach ($this->routes() as $route) {
            if (! $route['requiresAuth']) {
                continue;
            }
            $response = $this->json($route['method'], $route['uri'], $route['payload'] ?? []);
            $response->assertStatus(401);
        }
    }

    public function test_protected_routes_allow_authenticated_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        foreach ($this->routes() as $route) {
            if (! $route['requiresAuth']) {
                continue;
            }
            $response = $this->json($route['method'], $route['uri'], $route['payload'] ?? []);
            $this->assertNotSame(401, $response->getStatusCode(), sprintf('Expected non-401 for %s %s', $route['method'], $route['uri']));
        }
    }

    /**
     * @return array<int, array{method:string, uri:string, requiresAuth:bool, payload?:array<string, mixed>}> */
    private function routes(): array
    {
        return [
            // auth (public)
            ['method' => 'POST', 'uri' => '/api/v1/auth/login', 'requiresAuth' => false, 'payload' => ['email' => 'john@example.com', 'password' => 'secret']],
            ['method' => 'GET',  'uri' => '/api/v1/auth/test', 'requiresAuth' => false],

            // chat (protected)
            ['method' => 'POST', 'uri' => '/api/v1/chat/list', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/chat/chat-messages', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/chat/view', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/chat/upload', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/chat/create-group', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/chat/get-friend-chat', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/chat/get-file/1', 'requiresAuth' => true],

            // comment (protected)
            ['method' => 'POST', 'uri' => '/api/v1/comment/get', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/comment/get-replies', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/comment/create', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/comment/update', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/comment/delete', 'requiresAuth' => true],

            // credit-card (protected)
            ['method' => 'POST', 'uri' => '/api/v1/credit-card/list', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/credit-card/create', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/credit-card/update', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/credit-card/set-preferred', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/credit-card/delete', 'requiresAuth' => true],

            // customer (protected)
            ['method' => 'GET',  'uri' => '/api/v1/customer/get-profile', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/customer/update-profile', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/customer/change-password', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/customer/update-privacy', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/customer/remove-avatar', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/customer/remove-header', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/customer/logout', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/customer/delete', 'requiresAuth' => true],

            // default (public)
            ['method' => 'POST', 'uri' => '/api/v1/default/login', 'requiresAuth' => false],
            ['method' => 'POST', 'uri' => '/api/v1/default/signup', 'requiresAuth' => false],
            ['method' => 'POST', 'uri' => '/api/v1/default/news-by-subscription', 'requiresAuth' => false],

            // device (protected)
            ['method' => 'POST', 'uri' => '/api/v1/device/update', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/device/verify', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/device/push-settings-get', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/device/push-settings-set', 'requiresAuth' => true],

            // influencer (protected)
            ['method' => 'GET',  'uri' => '/api/v1/influencer/index', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/influencer/create', 'requiresAuth' => true],

            // legacy login (public)
            ['method' => 'POST', 'uri' => '/api/auth/login', 'requiresAuth' => false],

            // legacy news (protected)
            ['method' => 'POST', 'uri' => '/api/news/last', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/news/get-latest-news', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/news/by-influencers', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/news/by-subscription', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/news/by-follower-list', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/news/set-reaction', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/news/remove-reaction', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/news/report', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/news/mute', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/news/unmute', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/news/create', 'requiresAuth' => true],

            // v1/news (protected)
            ['method' => 'POST', 'uri' => '/api/v1/news/last', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/news/get-latest-news', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/news/by-influencers', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/news/by-subscription', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/news/by-follower-list', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/news/set-reaction', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/news/remove-reaction', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/news/report', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/news/mute', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/news/unmute', 'requiresAuth' => true],

            // order (protected)
            ['method' => 'POST', 'uri' => '/api/v1/order/create', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/order/pay', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/order/test', 'requiresAuth' => true],

            // phone (protected)
            ['method' => 'POST', 'uri' => '/api/v1/phone/verify', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/phone/set', 'requiresAuth' => true],

            // private-feed (protected)
            ['method' => 'GET',  'uri' => '/api/v1/private-feed/index', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/private-feed/create', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/private-feed/update', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/private-feed/delete', 'requiresAuth' => true],

            // product (protected)
            ['method' => 'POST', 'uri' => '/api/v1/product/list', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/product/alerts', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/product/purchase', 'requiresAuth' => true],

            // public-feed (protected)
            ['method' => 'POST', 'uri' => '/api/v1/public-feed/get-options', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/public-feed/send', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/public-feed/image-upload', 'requiresAuth' => true],

            // feed/subscription (protected)
            ['method' => 'POST', 'uri' => '/api/v1/feed/index', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/feed/set', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/feed/satellite-set', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/feed/category', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/feed/satellite-index', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/feed/add-own-marketstack-indx-subscription', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/feed/add-own-marketstack-ticker-subscription', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/feed/options', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/feed/delete-private-feed', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/feed/get-image', 'requiresAuth' => true],

            // all-send-form (protected)
            ['method' => 'POST', 'uri' => '/api/v1/all-send-form/get-options', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/all-send-form/send', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/all-send-form/image-upload', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/all-send-form/image-delete', 'requiresAuth' => true],

            // bundle (protected)
            ['method' => 'GET',  'uri' => '/api/v1/bundle/index', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/bundle/create', 'requiresAuth' => true],
            ['method' => 'PUT',  'uri' => '/api/v1/bundle/update/1', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/bundle/device-data', 'requiresAuth' => true],

            // contact (protected)
            ['method' => 'GET',  'uri' => '/api/v1/contact/index', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/contact/view/1', 'requiresAuth' => true],
            ['method' => 'DELETE', 'uri' => '/api/v1/contact/delete/1', 'requiresAuth' => true],

            // contract-line (protected)
            ['method' => 'GET',  'uri' => '/api/v1/contract-line/index', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/contract-line/view/1', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/contract-line/create', 'requiresAuth' => true],
            ['method' => 'PUT',  'uri' => '/api/v1/contract-line/update/1', 'requiresAuth' => true],
            ['method' => 'DELETE', 'uri' => '/api/v1/contract-line/delete/1', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/contract-line/unpaid', 'requiresAuth' => true],

            // email-pool (protected)
            ['method' => 'GET',  'uri' => '/api/v1/email-pool/index', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/email-pool/view/1', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/email-pool/create', 'requiresAuth' => true],
            ['method' => 'PUT',  'uri' => '/api/v1/email-pool/update/1', 'requiresAuth' => true],
            ['method' => 'DELETE', 'uri' => '/api/v1/email-pool/delete/1', 'requiresAuth' => true],

            // email-template (protected)
            ['method' => 'GET',  'uri' => '/api/v1/email-template/index', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/email-template/view/1', 'requiresAuth' => true],
            ['method' => 'PUT',  'uri' => '/api/v1/email-template/update/1', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/email-template/body/1', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/email-template/send/1', 'requiresAuth' => true],

            // report (protected)
            ['method' => 'GET',  'uri' => '/api/v1/report/login-statistic', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/report/sold-devices', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/report/influencer-total', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/report/referral', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/report/statistic', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/report/sms', 'requiresAuth' => true],

            // settings (protected)
            ['method' => 'GET',  'uri' => '/api/v1/settings/index', 'requiresAuth' => true],
            ['method' => 'PUT',  'uri' => '/api/v1/settings/update', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/settings/flush', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/settings/public', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/settings/bitpay-generation', 'requiresAuth' => true],

            // staff (protected)
            ['method' => 'GET',  'uri' => '/api/v1/staff/index', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/staff/view/1', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/staff/create', 'requiresAuth' => true],
            ['method' => 'PUT',  'uri' => '/api/v1/staff/update/1', 'requiresAuth' => true],
            ['method' => 'DELETE', 'uri' => '/api/v1/staff/delete/1', 'requiresAuth' => true],

            // invoice (protected)
            ['method' => 'GET',  'uri' => '/api/v1/invoice/index', 'requiresAuth' => true],
            ['method' => 'GET',  'uri' => '/api/v1/invoice/view/1', 'requiresAuth' => true],

            // bookmark (protected)
            ['method' => 'GET',  'uri' => '/api/v1/bookmark/list', 'requiresAuth' => true],
            ['method' => 'POST', 'uri' => '/api/v1/bookmark/create', 'requiresAuth' => true],
            ['method' => 'DELETE', 'uri' => '/api/v1/bookmark/delete/1', 'requiresAuth' => true],

            // maintenance (protected)
            ['method' => 'POST', 'uri' => '/api/v1/maintenance/summarize', 'requiresAuth' => true],
        ];
    }
}
