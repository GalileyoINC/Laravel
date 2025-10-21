<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Authentication\LoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\Web\LoginRequest;
use App\Http\Requests\Authentication\Web\SelfRequest;
use App\Models\System\Staff;
use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View as ViewFacade;

class SiteController extends Controller
{
    public function __construct(
        private readonly LoginAction $loginAction
    ) {}

    /**
     * Show login form
     */
    public function login(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Redirect::to(route('site.index'));
        }

        return view('site.login');
    }

    /**
     * Handle login form submission
     */
    public function loginSubmit(LoginRequest $request): RedirectResponse
    {
        if (Auth::check()) {
            return Redirect::to(route('site.index'));
        }
        $loginData = [
            'email' => $request->validated()['username'],
            'password' => $request->validated()['password'],
            'device' => [],
        ];

        $result = $this->loginAction->execute($loginData);
        $data = $result->getData(true);

        if ($result->getStatusCode() === 200 && isset($data['user_id'])) {
            // Find user and login with Laravel Auth
            $userId = (int) $data['user_id'];
            $user = User::find($userId);
            if ($user) {
                /** @var User $user */
                Auth::login($user, false);
                session()->regenerate();

                return Redirect::to(route('site.index'));
            }
        }

        return Redirect::back()
            ->withErrors(['username' => $data['error'] ?? 'Login failed'])
            ->withInput();
    }

    /**
     * Logout action
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to(route('site.login'));
    }

    /**
     * Index action (Dashboard)
     */
    public function index(): View
    {
        return ViewFacade::make('site.index');
    }

    /**
     * Reset action (for super admin)
     */
    public function reset(Request $request): RedirectResponse
    {
        if ($request->session()->get('loginFromSuper')) {
            Auth::logout();

            $super = Staff::find(Staff::ID_SUPER);
            if ($super) {
                Auth::login($super, true);
            }

            $request->session()->forget('loginFromSuper');
        }

        return Redirect::to(route('site.index'));
    }

    /**
     * Self action (profile view)
     */
    public function self(): View
    {
        $user = Auth::user();
        $username = $user->username ?? (string) (explode('@', (string) ($user->email ?? 'user@example.com'))[0] ?? 'user');

        $staff = (object) [
            'username' => $username,
            'email' => (string) ($user->email ?? ''),
            'first_name' => (string) ($user->first_name ?? ''),
            'last_name' => (string) ($user->last_name ?? ''),
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
        $user->first_name = $data['first_name'] ?? $user->first_name;
        $user->last_name = $data['last_name'] ?? $user->last_name;
        $user->email = $data['email'] ?? $user->email;
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
