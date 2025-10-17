<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\Web\RefundRequest;
use App\Models\Finance\MoneyTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class MoneyTransactionController extends Controller
{
    /**
     * Display a listing of money transactions
     */
    public function index(Request $request): View
    {
        $query = MoneyTransaction::with(['user', 'invoice', 'creditCard']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->get('transaction_type'));
        }

        // Filter by success status
        if ($request->filled('is_success')) {
            $query->where('is_success', $request->get('is_success'));
        }

        // Filter by void status
        if ($request->filled('is_void')) {
            $query->where('is_void', $request->get('is_void'));
        }

        // Filter by test status
        if ($request->filled('is_test')) {
            $query->where('is_test', $request->get('is_test'));
        }

        // Filter by date range
        if ($request->filled('createTimeRange')) {
            $dateRange = explode(' - ', (string) $request->get('createTimeRange'));
            if (count($dateRange) === 2) {
                $query->whereBetween('created_at', [
                    \Carbon\Carbon::parse($dateRange[0])->startOfDay(),
                    \Carbon\Carbon::parse($dateRange[1])->endOfDay(),
                ]);
            }
        }

        // Filter by total amount
        if ($request->filled('total_min')) {
            $query->where('total', '>=', $request->get('total_min'));
        }
        if ($request->filled('total_max')) {
            $query->where('total', '<=', $request->get('total_max'));
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate total sum
        $totalSum = $query->sum('total');

        return ViewFacade::make('web.money-transaction.index', [
            'transactions' => $transactions,
            'filters' => $request->only(['search', 'transaction_type', 'is_success', 'is_void', 'is_test', 'createTimeRange', 'total_min', 'total_max']),
            'totalSum' => $totalSum,
        ]);
    }

    /**
     * Display the specified money transaction
     */
    public function show(MoneyTransaction $moneyTransaction): View
    {
        $moneyTransaction->load(['user', 'invoice', 'creditCard']);

        return ViewFacade::make('web.money-transaction.show', [
            'transaction' => $moneyTransaction,
        ]);
    }

    /**
     * Void a money transaction
     */
    public function void(MoneyTransaction $moneyTransaction): Response
    {
        try {
            if (! $moneyTransaction->canBeVoided()) {
                return response()->json(['error' => 'Transaction cannot be voided'], 400);
            }

            if (! $moneyTransaction->void()) {
                return response()->json(['error' => 'Failed to void transaction'], 500);
            }

            return response()->json(['success' => 'Transaction voided successfully']);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to void transaction: '.$e->getMessage()], 500);
        }
    }

    /**
     * Show refund form
     */
    public function refund(MoneyTransaction $moneyTransaction): View
    {
        if (! $moneyTransaction->canBeRefund()) {
            abort(400, 'Transaction cannot be refunded');
        }

        return ViewFacade::make('web.money-transaction.refund', [
            'transaction' => $moneyTransaction,
        ]);
    }

    /**
     * Process refund
     */
    public function processRefund(RefundRequest $request, MoneyTransaction $moneyTransaction): Response
    {
        try {
            if (! $moneyTransaction->canBeRefund()) {
                return response()->json(['error' => 'Transaction cannot be refunded'], 400);
            }

            $validated = $request->validated();

            // Process refund logic here
            // This would typically call a payment gateway API

            return response()->json(['success' => 'Refund processed successfully']);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to process refund: '.$e->getMessage()], 500);
        }
    }

    /**
     * Export transactions to CSV
     */
    public function toCsv(Request $request): Response
    {
        try {
            $query = MoneyTransaction::with(['user', 'creditCard']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('transaction_id', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('transaction_type')) {
                $query->where('transaction_type', $request->get('transaction_type'));
            }

            if ($request->filled('is_success')) {
                $query->where('is_success', $request->get('is_success'));
            }

            if ($request->filled('is_void')) {
                $query->where('is_void', $request->get('is_void'));
            }

            if ($request->filled('is_test')) {
                $query->where('is_test', $request->get('is_test'));
            }

            if ($request->filled('createTimeRange')) {
                $dateRange = explode(' - ', (string) $request->get('createTimeRange'));
                if (count($dateRange) === 2) {
                    $query->whereBetween('created_at', [
                        \Carbon\Carbon::parse($dateRange[0])->startOfDay(),
                        \Carbon\Carbon::parse($dateRange[1])->endOfDay(),
                    ]);
                }
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="money_transactions.csv"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function () use ($transactions) {
                $file = fopen('php://output', 'w');
                fputcsv($file, [
                    'ID', 'User', 'Invoice', 'Credit Card', 'Transaction ID',
                    'Success', 'Void', 'Test', 'Total', 'Created At', 'Updated At',
                ]);

                foreach ($transactions as $transaction) {
                    $card = null;
                    if ($transaction->creditCard) {
                        $card = ($transaction->creditCard->type ? $transaction->creditCard->type.' ' : '').
                               $transaction->creditCard->num.' ('.
                               $transaction->creditCard->expiration_year.'/'.
                               $transaction->creditCard->expiration_month.')';
                    }

                    fputcsv($file, [
                        $transaction->id,
                        $transaction->user->first_name.' '.$transaction->user->last_name,
                        $transaction->id_invoice,
                        $card,
                        $transaction->transaction_id,
                        $transaction->is_success ? 'Yes' : 'No',
                        $transaction->is_void ? 'Yes' : 'No',
                        $transaction->is_test ? 'Yes' : 'No',
                        number_format($transaction->total, 2, '.', ''),
                        $transaction->created_at->toDateTimeString(),
                        $transaction->updated_at ? $transaction->updated_at->toDateTimeString() : '',
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
     * Generate transaction report
     */
    public function report(Request $request): View
    {
        $month = $request->get('month', date('Y-m'));
        $now = \Carbon\Carbon::parse($month);
        $firstDayOfThisMonth = $now->copy()->startOfMonth();
        $firstDayOfNextMonth = $now->copy()->addMonth()->startOfMonth();

        // This would typically involve complex database queries
        // For now, we'll return a basic view structure

        return ViewFacade::make('web.money-transaction.report', [
            'month' => $month,
            'firstDayOfThisMonth' => $firstDayOfThisMonth,
            'firstDayOfNextMonth' => $firstDayOfNextMonth,
        ]);
    }
}
