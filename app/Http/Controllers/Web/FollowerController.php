<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Follower\ExportFollowersToCsvAction;
use App\Domain\Actions\Follower\GetFollowerListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Follower\Web\FollowerIndexRequest;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FollowerController extends Controller
{
    public function __construct(
        private readonly GetFollowerListAction $getFollowerListAction,
        private readonly ExportFollowersToCsvAction $exportFollowersToCsvAction,
    ) {}

    /**
     * Display Followers
     */
    public function index(FollowerIndexRequest $request): View
    {
        $filters = $request->validated();
        $followers = $this->getFollowerListAction->execute($filters, 20);

        return ViewFacade::make('follower.index', [
            'followers' => $followers,
            'filters' => $filters,
        ]);
    }

    /**
     * Export Followers to CSV
     */
    public function export(FollowerIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportFollowersToCsvAction->execute($filters);
        $filename = 'followers_'.now()->format('Y-m-d_H-i-s').'.csv';

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
    }
}
