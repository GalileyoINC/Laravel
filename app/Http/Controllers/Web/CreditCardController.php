<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\CreditCard\ExportCreditCardsToCsvAction;
use App\Domain\Actions\CreditCard\GetCreditCardListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreditCard\Web\CreditCardIndexRequest;
use App\Models\Finance\CreditCard;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CreditCardController extends Controller
{
    public function __construct(
        private readonly GetCreditCardListAction $getCreditCardListAction,
        private readonly ExportCreditCardsToCsvAction $exportCreditCardsToCsvAction,
    ) {}

    /**
     * Display Credit Cards
     */
    public function index(CreditCardIndexRequest $request): View
    {
        $filters = $request->validated();
        $creditCards = $this->getCreditCardListAction->execute($filters, 20);

        // Get years for filter dropdown
        $years = array_combine(range(2021, date('Y') + 20), range(2021, date('Y') + 20));

        return ViewFacade::make('credit-card.index', [
            'creditCards' => $creditCards,
            'years' => $years,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Credit Card details
     */
    public function show(CreditCard $creditCard): View
    {
        return ViewFacade::make('credit-card.show', [
            'creditCard' => $creditCard,
        ]);
    }

    /**
     * Get Gateway Profile
     */
    public function getGatewayProfile(CreditCard $creditCard): Response
    {
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
    }

    /**
     * Export Credit Cards to CSV
     */
    public function export(CreditCardIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $rows = $this->exportCreditCardsToCsvAction->execute($filters);
        $filename = 'credit_cards_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $file = fopen('php://output', 'w');
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
