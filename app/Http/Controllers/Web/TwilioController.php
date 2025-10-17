<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\System\Provider;
use App\Models\System\TwilioCarrier;
use App\Models\System\TwilioIncoming;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class TwilioController extends Controller
{
    /**
     * Display Twilio Carriers
     */
    public function carriers(Request $request): View
    {
        $query = TwilioCarrier::with('provider');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by provider
        if ($request->filled('provider_id')) {
            $query->where('provider_id', $request->get('provider_id'));
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $carriers = $query->orderBy('created_at', 'desc')->paginate(20);
        $providers = Provider::orderBy('name')->get();

        return ViewFacade::make('web.twilio.carriers', [
            'carriers' => $carriers,
            'providers' => $providers,
            'filters' => $request->only(['search', 'provider_id', 'created_at_from', 'created_at_to']),
        ]);
    }

    /**
     * Show Twilio Carrier edit form
     */
    public function editCarrier(TwilioCarrier $carrier): View
    {
        $providers = Provider::orderBy('name')->get();

        return ViewFacade::make('web.twilio.carrier-edit', [
            'carrier' => $carrier,
            'providers' => $providers,
        ]);
    }

    /**
     * Update Twilio Carrier
     */
    public function updateCarrier(Request $request, TwilioCarrier $carrier): Response
    {
        try {
            $request->validate([
                'provider_id' => 'required|exists:providers,id',
            ]);

            $carrier->update([
                'provider_id' => $request->get('provider_id'),
            ]);

            return redirect()->route('web.twilio.carriers')
                ->with('success', 'Twilio Carrier updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update carrier: '.$e->getMessage()]);
        }
    }

    /**
     * Display Twilio Incoming Messages
     */
    public function incoming(Request $request): View
    {
        $query = TwilioIncoming::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        // Filter by number
        if ($request->filled('number')) {
            $query->where('number', 'like', "%{$request->get('number')}%");
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $incoming = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('web.twilio.incoming', [
            'incoming' => $incoming,
            'filters' => $request->only(['search', 'number', 'created_at_from', 'created_at_to']),
        ]);
    }

    /**
     * Show Twilio Incoming Message details
     */
    public function showIncoming(TwilioIncoming $incoming): View
    {
        return ViewFacade::make('web.twilio.incoming-show', [
            'incoming' => $incoming,
        ]);
    }

    /**
     * Create Twilio Incoming Message
     */
    public function createIncoming(): View
    {
        return ViewFacade::make('web.twilio.incoming-create');
    }

    /**
     * Store Twilio Incoming Message
     */
    public function storeIncoming(Request $request): Response
    {
        try {
            $request->validate([
                'number' => 'required|string|max:20',
                'body' => 'required|string|max:1600',
            ]);

            TwilioIncoming::create([
                'number' => $request->get('number'),
                'body' => $request->get('body'),
                'message' => $request->get('message', ''),
            ]);

            return redirect()->route('web.twilio.incoming')
                ->with('success', 'Twilio Incoming message created successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create incoming message: '.$e->getMessage()]);
        }
    }

    /**
     * Export Twilio Carriers to CSV
     */
    public function exportCarriers(Request $request): Response
    {
        try {
            $query = TwilioCarrier::with('provider');

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            if ($request->filled('provider_id')) {
                $query->where('provider_id', $request->get('provider_id'));
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $carriers = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Name', 'Provider', 'Created At'];

            foreach ($carriers as $carrier) {
                $csvData[] = [
                    $carrier->id,
                    $carrier->name,
                    $carrier->provider->name ?? '',
                    $carrier->created_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'twilio_carriers_'.now()->format('Y-m-d_H-i-s').'.csv';

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
                ->withErrors(['error' => 'Failed to export carriers: '.$e->getMessage()]);
        }
    }

    /**
     * Export Twilio Incoming Messages to CSV
     */
    public function exportIncoming(Request $request): Response
    {
        try {
            $query = TwilioIncoming::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('number', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                });
            }

            if ($request->filled('number')) {
                $query->where('number', 'like', "%{$request->get('number')}%");
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $incoming = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Number', 'Body', 'Message', 'Created At'];

            foreach ($incoming as $message) {
                $csvData[] = [
                    $message->id,
                    $message->number,
                    $message->body,
                    $message->message,
                    $message->created_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'twilio_incoming_'.now()->format('Y-m-d_H-i-s').'.csv';

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
                ->withErrors(['error' => 'Failed to export incoming messages: '.$e->getMessage()]);
        }
    }
}
