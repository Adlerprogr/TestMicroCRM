<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\DTOs\Products\ProductFilterDTO;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $filterDTO = ProductFilterDTO::fromRequest($request);
        $products = $this->productService->getProducts($filterDTO);
        return response()->json($products);
    }
}
