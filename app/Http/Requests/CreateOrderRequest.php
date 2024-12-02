<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer' => 'required|string|max:255',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.count' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'customer.required' => 'Не указано имя клиента',
            'customer.string' => 'Имя клиента должно быть строкой',
            'customer.max' => 'Имя клиента не должно превышать 255 символов',

            'warehouse_id.required' => 'Не указан ID склада',
            'warehouse_id.integer' => 'ID склада должен быть целым числом',
            'warehouse_id.exists' => 'Склад с ID :input не существует',

            'items.required' => 'Не указан список товаров',
            'items.array' => 'Список товаров должен быть массивом',
            'items.min' => 'В заказе должен быть хотя бы один товар',

            'items.*.product_id.required' => 'Не указан ID товара',
            'items.*.product_id.integer' => 'ID товара должен быть целым числом',
            'items.*.product_id.exists' => 'Товар с ID :input не существует',

            'items.*.count.required' => 'Не указано количество товара',
            'items.*.count.integer' => 'Количество должно быть целым числом',
            'items.*.count.min' => 'Количество должно быть больше 0'
        ];
    }
}
