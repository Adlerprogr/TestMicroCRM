<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\DTOs\StockMovements\StockMovementFilterDTO;
use App\Services\StockMovementService;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    protected $stockMovementService;

    public function __construct(StockMovementService $stockMovementService)
    {
        $this->stockMovementService = $stockMovementService;
    }

    public function index(Request $request)
    {
        $filterDTO = StockMovementFilterDTO::fromRequest($request);
        $movements = $this->stockMovementService->getMovements($filterDTO);
        return response()->json($movements);
    }
}
