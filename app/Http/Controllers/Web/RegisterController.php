<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Register\ExportRegisterListAction;
use App\Domain\Actions\Register\ExportRegisterUniqueListAction;
use App\Domain\Actions\Register\GetRegisterListAction;
use App\Domain\Actions\Register\GetRegisterUniqueListAction;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegisterController extends Controller
{
    public function __construct(
        private readonly GetRegisterListAction $getRegisterListAction,
        private readonly GetRegisterUniqueListAction $getRegisterUniqueListAction,
        private readonly ExportRegisterListAction $exportRegisterListAction,
        private readonly ExportRegisterUniqueListAction $exportRegisterUniqueListAction,
    ) {}

    /**
     * Display a listing of registers
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'is_unfinished_signup', 'created_at']);
        $registers = $this->getRegisterListAction->execute($filters, 20);

        $isUnfinishedSignup = $request->get('is_unfinished_signup', 0);

        return ViewFacade::make('register.index', [
            'registers' => $registers,
            'filters' => $filters,
            'isUnfinishedSignup' => $isUnfinishedSignup,
        ]);
    }

    /**
     * Display unique registers
     */
    public function indexUnique(Request $request): View
    {
        $filters = $request->only(['search', 'is_unfinished_signup', 'created_at']);
        $registers = $this->getRegisterUniqueListAction->execute($filters, 20);

        $isUnfinishedSignup = $request->get('is_unfinished_signup', 0);

        return ViewFacade::make('register.index_unique', [
            'registers' => $registers,
            'filters' => $filters,
            'isUnfinishedSignup' => $isUnfinishedSignup,
        ]);
    }

    /**
     * Display unfinished signups
     */
    public function signups(Request $request): View
    {
        $request->merge(['is_unfinished_signup' => 1]);

        return $this->index($request);
    }

    /**
     * Export registers to CSV
     */
    public function toCsv(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'is_unfinished_signup', 'created_at']);

        $rows = $this->exportRegisterListAction->execute($filters);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="register_list.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
            }
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export unique registers to CSV
     */
    public function toCsvUnique(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'is_unfinished_signup', 'created_at']);
        $rows = $this->exportRegisterUniqueListAction->execute($filters);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="register_list.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
            }
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
