<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\Web\SelfRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View as ViewFacade;

use function is_object;
use function property_exists;

class SiteController extends Controller
{
    /**
     * Index action (Dashboard)
     */
    public function index(): View
    {
        return ViewFacade::make('site.index');
    }

    /**
     * Self action (profile view)
     */
    public function self(): View
    {
        $user = Auth::user();
        $username = $user->username ?? (string) (explode('@', (string) ($user->email ?? 'user@example.com'))[0] ?? 'user');

        $firstName = is_object($user) && property_exists($user, 'first_name') ? (string) ($user->first_name ?? '') : '';
        $lastName = is_object($user) && property_exists($user, 'last_name') ? (string) ($user->last_name ?? '') : '';

        $staff = (object) [
            'username' => $username,
            'email' => (string) ($user->email ?? ''),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'created_at' => $user->created_at ?? now(),
            'updated_at' => $user->updated_at ?? now(),
            'role' => 1,
        ];

        if (request()->ajax()) {
            return ViewFacade::make('site._self-modal', compact('staff'));
        }

        return ViewFacade::make('site.self', compact('staff'));
    }

    /**
     * Self action (profile update)
     */
    public function selfSubmit(SelfRequest $request): RedirectResponse
    {
        $user = Auth::user();
        if (! $user) {
            return Redirect::to(route('site.login'));
        }

        $data = $request->validated();
        if (is_object($user) && property_exists($user, 'first_name')) {
            /** @phpstan-ignore-next-line */
            $user->first_name = $data['first_name'] ?? $user->first_name;
        }
        if (is_object($user) && property_exists($user, 'last_name')) {
            /** @phpstan-ignore-next-line */
            $user->last_name = $data['last_name'] ?? $user->last_name;
        }
        if (is_object($user) && property_exists($user, 'email')) {
            /** @phpstan-ignore-next-line */
            $user->email = $data['email'] ?? $user->email;
        }
        $user->save();

        return Redirect::to(route('site.index'))
            ->with('success', 'You have successfully updated your data.');
    }

    /**
     * Error action
     */
    public function error(): View
    {
        return ViewFacade::make('site.error');
    }
}
