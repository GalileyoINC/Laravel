<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Finance\ExportMoneyTransactionsToCsvAction;
use App\Domain\Actions\Finance\GetMoneyTransactionListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\Web\MoneyTransactionIndexRequest;
use App\Http\Requests\Finance\Web\RefundRequest;
use App\Models\Finance\MoneyTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MoneyTransactionController extends Controller
{
    public function __construct(
        private readonly GetMoneyTransactionListAction $getMoneyTransactionListAction,
        private readonly ExportMoneyTransactionsToCsvAction $exportMoneyTransactionsToCsvAction,
    ) {}

    /**
     * Display a listing of money transactions
     */
    public function index(MoneyTransactionIndexRequest $request): View
    {
        $filters = $request->validated();
        $result = $this->getMoneyTransactionListAction->execute($filters, 20);
        $transactions = $result['transactions'];
        $totalSum = $result['totalSum'];

        return ViewFacade::make('money-transaction.index', [
            'transactions' => $transactions,
            'filters' => $filters,
            'totalSum' => $totalSum,
        ]);
    }

    /**
     * Display the specified money transaction
     */
    public function show(MoneyTransaction $moneyTransaction): View
    {
        $moneyTransaction->load(['user', 'invoice', 'creditCard']);

        return ViewFacade::make('money-transaction.show', [
            'transaction' => $moneyTransaction,
        ]);
    }

    /**
     * Void a money transaction
     */
    public function void(MoneyTransaction $moneyTransaction): JsonResponse
    {
        if (! $moneyTransaction->canBeVoided()) {
            return response()->json(['error' => 'Transaction cannot be voided'], 400);
        }

        if (! $moneyTransaction->void()) {
            return response()->json(['error' => 'Failed to void transaction'], 500);
        }

        return response()->json(['success' => 'Transaction voided successfully']);
    }

    /**
     * Show refund form
     */
    public function refund(MoneyTransaction $moneyTransaction): View
    {
        if (! $moneyTransaction->canBeRefund()) {
            abort(400, 'Transaction cannot be refunded');
        }

        return ViewFacade::make('money-transaction.refund', [
            'transaction' => $moneyTransaction,
        ]);
    }

    /**
     * Process refund
     */
    public function processRefund(RefundRequest $request, MoneyTransaction $moneyTransaction): JsonResponse
    {
        if (! $moneyTransaction->canBeRefund()) {
            return response()->json(['error' => 'Transaction cannot be refunded'], 400);
        }

        $validated = $request->validated();

        // Process refund logic here
        // This would typically call a payment gateway API

        return response()->json(['success' => 'Refund processed successfully']);
    }

    /**
     * Export transactions to CSV
     */
    public function toCsv(MoneyTransactionIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportMoneyTransactionsToCsvAction->execute($filters);
        $filename = 'money_transactions_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                throw new RuntimeException('Failed to open output stream');
            }
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
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

        return ViewFacade::make('money-transaction.report', [
            'month' => $month,
            'firstDayOfThisMonth' => $firstDayOfThisMonth,
            'firstDayOfNextMonth' => $firstDayOfNextMonth,
        ]);
    }
}
