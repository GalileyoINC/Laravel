<?php

declare(strict_types=1);

namespace Tests\Feature\Web;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminViewRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_view_is_accessible_and_uses_correct_view(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $response = $this->get('/admin/dashboard');

        $response->assertOk();
        $response->assertViewIs('admin.user.dashboard');
    }

    public function test_admin_profile_view_is_accessible_and_uses_correct_view(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $response = $this->get('/admin/profile');

        $response->assertOk();
        $response->assertViewIs('admin.user.profile');
    }

    public function test_admin_subscriptions_view_is_accessible_and_uses_correct_view(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $response = $this->get('/admin/subscriptions');

        $response->assertOk();
        $response->assertViewIs('admin.user.subscriptions');
    }
}
