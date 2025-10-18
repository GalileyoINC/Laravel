<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Web\StaffRequest;
use App\Models\System\Staff;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Display a listing of staff
     */
    public function index(Request $request): View
    {
        $query = Staff::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status (default to active)
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        } else {
            $query->where('status', 1); // Default to active
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        // Filter by created date
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->get('created_at'));
        }

        $staff = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('staff.index', [
            'staff' => $staff,
            'filters' => $request->only(['search', 'status', 'role', 'created_at']),
        ]);
    }

    /**
     * Show the form for creating a new staff
     */
    public function create(): View
    {
        return ViewFacade::make('staff.create');
    }

    /**
     * Store a newly created staff
     */
    public function store(StaffRequest $request): RedirectResponse
    {
            $validated = $request->validated();

            $data = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password']),
                'role' => $validated['role'] ?? Staff::ROLE_ADMIN,
                'status' => $validated['status'] ?? Staff::STATUS_ACTIVE,
            ];

            // Only super admin can set super login
            if (Auth::user()->isSuper() && isset($validated['is_superlogin'])) {
                $data['is_superlogin'] = $validated['is_superlogin'];
            }

            Staff::create($data);

            return Redirect::to(route('staff.index'))
                ->with('success', 'Staff created successfully.');
    }

    /**
     * Display the specified staff
     */
    public function show(Staff $staff): View
    {
        return ViewFacade::make('staff.show', [
            'staff' => $staff,
        ]);
    }

    /**
     * Show the form for editing the specified staff
     */
    public function edit(Staff $staff): View
    {
        return ViewFacade::make('staff.edit', [
            'staff' => $staff,
        ]);
    }

    /**
     * Update the specified staff
     */
    public function update(StaffRequest $request, Staff $staff): RedirectResponse
    {
            // Check permissions
            if (! $this->canChange($staff)) {
                return Redirect::back()
                    ->withErrors(['error' => 'You do not have permission to modify this staff member.']);
            }

            $validated = $request->validated();

            $data = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'role' => $validated['role'] ?? $staff->role,
                'status' => $validated['status'] ?? $staff->status,
            ];

            // Handle password update
            if (! empty($validated['password'])) {
                $data['password_hash'] = Hash::make($validated['password']);
            }

            // Only super admin can set super login
            if (Auth::user()->isSuper() && isset($validated['is_superlogin'])) {
                $data['is_superlogin'] = $validated['is_superlogin'];
            }

            $staff->update($data);

            return Redirect::to(route('staff.index'))
                ->with('success', 'Staff updated successfully.');
    }

    /**
     * Remove the specified staff
     */
    public function destroy(Staff $staff): RedirectResponse
    {
            // Check permissions
            if (! $this->canChange($staff)) {
                return Redirect::back()
                    ->withErrors(['error' => 'You do not have permission to delete this staff member.']);
            }

            $staff->delete();

            return Redirect::to(route('staff.index'))
                ->with('success', 'Staff deleted successfully.');
    }

    /**
     * Login as staff member (super admin only)
     */
    public function loginAs(Staff $staff): RedirectResponse
    {
            // Only super admin can login as other staff
            if (! Auth::user()->isSuper()) {
                return Redirect::back()
                    ->withErrors(['error' => 'Unauthorized action.']);
            }

            Auth::logout();
            Auth::login($staff, true);

            request()->session()->put('loginFromSuper', true);

            return Redirect::to(route('site.index'))
                ->with('success', 'Logged in as: '.$staff->username);
    }

    /**
     * Check if current user can change the staff member
     */
    private function canChange(Staff $staff): bool
    {
        $currentUser = Auth::user();

        if ($currentUser->isSuper()) {
            return true;
        }

        if ($currentUser->isAdmin() && ! $staff->isSuper()) {
            return true;
        }

        return false;
    }
}
