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

        if ($result->getData()->status === 'success') {
            // Find user and login with Laravel Auth
            $userId = (int) $result->getData()->user_id;
            $user = User::find($userId);
            if ($user) {
                /** @var User $user */
                Auth::login($user, false);
                $request->session()->regenerate();

                return Redirect::to(route('site.index'));
            }
        }

        return Redirect::back()
            ->withErrors(['username' => $result->getData()->error ?? 'Login failed'])
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
     * Self action (profile update)
     */
    public function self(SelfRequest $request): View|RedirectResponse
    {
        $staff = Staff::find(Auth::id());
        if (! $staff) {
            return Redirect::to(route('site.index'))
                ->withErrors(['error' => 'Staff member not found.']);
        }
        $staff->update($request->validated());

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
