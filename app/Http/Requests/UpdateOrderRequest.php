<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.count' => 'required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'customer.required' => 'Не указано имя клиента',
            'customer.max' => 'Имя клиента не должно превышать 255 символов',
            'items.required' => 'Список товаров не может быть пустым',
            'items.array' => 'Некорректный формат списка товаров',
            'items.min' => 'Заказ должен содержать хотя бы один товар',
            'items.*.product_id.required' => 'Не указан ID товара',
            'items.*.product_id.exists' => 'Товар с указанным ID не существует',
            'items.*.count.required' => 'Не указано количество товара',
            'items.*.count.integer' => 'Количество товара должно быть целым числом',
            'items.*.count.min' => 'Количество товара должно быть больше 0'
        ];
    }
}
