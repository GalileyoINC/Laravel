<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Users\CreateUserAction;
use App\Domain\Actions\Users\GetUsersListAction;
use App\Domain\DTOs\Users\CreateUserDTO;
use App\Domain\DTOs\Users\UsersListRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Web\UserRequest;
use App\Models\User\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly CreateUserAction $createUserAction,
        private readonly GetUsersListAction $getUsersListAction
    ) {}

    /**
     * Display a listing of users
     */
    public function index(Request $request): View
    {
        try {
            $dto = new UsersListRequestDTO(
                page: $request->get('page', 1),
                limit: $request->get('limit', 20),
                search: $request->get('search'),
                status: $request->get('status', 1)
            );

            $result = $this->getUsersListAction->execute($dto->toArray());
            $users = $result->getData()->data;

            return ViewFacade::make('web.user.index', [
                'users' => $users,
                'filters' => $request->only(['search', 'status']),
            ]);
        } catch (Exception $e) {
            return ViewFacade::make('web.user.index', [
                'users' => collect(),
                'filters' => [],
                'error' => 'Failed to load users: '.$e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new user
     */
    public function create(): View
    {
        return ViewFacade::make('web.user.create');
    }

    /**
     * Store a newly created user
     */
    public function store(UserRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $dto = new CreateUserDTO(
                firstName: $validated['first_name'],
                lastName: $validated['last_name'],
                email: $validated['email'],
                password: $validated['password'],
                country: $validated['country'],
                zip: $validated['zip'] ?? null,
                state: $validated['state'] ?? null,
                city: $validated['city'] ?? null,
                role: $validated['role'] ?? 2,
                status: $validated['status'] ?? 1
            );

            $result = $this->createUserAction->execute($dto);

            if ($result->getData()->success) {
                return Redirect::to(route('web.user.index'))
                    ->with('success', 'User created successfully.');
            }

            return Redirect::back()
                ->withErrors(['error' => $result->getData()->message ?? 'Failed to create user.'])
                ->withInput();

        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Failed to create user: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user): View
    {
        $user->load(['phoneNumbers', 'creditCards', 'subscriptions']);

        return ViewFacade::make('web.user.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user): View
    {
        return ViewFacade::make('web.user.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $data = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'country' => $validated['country'],
                'state' => $validated['state'] ?? null,
                'zip' => $validated['zip'] ?? null,
                'city' => $validated['city'] ?? null,
                'role' => $validated['role'] ?? $user->role,
                'status' => $validated['status'] ?? $user->status,
            ];

            // Handle password update
            if (! empty($validated['password'])) {
                $data['password_hash'] = bcrypt($validated['password']);
            }

            $user->update($data);

            return Redirect::to(route('web.user.index'))
                ->with('success', 'User updated successfully.');

        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Failed to update user: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            // Don't allow deletion of admin users
            if ($user->role === 1) {
                return Redirect::back()
                    ->withErrors(['error' => 'Cannot delete admin users.']);
            }

            $user->delete();

            return Redirect::to(route('web.user.index'))
                ->with('success', 'User deleted successfully.');

        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Failed to delete user: '.$e->getMessage()]);
        }
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        try {
            $user->update([
                'status' => $user->status === 1 ? 0 : 1,
            ]);

            $status = $user->status === 1 ? 'activated' : 'deactivated';

            return Redirect::back()
                ->with('success', "User {$status} successfully.");

        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Failed to toggle user status: '.$e->getMessage()]);
        }
    }

    /**
     * Login as user (super admin feature)
     */
    public function loginAsUser(User $user): RedirectResponse
    {
        try {
            // Check if current user is super admin
            if (! Auth::user() || Auth::user()->role !== 1) {
                return Redirect::back()
                    ->withErrors(['error' => 'Unauthorized action.']);
            }

            Auth::login($user);

            return Redirect::to(route('web.site.index'))
                ->with('success', 'Logged in as user: '.$user->first_name.' '.$user->last_name);

        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Failed to login as user: '.$e->getMessage()]);
        }
    }
}
