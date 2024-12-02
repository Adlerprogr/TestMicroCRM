<?php

namespace App\DTOs\Products;

use Illuminate\Http\Request;

class ProductFilterDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?float $minPrice = null,
        public readonly ?float $maxPrice = null,
        public readonly ?int $warehouseId = null,
        public readonly string $sortBy = 'name',
        public readonly string $sortDirection = 'asc',
        public readonly int $perPage = 15
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->get('search'),
            minPrice: $request->get('min_price'),
            maxPrice: $request->get('max_price'),
            warehouseId: $request->get('warehouse_id'),
            sortBy: $request->get('sort_by', 'name'),
            sortDirection: $request->get('sort_direction', 'asc'),
            perPage: (int) $request->get('per_page', 15)
        );
    }
}
