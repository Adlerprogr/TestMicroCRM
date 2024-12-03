<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class SitemapTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('app/public');
    }

    public function test_can_generate_sitemaps(): void
    {
        $response = $this->get('/test-sitemap');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        Storage::disk('public')->assertExists('sitemap.xml');
        Storage::disk('public')->assertExists('sitemap.csv');
        Storage::disk('public')->assertExists('sitemap.json');

        // Проверяем содержимое XML файла
        $xmlContent = Storage::disk('public')->get('sitemap.xml');
        $this->assertStringContainsString('<loc>https://example.com</loc>', $xmlContent);
        $this->assertStringContainsString('<priority>1</priority>', $xmlContent);

        // Проверяем содержимое CSV файла
        $csvContent = Storage::disk('public')->get('sitemap.csv');
        $this->assertStringContainsString('https://example.com;2024-03-12;1;daily', $csvContent);

        // Проверяем содержимое JSON файла
        $jsonContent = Storage::disk('public')->get('sitemap.json');
        $jsonData = json_decode($jsonContent, true);
        $this->assertIsArray($jsonData);
        $this->assertCount(2, $jsonData);
    }
}
