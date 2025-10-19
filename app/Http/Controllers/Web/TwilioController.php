<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Twilio\CreateTwilioIncomingAction;
use App\Domain\Actions\Twilio\ExportTwilioCarriersToCsvAction;
use App\Domain\Actions\Twilio\ExportTwilioIncomingToCsvAction;
use App\Domain\Actions\Twilio\GetTwilioCarrierListAction;
use App\Domain\Actions\Twilio\GetTwilioIncomingListAction;
use App\Domain\Actions\Twilio\UpdateTwilioCarrierAction;
use App\Domain\DTOs\Twilio\TwilioCarrierUpdateDTO;
use App\Domain\DTOs\Twilio\TwilioIncomingCreateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Twilio\Web\TwilioCarrierIndexRequest;
use App\Http\Requests\Twilio\Web\TwilioCarrierUpdateRequest;
use App\Http\Requests\Twilio\Web\TwilioIncomingIndexRequest;
use App\Http\Requests\Twilio\Web\TwilioIncomingStoreRequest;
use App\Models\Finance\Provider;
use App\Models\System\TwilioCarrier;
use App\Models\System\TwilioIncoming;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View as ViewFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TwilioController extends Controller
{
    public function __construct(
        private readonly GetTwilioCarrierListAction $getTwilioCarrierListAction,
        private readonly GetTwilioIncomingListAction $getTwilioIncomingListAction,
        private readonly ExportTwilioCarriersToCsvAction $exportTwilioCarriersToCsvAction,
        private readonly ExportTwilioIncomingToCsvAction $exportTwilioIncomingToCsvAction,
        private readonly CreateTwilioIncomingAction $createTwilioIncomingAction,
        private readonly UpdateTwilioCarrierAction $updateTwilioCarrierAction,
    ) {}

    /**
     * Display Twilio Carriers
     */
    public function carriers(TwilioCarrierIndexRequest $request): View
    {
        $filters = $request->validated();

        $carriers = $this->getTwilioCarrierListAction->execute($filters, 20);
        $providers = Provider::orderBy('name')->get();

        return ViewFacade::make('twilio.carriers', [
            'carriers' => $carriers,
            'providers' => $providers,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Twilio Carrier edit form
     */
    public function editCarrier(TwilioCarrier $carrier): View
    {
        $providers = Provider::orderBy('name')->get();

        return ViewFacade::make('twilio.carrier-edit', [
            'carrier' => $carrier,
            'providers' => $providers,
        ]);
    }

    /**
     * Update Twilio Carrier
     */
    public function updateCarrier(TwilioCarrierUpdateRequest $request, TwilioCarrier $carrier): RedirectResponse
    {
        $validated = $request->validated();
        $dto = new TwilioCarrierUpdateDTO(
            carrierId: $carrier->id,
            providerId: (int) $validated['provider_id'],
        );

        $this->updateTwilioCarrierAction->execute($dto);

        return redirect()->route('twilio.carriers')
            ->with('success', 'Twilio Carrier updated successfully.');
    }

    /**
     * Display Twilio Incoming Messages
     */
    public function incoming(TwilioIncomingIndexRequest $request): View
    {
        $filters = $request->validated();

        $incoming = $this->getTwilioIncomingListAction->execute($filters, 20);

        return ViewFacade::make('twilio.incoming', [
            'incoming' => $incoming,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Twilio Incoming Message details
     */
    public function showIncoming(TwilioIncoming $incoming): View
    {
        return ViewFacade::make('twilio.incoming-show', [
            'incoming' => $incoming,
        ]);
    }

    /**
     * Create Twilio Incoming Message
     */
    public function createIncoming(): View
    {
        return ViewFacade::make('twilio.incoming-create');
    }

    /**
     * Store Twilio Incoming Message
     */
    public function storeIncoming(TwilioIncomingStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new TwilioIncomingCreateDTO(
            number: $validated['number'],
            body: $validated['body'],
            message: $validated['message'] ?? null,
        );

        $this->createTwilioIncomingAction->execute($dto);

        return redirect()->route('twilio.incoming')
            ->with('success', 'Twilio Incoming message created successfully.');
    }

    /**
     * Export Twilio Carriers to CSV
     */
    public function exportCarriers(TwilioCarrierIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportTwilioCarriersToCsvAction->execute($filters);
        $filename = 'twilio_carriers_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
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
     * Export Twilio Incoming Messages to CSV
     */
    public function exportIncoming(TwilioIncomingIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportTwilioIncomingToCsvAction->execute($filters);
        $filename = 'twilio_incoming_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
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
}
