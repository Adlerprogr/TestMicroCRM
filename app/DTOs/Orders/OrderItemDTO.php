<?php

namespace App\DTOs\Orders;

class OrderItemDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly int $count
    ) {}
}
