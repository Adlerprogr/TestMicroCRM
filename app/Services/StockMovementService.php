<?php

namespace App\Services;

use App\DTOs\StockMovements\StockMovementFilterDTO;
use App\Models\StockMovement;

class StockMovementService
{
    public function getMovements(StockMovementFilterDTO $dto)
    {
        return StockMovement::query()
            ->with(['product', 'warehouse'])
            ->when($dto->productId, function($query) use ($dto) {
                $query->where('product_id', $dto->productId);
            })
            ->when($dto->warehouseId, function($query) use ($dto) {
                $query->where('warehouse_id', $dto->warehouseId);
            })
            ->when($dto->dateFrom, function($query) use ($dto) {
                $query->where('created_at', '>=', $dto->dateFrom);
            })
            ->when($dto->dateTo, function($query) use ($dto) {
                $query->where('created_at', '<=', $dto->dateTo);
            })
            ->orderBy($dto->sortBy, $dto->sortDirection)
            ->paginate($dto->perPage);
    }
}
