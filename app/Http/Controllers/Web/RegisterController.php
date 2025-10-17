<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User\Register;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Display a listing of registers
     */
    public function index(Request $request): View
    {
        $query = Register::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by unfinished signup
        if ($request->filled('is_unfinished_signup')) {
            $query->where('is_unfinished_signup', $request->get('is_unfinished_signup'));
        }

        // Filter by created date
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->get('created_at'));
        }

        $registers = $query->orderBy('created_at', 'desc')->paginate(20);

        $isUnfinishedSignup = $request->get('is_unfinished_signup', 0);

        return ViewFacade::make('web.register.index', [
            'registers' => $registers,
            'filters' => $request->only(['search', 'is_unfinished_signup', 'created_at']),
            'isUnfinishedSignup' => $isUnfinishedSignup,
        ]);
    }

    /**
     * Display unique registers
     */
    public function indexUnique(Request $request): View
    {
        $query = Register::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by unfinished signup
        if ($request->filled('is_unfinished_signup')) {
            $query->where('is_unfinished_signup', $request->get('is_unfinished_signup'));
        }

        // Filter by created date
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->get('created_at'));
        }

        // Get unique records by email
        $registers = $query->selectRaw('MIN(id) as min_id, email, first_name, last_name, MIN(created_at) as min_created_at')
            ->groupBy('email', 'first_name', 'last_name')
            ->orderBy('min_created_at', 'desc')
            ->paginate(20);

        $isUnfinishedSignup = $request->get('is_unfinished_signup', 0);

        return ViewFacade::make('web.register.index_unique', [
            'registers' => $registers,
            'filters' => $request->only(['search', 'is_unfinished_signup', 'created_at']),
            'isUnfinishedSignup' => $isUnfinishedSignup,
        ]);
    }

    /**
     * Display unfinished signups
     */
    public function signups(Request $request): View
    {
        $request->merge(['is_unfinished_signup' => 1]);

        return $this->index($request);
    }

    /**
     * Export registers to CSV
     */
    public function toCsv(Request $request): Response
    {
        try {
            $query = Register::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            }

            if ($request->filled('is_unfinished_signup')) {
                $query->where('is_unfinished_signup', $request->get('is_unfinished_signup'));
            }

            if ($request->filled('created_at')) {
                $query->whereDate('created_at', $request->get('created_at'));
            }

            $registers = $query->orderBy('created_at', 'desc')->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="register_list.csv"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function () use ($registers) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Email', 'First Name', 'Last Name', 'Created At']);

                foreach ($registers as $register) {
                    fputcsv($file, [
                        $register->id,
                        $register->email,
                        $register->first_name,
                        $register->last_name,
                        $register->created_at->toDateTimeString(),
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to export CSV: '.$e->getMessage()], 500);
        }
    }

    /**
     * Export unique registers to CSV
     */
    public function toCsvUnique(Request $request): Response
    {
        try {
            $query = Register::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            }

            if ($request->filled('is_unfinished_signup')) {
                $query->where('is_unfinished_signup', $request->get('is_unfinished_signup'));
            }

            if ($request->filled('created_at')) {
                $query->whereDate('created_at', $request->get('created_at'));
            }

            // Get unique records by email
            $registers = $query->selectRaw('MIN(id) as min_id, email, first_name, last_name, MIN(created_at) as min_created_at')
                ->groupBy('email', 'first_name', 'last_name')
                ->orderBy('min_created_at', 'desc')
                ->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="register_list.csv"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function () use ($registers) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Email', 'First Name', 'Last Name', 'Created At']);

                foreach ($registers as $register) {
                    fputcsv($file, [
                        $register->min_id,
                        $register->email,
                        $register->first_name,
                        $register->last_name,
                        $register->min_created_at,
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to export CSV: '.$e->getMessage()], 500);
        }
    }
}
