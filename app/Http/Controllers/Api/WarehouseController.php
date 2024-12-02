<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Получить постраничный список складов
     *
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $warehouses = Warehouse::query()
            ->when($request->has('search'), function($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->orderBy('name')
            ->paginate($perPage);

        return response()->json($warehouses);
    }
}
