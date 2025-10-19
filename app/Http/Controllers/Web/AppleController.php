<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Apple\ExportAppleAppTransactionsToCsvAction;
use App\Domain\Actions\Apple\ExportAppleNotificationsToCsvAction;
use App\Domain\Actions\Apple\GetAppleNotificationsListAction;
use App\Domain\Actions\Apple\GetAppleTransactionsListAction;
use App\Domain\Actions\Apple\ProcessAppleTransactionAction;
use App\Domain\Actions\Apple\RetryAppleTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Apple\Web\AppleNotificationsRequest;
use App\Http\Requests\Apple\Web\AppleTransactionsRequest;
use App\Models\Notification\AppleNotification;
use App\Models\Order\AppleAppTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View as ViewFacade;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AppleController extends Controller
{
    public function __construct(
        private readonly GetAppleTransactionsListAction $getAppleTransactionsListAction,
        private readonly GetAppleNotificationsListAction $getAppleNotificationsListAction,
        private readonly ExportAppleAppTransactionsToCsvAction $exportAppleAppTransactionsToCsvAction,
        private readonly ExportAppleNotificationsToCsvAction $exportAppleNotificationsToCsvAction,
        private readonly ProcessAppleTransactionAction $processAppleTransactionAction,
        private readonly RetryAppleTransactionAction $retryAppleTransactionAction,
    ) {}

    /**
     * Display Apple App Transactions
     */
    public function appTransactions(AppleTransactionsRequest $request): View
    {
        $filters = $request->validated();
        $transactions = $this->getAppleTransactionsListAction->execute($filters, 20);

        return ViewFacade::make('apple.app-transactions', [
            'transactions' => $transactions,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Apple App Transaction details
     */
    public function showAppTransaction(AppleAppTransaction $transaction): View
    {
        return ViewFacade::make('apple.app-transaction-show', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Display Apple Notifications
     */
    public function notifications(AppleNotificationsRequest $request): View
    {
        $filters = $request->validated();
        $notifications = $this->getAppleNotificationsListAction->execute($filters, 20);

        return ViewFacade::make('apple.notifications', [
            'notifications' => $notifications,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Apple Notification details
     */
    public function showNotification(AppleNotification $notification): View
    {
        return ViewFacade::make('apple.notification-show', [
            'notification' => $notification,
        ]);
    }

    /**
     * Process Apple App Transaction
     */
    public function processTransaction(AppleAppTransaction $transaction): RedirectResponse
    {
        $this->processAppleTransactionAction->execute($transaction->id);

        return redirect()->back()
            ->with('success', 'Transaction processed successfully.');
    }

    /**
     * Retry Apple App Transaction
     */
    public function retryTransaction(AppleAppTransaction $transaction): RedirectResponse
    {
        $this->retryAppleTransactionAction->execute($transaction->id);

        return redirect()->back()
            ->with('success', 'Transaction retry initiated successfully.');
    }

    /**
     * Export Apple App Transactions to CSV
     */
    public function exportAppTransactions(AppleTransactionsRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $rows = $this->exportAppleAppTransactionsToCsvAction->execute($filters);

        $filename = 'apple_app_transactions_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                throw new RuntimeException('Failed to open output stream');
            }
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Export Apple Notifications to CSV
     */
    public function exportNotifications(AppleNotificationsRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $rows = $this->exportAppleNotificationsToCsvAction->execute($filters);

        $filename = 'apple_notifications_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                throw new RuntimeException('Failed to open output stream');
            }
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
