<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Finance\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices
     */
    public function index(Request $request): View
    {
        $query = Invoice::with(['user', 'invoiceLines', 'moneyTransactions']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by paid status
        if ($request->filled('paid_status')) {
            $query->where('paid_status', $request->get('paid_status'));
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

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate total sum
        $totalSum = $query->sum('total');

        return ViewFacade::make('invoice.index', [
            'invoices' => $invoices,
            'filters' => $request->only(['search', 'paid_status', 'createTimeRange', 'total_min', 'total_max']),
            'totalSum' => $totalSum,
        ]);
    }

    /**
     * Display the specified invoice
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load(['user', 'invoiceLines.bundle', 'moneyTransactions.creditCard']);

        return ViewFacade::make('invoice.show', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Check if invoice can be refunded
     */
    private function canBeRefunded(Invoice $invoice): bool
    {
        return $invoice->total > 0
            && $invoice->moneyTransactions->where('is_success', true)->count() === 1
            && $invoice->moneyTransactions->where('is_success', true)->first()->canBeRefund();
    }
}
