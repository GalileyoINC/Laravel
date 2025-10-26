<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\Services\Product\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetProductListAction
{
    public function __construct(
        private readonly ProductServiceInterface $productService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $page = $data['page'] ?? 1;
        $limit = $data['limit'] ?? 20;
        $search = $data['search'] ?? null;
        $category = $data['category'] ?? null;
        $status = $data['status'] ?? null;
        $sortBy = $data['sort_by'] ?? 'created_at';
        $sortOrder = $data['sort_order'] ?? 'desc';

        $user = Auth::user();
        $products = $this->productService->getProductList($page, $limit, $search, $category, $status, $sortBy, $sortOrder, $user);

        return response()->json($products);
    }
}
