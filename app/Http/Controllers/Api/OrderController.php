<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Orders\CreateOrderDTO;
use App\DTOs\Orders\OrderFilterDTO;
use App\DTOs\Orders\UpdateOrderDTO;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Получить отфильтрованный список заказов с разбивкой на страницы и быстрой загрузкой
     */
    public function index(Request $request)
    {
        $filterDTO = OrderFilterDTO::fromRequest($request);
        return $this->orderService->getOrders($filterDTO);
    }

    /**
     * Создать новый заказ с подтверждением
     */
    public function store(CreateOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $orderDTO = CreateOrderDTO::fromRequest($request);
            $order = $this->orderService->createOrder($orderDTO);
            DB::commit();
            return response()->json($order, 201);
        } catch (BusinessException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Обновить заказ с помощью проверки
     */
    public function update(UpdateOrderRequest $request, $orderId)
    {
        DB::beginTransaction();

        try {
            $order = Order::findOrFail($orderId);

            $orderDTO = UpdateOrderDTO::fromRequest($request);
            $order = $this->orderService->updateOrder($order, $orderDTO);

            DB::commit();
            return response()->json($order);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['error' => 'Заказ не найден'], 404);
        } catch (BusinessException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Завершить заказ
     */
    public function complete(Order $order)
    {
        DB::beginTransaction();
        try {
            $this->orderService->completeOrder($order);

            DB::commit();
            return response()->json($order);
        } catch (BusinessException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Отменить заказ
     */
    public function cancel(Order $order)
    {
        DB::beginTransaction();
        try {
            $this->orderService->cancelOrder($order);

            DB::commit();
            return response()->json($order);
        } catch (BusinessException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Возобновить отмененный заказ
     */
    public function resume(Order $order)
    {
        DB::beginTransaction();
        try {
            $this->orderService->resumeOrder($order);

            DB::commit();
            return response()->json($order);
        } catch (BusinessException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
