<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\System\AppleAppTransaction;
use App\Models\System\AppleNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class AppleController extends Controller
{
    /**
     * Display Apple App Transactions
     */
    public function appTransactions(Request $request): View
    {
        $query = AppleAppTransaction::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('error', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by process status
        if ($request->filled('is_process')) {
            $query->where('is_process', $request->get('is_process'));
        }

        // Filter by user ID
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->get('id_user'));
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('web.apple.app-transactions', [
            'transactions' => $transactions,
            'filters' => $request->only(['search', 'status', 'is_process', 'id_user', 'created_at_from', 'created_at_to']),
        ]);
    }

    /**
     * Show Apple App Transaction details
     */
    public function showAppTransaction(AppleAppTransaction $transaction): View
    {
        return ViewFacade::make('web.apple.app-transaction-show', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Display Apple Notifications
     */
    public function notifications(Request $request): View
    {
        $query = AppleNotification::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('notification_type', 'like', "%{$search}%")
                    ->orWhere('subtype', 'like', "%{$search}%")
                    ->orWhere('notification_uuid', 'like', "%{$search}%");
            });
        }

        // Filter by notification type
        if ($request->filled('notification_type')) {
            $query->where('notification_type', $request->get('notification_type'));
        }

        // Filter by subtype
        if ($request->filled('subtype')) {
            $query->where('subtype', $request->get('subtype'));
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('web.apple.notifications', [
            'notifications' => $notifications,
            'filters' => $request->only(['search', 'notification_type', 'subtype', 'created_at_from', 'created_at_to']),
        ]);
    }

    /**
     * Show Apple Notification details
     */
    public function showNotification(AppleNotification $notification): View
    {
        return ViewFacade::make('web.apple.notification-show', [
            'notification' => $notification,
        ]);
    }

    /**
     * Process Apple App Transaction
     */
    public function processTransaction(Request $request, AppleAppTransaction $transaction): Response
    {
        try {
            // Process transaction logic here
            $transaction->update(['is_process' => true]);

            return redirect()->back()
                ->with('success', 'Transaction processed successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to process transaction: '.$e->getMessage()]);
        }
    }

    /**
     * Retry Apple App Transaction
     */
    public function retryTransaction(Request $request, AppleAppTransaction $transaction): Response
    {
        try {
            // Retry transaction logic here
            $transaction->update(['is_process' => false]);

            return redirect()->back()
                ->with('success', 'Transaction retry initiated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to retry transaction: '.$e->getMessage()]);
        }
    }

    /**
     * Export Apple App Transactions to CSV
     */
    public function exportAppTransactions(Request $request): Response
    {
        try {
            $query = AppleAppTransaction::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('transaction_id', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('error', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }

            if ($request->filled('is_process')) {
                $query->where('is_process', $request->get('is_process'));
            }

            if ($request->filled('id_user')) {
                $query->where('id_user', $request->get('id_user'));
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Transaction ID', 'Status', 'Error', 'User ID', 'Is Process', 'Created At'];

            foreach ($transactions as $transaction) {
                $csvData[] = [
                    $transaction->id,
                    $transaction->transaction_id,
                    $transaction->status,
                    $transaction->error,
                    $transaction->id_user,
                    $transaction->is_process ? 'Yes' : 'No',
                    $transaction->created_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'apple_app_transactions_'.now()->format('Y-m-d_H-i-s').'.csv';

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
                ->withErrors(['error' => 'Failed to export transactions: '.$e->getMessage()]);
        }
    }

    /**
     * Export Apple Notifications to CSV
     */
    public function exportNotifications(Request $request): Response
    {
        try {
            $query = AppleNotification::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('notification_type', 'like', "%{$search}%")
                        ->orWhere('subtype', 'like', "%{$search}%")
                        ->orWhere('notification_uuid', 'like', "%{$search}%");
                });
            }

            if ($request->filled('notification_type')) {
                $query->where('notification_type', $request->get('notification_type'));
            }

            if ($request->filled('subtype')) {
                $query->where('subtype', $request->get('subtype'));
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $notifications = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Notification Type', 'Subtype', 'Notification UUID', 'Created At'];

            foreach ($notifications as $notification) {
                $csvData[] = [
                    $notification->id,
                    $notification->notification_type,
                    $notification->subtype,
                    $notification->notification_uuid,
                    $notification->created_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'apple_notifications_'.now()->format('Y-m-d_H-i-s').'.csv';

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
                ->withErrors(['error' => 'Failed to export notifications: '.$e->getMessage()]);
        }
    }
}
