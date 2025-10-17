<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Finance\CreditCard;
use App\Models\User\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class CreditCardController extends Controller
{
    /**
     * Display Credit Cards
     */
    public function index(Request $request): View
    {
        $query = CreditCard::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('num', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('anet_customer_payment_profile_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by card type
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        // Filter by expiration year
        if ($request->filled('expiration_year')) {
            $query->where('expiration_year', $request->get('expiration_year'));
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('id_user', $request->get('user_id'));
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        if ($request->filled('updated_at_from')) {
            $query->whereDate('updated_at', '>=', $request->get('updated_at_from'));
        }
        if ($request->filled('updated_at_to')) {
            $query->whereDate('updated_at', '<=', $request->get('updated_at_to'));
        }

        $creditCards = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get years for filter dropdown
        $years = array_combine(range(2021, date('Y') + 20), range(2021, date('Y') + 20));

        return ViewFacade::make('web.credit-card.index', [
            'creditCards' => $creditCards,
            'years' => $years,
            'filters' => $request->only(['search', 'type', 'expiration_year', 'user_id', 'created_at_from', 'created_at_to', 'updated_at_from', 'updated_at_to']),
        ]);
    }

    /**
     * Show Credit Card details
     */
    public function show(CreditCard $creditCard): View
    {
        return ViewFacade::make('web.credit-card.show', [
            'creditCard' => $creditCard,
        ]);
    }

    /**
     * Get Gateway Profile
     */
    public function getGatewayProfile(CreditCard $creditCard): Response
    {
        try {
            if (! $creditCard->anet_customer_payment_profile_id) {
                return redirect()->back()
                    ->withErrors(['error' => 'No gateway profile ID found for this credit card.']);
            }

            // Here you would make the actual API request to the payment gateway
            // For now, we'll return a JSON response with the profile ID
            return response()->json([
                'profile_id' => $creditCard->anet_customer_payment_profile_id,
                'card_type' => $creditCard->type,
                'last_four' => mb_substr((string) $creditCard->num, -4),
                'expiration' => "{$creditCard->expiration_year}/{$creditCard->expiration_month}",
                'message' => 'Gateway profile retrieved successfully',
            ]);

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to retrieve gateway profile: '.$e->getMessage()]);
        }
    }

    /**
     * Export Credit Cards to CSV
     */
    public function export(Request $request): Response
    {
        try {
            $query = CreditCard::with('user');

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('num', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('anet_customer_payment_profile_id', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('type')) {
                $query->where('type', $request->get('type'));
            }

            if ($request->filled('expiration_year')) {
                $query->where('expiration_year', $request->get('expiration_year'));
            }

            if ($request->filled('user_id')) {
                $query->where('id_user', $request->get('user_id'));
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $creditCards = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'User ID', 'First Name', 'Last Name', 'Card Number', 'Phone', 'Type', 'Expiration', 'Is Active', 'Gateway Profile ID', 'Created At', 'Updated At'];

            foreach ($creditCards as $card) {
                $csvData[] = [
                    $card->id,
                    $card->id_user,
                    $card->user->first_name ?? '',
                    $card->user->last_name ?? '',
                    $card->num,
                    $card->phone,
                    $card->type,
                    "{$card->expiration_year}/{$card->expiration_month}",
                    $card->is_active ? 'Yes' : 'No',
                    $card->anet_customer_payment_profile_id,
                    $card->created_at->format('Y-m-d H:i:s'),
                    $card->updated_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'credit_cards_'.now()->format('Y-m-d_H-i-s').'.csv';

            return response()->streamDownload(function () use ($csvData) {
                $file = fopen('php://output', 'w');
                foreach ($csvData as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            }, $filename, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to export credit cards: '.$e->getMessage()]);
        }
    }
}
