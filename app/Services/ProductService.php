<?php

namespace App\Services;

use App\DTOs\Products\ProductFilterDTO;
use App\Models\Product;

class ProductService
{
    public function getProducts(ProductFilterDTO $dto)
    {
        return Product::query()
            ->with(['stocks' => function($query) {
                $query->where('stock', '>', 0);
            }])
            ->when($dto->search, function($query) use ($dto) {
                $query->where('name', 'like', "%{$dto->search}%");
            })
            ->when($dto->minPrice, function($query) use ($dto) {
                $query->where('price', '>=', $dto->minPrice);
            })
            ->when($dto->maxPrice, function($query) use ($dto) {
                $query->where('price', '<=', $dto->maxPrice);
            })
            ->when($dto->warehouseId, function($query) use ($dto) {
                $query->whereHas('stocks', function($q) use ($dto) {
                    $q->where('warehouse_id', $dto->warehouseId)
                        ->where('stock', '>', 0);
                });
            })
            ->orderBy($dto->sortBy, $dto->sortDirection)
            ->paginate($dto->perPage);
    }
}
