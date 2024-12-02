<?php

namespace App\DTOs\StockMovements;

use Illuminate\Http\Request;

class StockMovementFilterDTO
{
    public function __construct(
        public readonly ?int $productId = null,
        public readonly ?int $warehouseId = null,
        public readonly ?string $dateFrom = null,
        public readonly ?string $dateTo = null,
        public readonly string $sortBy = 'created_at',
        public readonly string $sortDirection = 'desc',
        public readonly int $perPage = 15
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            productId: $request->get('product_id'),
            warehouseId: $request->get('warehouse_id'),
            dateFrom: $request->get('date_from'),
            dateTo: $request->get('date_to'),
            sortBy: $request->get('sort_by', 'created_at'),
            sortDirection: $request->get('sort_direction', 'desc'),
            perPage: (int) $request->get('per_page', 15)
        );
    }
}
