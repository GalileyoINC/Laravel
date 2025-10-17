<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Authentication\LoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\Web\LoginRequest;
use App\Http\Requests\Authentication\Web\SelfRequest;
use App\Models\System\Staff;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function __construct(
        private readonly LoginAction $loginAction
    ) {}

    /**
     * Show login form
     */
    public function login(): View
    {
        if (Auth::check()) {
            return Redirect::to(route('web.site.index'));
        }

        return view('web.site.login');
    }

    /**
     * Handle login form submission
     */
    public function loginSubmit(LoginRequest $request): RedirectResponse
    {
        if (Auth::check()) {
            return Redirect::to(route('web.site.index'));
        }

        try {
            $loginData = [
                'email' => $request->validated()['username'],
                'password' => $request->validated()['password'],
                'device' => [],
            ];

            $result = $this->loginAction->execute($loginData);

            if ($result->getData()->status === 'success') {
                // Find user and login with Laravel Auth
                $user = \App\Models\User\User::find($result->getData()->user_id);
                if ($user) {
                    Auth::login($user, false);
                    $request->session()->regenerate();

                    return Redirect::intended(route('web.site.index'));
                }
            }

            return Redirect::back()
                ->withErrors(['username' => $result->getData()->error ?? 'Login failed'])
                ->withInput();

        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors(['username' => 'Login failed: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Logout action
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to(route('web.site.login'));
    }

    /**
     * Index action (Dashboard)
     */
    public function index(): View
    {
        return ViewFacade::make('web.site.index');
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

        return Redirect::to(route('web.site.index'));
    }

    /**
     * Self action (profile update)
     */
    public function self(SelfRequest $request): View|RedirectResponse
    {
        $staff = Staff::find(Auth::id());
        if (! $staff) {
            return Redirect::to(route('web.site.index'))
                ->withErrors(['error' => 'Staff member not found.']);
        }

        try {
            $staff->update($request->validated());

            return Redirect::to(route('web.site.index'))
                ->with('success', 'You have successfully updated your data.');
        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Failed to update profile: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Error action
     */
    public function error(): View
    {
        return ViewFacade::make('web.site.error');
    }
}
