<?php

namespace App\DTOs\Orders;

use Illuminate\Http\Request;

class OrderFilterDTO
{
    public function __construct(
        public readonly ?string $status = null,
        public readonly ?string $customer = null,
        public readonly ?string $dateFrom = null,
        public readonly ?string $dateTo = null,
        public readonly ?int $warehouseId = null,
        public readonly string $sortBy = 'created_at',
        public readonly string $sortDirection = 'desc',
        public readonly int $perPage = 15
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            status: $request->get('status'),
            customer: $request->get('customer'),
            dateFrom: $request->get('date_from'),
            dateTo: $request->get('date_to'),
            warehouseId: $request->get('warehouse_id'),
            sortBy: $request->get('sort_by', 'created_at'),
            sortDirection: $request->get('sort_direction', 'desc'),
            perPage: (int) $request->get('per_page', 15)
        );
    }
}
