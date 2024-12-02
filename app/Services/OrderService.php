<?php

namespace App\Services;

use App\DTOs\Orders\CreateOrderDTO;
use App\DTOs\Orders\OrderFilterDTO;
use App\DTOs\Orders\UpdateOrderDTO;
use App\Exceptions\BusinessException;
use App\Models\Order;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Создать новый заказ
     */
    public function createOrder(CreateOrderDTO $dto): Order
    {
        try {
            $productIds = array_column($dto->items, 'product_id');
            $stocks = Stock::where('warehouse_id', $dto->warehouseId)
                ->whereIn('product_id', $productIds)
                ->get()
                ->keyBy('product_id');

            foreach ($dto->items as $item) {
                if (!isset($stocks[$item['product_id']])) {
                    throw new BusinessException(
                        "Товар с ID {$item['product_id']} отсутствует на складе {$dto->warehouseId}"
                    );
                }

                if ($stocks[$item['product_id']]->stock < $item['count']) {
                    throw new BusinessException(
                        "Недостаточно товара с ID {$item['product_id']} на складе. " .
                        "Доступно: {$stocks[$item['product_id']]->stock}, запрошено: {$item['count']}"
                    );
                }
            }

            return DB::transaction(function() use ($dto, $stocks) {
                $order = Order::create([
                    'customer' => $dto->customer,
                    'warehouse_id' => $dto->warehouseId,
                    'status' => Order::STATUS_ACTIVE
                ]);

                foreach ($dto->items as $item) {
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'count' => $item['count']
                    ]);

                    Stock::where('warehouse_id', $dto->warehouseId)
                        ->where('product_id', $item['product_id'])
                        ->update([
                            'stock' => $stocks[$item['product_id']]->stock - $item['count'],
                            'updated_at' => now()
                        ]);

                    StockMovement::create([
                        'product_id' => $item['product_id'],
                        'warehouse_id' => $dto->warehouseId,
                        'quantity' => -$item['count'],
                        'type' => 'decrease'
                    ]);
                }

                return $order->load(['items.product', 'warehouse']);
            });
        } catch (\Throwable $e) {
            throw new BusinessException("Ошибка при создании заказа: " . $e->getMessage(), 500);
        }
    }

    /**
     * Обновить существующий заказ
     */
    public function updateOrder(Order $order, UpdateOrderDTO $dto): Order
    {
        try {
            if (!$order->exists) {
                throw new BusinessException("Заказ не найден");
            }

            if ($order->status !== Order::STATUS_ACTIVE) {
                throw new BusinessException("Можно обновлять только активные заказы");
            }

            $currentItems = $order->items()->with('product')->get();

            return DB::transaction(function () use ($order, $dto, $currentItems) {
                foreach ($currentItems as $item) {
                    Stock::where('warehouse_id', $order->warehouse_id)
                        ->where('product_id', $item->product_id)
                        ->increment('stock', $item->count);

                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'warehouse_id' => $order->warehouse_id,
                        'quantity' => $item->count,
                        'type' => 'increase'
                    ]);
                }

                $stocks = Stock::where('warehouse_id', $order->warehouse_id)
                    ->whereIn('product_id', array_column($dto->items, 'product_id'))
                    ->get()
                    ->keyBy('product_id');

                foreach ($dto->items as $item) {
                    if (!isset($stocks[$item['product_id']])) {
                        throw new BusinessException(
                            "Товар с ID {$item['product_id']} отсутствует на складе"
                        );
                    }

                    if ($stocks[$item['product_id']]->stock < $item['count']) {
                        throw new BusinessException(
                            "Недостаточно товара с ID {$item['product_id']} на складе. " .
                            "Доступно: {$stocks[$item['product_id']]->stock}, запрошено: {$item['count']}"
                        );
                    }
                }

                $order->update([
                    'customer' => $dto->customer
                ]);

                $order->items()->delete();

                foreach ($dto->items as $item) {
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'count' => $item['count']
                    ]);

                    Stock::where('warehouse_id', $order->warehouse_id)
                        ->where('product_id', $item['product_id'])
                        ->decrement('stock', $item['count']);

                    StockMovement::create([
                        'product_id' => $item['product_id'],
                        'warehouse_id' => $order->warehouse_id,
                        'quantity' => -$item['count'],
                        'type' => 'decrease'
                    ]);
                }

                return $order->load(['items.product', 'warehouse']);
            });
        } catch (\Throwable $e) {
            throw new BusinessException("Ошибка при обновлении заказа: " . $e->getMessage(), 500);
        }
    }

    /**
     * Завершить заказ
     */
    public function completeOrder(Order $order)
    {
        if (!$order->exists) {
            throw new BusinessException("Заказ не найден");
        }

        if ($order->status !== Order::STATUS_ACTIVE) {
            throw new BusinessException("Можно завершить только активный заказ");
        }

        $order->update([
            'status' => Order::STATUS_COMPLETED,
            'completed_at' => now()
        ]);
    }

    /**
     * Отменить заказ
     */
    public function cancelOrder(Order $order)
    {
        if (!$order->exists) {
            throw new BusinessException("Заказ не найден");
        }

        if ($order->status !== Order::STATUS_ACTIVE) {
            throw new BusinessException("Можно отменить только активный заказ");
        }

        // Restore stock
        foreach ($order->items as $item) {
            $this->updateStock($item->product_id, $order->warehouse_id, $item->count);
        }

        $order->update([
            'status' => Order::STATUS_CANCELED
        ]);
    }

    /**
     * Возобновить отмененный заказ
     */
    public function resumeOrder(Order $order)
    {
        if (!$order->exists) {
            throw new BusinessException("Заказ не найден");
        }

        if ($order->status !== Order::STATUS_CANCELED) {
            throw new BusinessException("Можно возобновить только отмененный заказ");
        }

        $this->validateStockAvailability(
            $order->items->toArray(),
            $order->warehouse_id
        );

        foreach ($order->items as $item) {
            $this->updateStock($item->product_id, $order->warehouse_id, -$item->count);
        }

        $order->update([
            'status' => Order::STATUS_ACTIVE
        ]);
    }

    /**
     * Проверка наличия на складе товаров для заказа
     */
    protected function validateStockAvailability(array $items, int $warehouseId)
    {
        foreach ($items as $item) {
            $stock = Stock::where('product_id', $item['product_id'])
                ->where('warehouse_id', $warehouseId)
                ->first();

            if (!$stock || $stock->stock < $item['count']) {
                throw new BusinessException("Недостаточный запас продукта ID {$item['product_id']}");
            }
        }
    }

    /**
     * Обновите уровень запасов и создать запись о перемещении
     */
    protected function updateStock(int $productId, int $warehouseId, int $quantity)
    {
        $stock = Stock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        // Обновляем stock напрямую через where условия
        Stock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->update([
                'stock' => $stock->stock + $quantity,
                'updated_at' => now()
            ]);

        StockMovement::create([
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'quantity' => $quantity,
            'type' => $quantity > 0 ? 'increase' : 'decrease'
        ]);
    }

    public function getOrders(OrderFilterDTO $dto)
    {
        $query = Order::query()
            ->with([
                'items.product',
                'warehouse'
            ]);

        if ($dto->status) {
            $query->where('status', $dto->status);
        }

        if ($dto->customer) {
            $query->where('customer', 'like', "%{$dto->customer}%");
        }

        if ($dto->dateFrom) {
            $query->where('created_at', '>=', $dto->dateFrom);
        }

        if ($dto->dateTo) {
            $query->where('created_at', '<=', $dto->dateTo);
        }

        if ($dto->warehouseId) {
            $query->where('warehouse_id', $dto->warehouseId);
        }

        return $query->orderBy($dto->sortBy, $dto->sortDirection)
            ->paginate($dto->perPage);
    }
}
