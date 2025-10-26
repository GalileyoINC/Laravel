<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Auth\GetStaffByCredentialsAction;
use App\Domain\Actions\Auth\GetSuperStaffAction;
use App\Domain\Actions\Auth\GetUserByIdAction;
use App\Domain\Actions\Authentication\LoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\Web\LoginRequest;
use App\Models\System\Staff;
use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View as ViewFacade;

class AuthController extends Controller
{
    public function __construct(
        private readonly LoginAction $loginAction,
        private readonly GetStaffByCredentialsAction $getStaffByCredentialsAction,
        private readonly GetSuperStaffAction $getSuperStaffAction,
        private readonly GetUserByIdAction $getUserByIdAction,
    ) {}

    /**
     * Show login form (unified for staff and users)
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('staff')->check() || Auth::guard('web')->check()) {
            return Redirect::to(route('site.index'));
        }

        return ViewFacade::make('site.login');
    }

    /**
     * Handle unified login
     */
    public function submitLogin(LoginRequest $request): RedirectResponse
    {
        // Already logged in
        if (Auth::guard('staff')->check() || Auth::guard('web')->check()) {
            return Redirect::to(route('site.index'));
        }

        $credentials = $request->validated();
        $username = (string) ($credentials['username'] ?? '');
        $password = (string) ($credentials['password'] ?? '');

        // 1) Try Staff guard (by username or email)
        $staff = $this->getStaffByCredentialsAction->execute($username);
        if ($staff && Hash::check($password, (string) $staff->password_hash)) {
            Auth::guard('staff')->login($staff, false);
            session()->regenerate();

            return Redirect::to(route('site.index'));
        }

        // 2) Try User login via existing LoginAction
        $loginData = [
            'email' => $username,
            'password' => $password,
            'device' => [],
        ];
        $result = $this->loginAction->execute($loginData);
        $data = $result->getData(true);

        if ($result->getStatusCode() === 200 && isset($data['user_id'])) {
            $user = $this->getUserByIdAction->execute((int) $data['user_id']);
            if ($user) {
                Auth::guard('web')->login($user, false);
                session()->regenerate();

                return Redirect::to(route('site.index'));
            }
        }

        return Redirect::back()
            ->withErrors(['username' => $data['error'] ?? 'Login failed'])
            ->withInput();
    }

    /**
     * Unified logout (logs out both guards)
     */
    public function logout(): RedirectResponse
    {
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        session()->invalidate();
        session()->regenerateToken();

        return Redirect::to(route('site.login'));
    }

    /**
     * Reset super-login impersonation back to super staff
     */
    public function reset(Request $request): RedirectResponse
    {
        if (session()->get('loginFromSuper')) {
            if (Auth::guard('staff')->check()) {
                Auth::guard('staff')->logout();
            }

            $super = $this->getSuperStaffAction->execute();
            if ($super) {
                Auth::guard('staff')->login($super, true);
            }

            session()->forget('loginFromSuper');
        }

        return Redirect::to(route('site.index'));
    }
}
