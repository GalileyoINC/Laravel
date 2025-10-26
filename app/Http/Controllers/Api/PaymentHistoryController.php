<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentHistoryController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $page = (int) ($request->input('page', 1));
        $pageSize = (int) ($request->input('page_size', 20));

        return response()->json([
            'status' => 'success',
            'data' => [
                'count' => 0,
                'page' => $page,
                'page_size' => $pageSize,
                'list' => [],
            ],
        ]);
    }
}
