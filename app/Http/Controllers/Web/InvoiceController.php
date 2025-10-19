<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Invoice\GetInvoiceAction;
use App\Domain\Actions\Invoice\GetInvoiceListAction;
use App\Http\Controllers\Controller;
use App\Models\Finance\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly GetInvoiceListAction $getInvoiceListAction,
        private readonly GetInvoiceAction $getInvoiceAction,
    ) {}

    /**
     * Display a listing of invoices
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'paid_status', 'createTimeRange', 'total_min', 'total_max']);
        $result = $this->getInvoiceListAction->execute($filters, 20);

        return ViewFacade::make('invoice.index', [
            'invoices' => $result['invoices'],
            'filters' => $filters,
            'totalSum' => $result['totalSum'],
        ]);
    }

    /**
     * Display the specified invoice
     */
    public function show(Invoice $invoice): View
    {
        $invoice = $this->getInvoiceAction->execute($invoice->id);

        return ViewFacade::make('invoice.show', [
            'invoice' => $invoice,
        ]);
    }
}
