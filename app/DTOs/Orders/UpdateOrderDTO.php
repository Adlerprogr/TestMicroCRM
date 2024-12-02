<?php

namespace App\DTOs\Orders;

use App\Http\Requests\UpdateOrderRequest;

class UpdateOrderDTO
{
    public function __construct(
        public readonly string $customer,
        public readonly array $items
    ) {}

    public static function fromRequest(UpdateOrderRequest $request): self
    {
        return new self(
            customer: $request->get('customer'),
            items: $request->get('items')
        );
    }
}
