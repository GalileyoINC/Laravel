<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\FollowerList\ExportFollowerListsToCsvAction;
use App\Domain\Actions\FollowerList\GetFollowerListsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\FollowerList\Web\FollowerListIndexRequest;
use App\Models\Subscription\FollowerList;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FollowerListController extends Controller
{
    public function __construct(
        private readonly GetFollowerListsAction $getFollowerListsAction,
        private readonly ExportFollowerListsToCsvAction $exportFollowerListsToCsvAction,
    ) {}

    /**
     * Display Follower Lists
     */
    public function index(FollowerListIndexRequest $request): View
    {
        $filters = $request->validated();
        $followerLists = $this->getFollowerListsAction->execute($filters, 20);

        return ViewFacade::make('follower-list.index', [
            'followerLists' => $followerLists,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Follower List Details
     */
    public function show(FollowerList $followerList): View
    {
        $followerList->load(['user']);

        return ViewFacade::make('follower-list.show', [
            'followerList' => $followerList,
        ]);
    }

    /**
     * Export Follower Lists to CSV
     */
    public function export(FollowerListIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportFollowerListsToCsvAction->execute($filters);
        $filename = 'follower_lists_'.now()->format('Y-m-d_H-i-s').'.csv';

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
