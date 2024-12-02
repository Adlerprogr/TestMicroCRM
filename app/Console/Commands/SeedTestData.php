<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Stock;

class SeedTestData extends Command
{
    protected $signature = 'seed:test-data';
    protected $description = 'Seed test data for products, warehouses and stocks';

    public function handle()
    {
        $this->info('Started seeding test data...');

        // Create test warehouses
        $warehouses = [
            ['name' => 'Main Warehouse'],
            ['name' => 'Secondary Warehouse'],
            ['name' => 'Express Warehouse']
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
        $this->info('Warehouses created successfully');

        // Create test products with random prices
        $products = [
            ['name' => 'Laptop', 'price' => rand(800, 1500)],
            ['name' => 'Smartphone', 'price' => rand(500, 1000)],
            ['name' => 'Tablet', 'price' => rand(300, 700)],
            ['name' => 'Monitor', 'price' => rand(200, 500)],
            ['name' => 'Keyboard', 'price' => rand(50, 150)],
            ['name' => 'Mouse', 'price' => rand(20, 80)],
            ['name' => 'Headphones', 'price' => rand(100, 300)],
            ['name' => 'Printer', 'price' => rand(150, 400)],
            ['name' => 'Router', 'price' => rand(80, 200)],
            ['name' => 'External HDD', 'price' => rand(100, 250)]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
        $this->info('Products created successfully');

        // Create random stock levels for each product in each warehouse
        $warehouses = Warehouse::all();
        $products = Product::all();

        foreach ($warehouses as $warehouse) {
            foreach ($products as $product) {
                Stock::create([
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                    'stock' => rand(5, 100)
                ]);
            }
        }
        $this->info('Stocks created successfully');

        $this->info('Test data seeding completed!');
    }
}
