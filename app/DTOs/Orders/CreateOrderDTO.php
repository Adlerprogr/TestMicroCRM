<?php

namespace App\DTOs\Orders;

use App\Http\Requests\CreateOrderRequest;

class CreateOrderDTO
{
    public function __construct(
        public readonly string $customer,
        public readonly int $warehouseId,
        public readonly array $items
    ) {}

    public static function fromRequest(CreateOrderRequest $request): self
    {
        return new self(
            customer: $request->get('customer'),
            warehouseId: $request->get('warehouse_id'),
            items: $request->get('items')
        );
    }
}
