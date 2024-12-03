<?php

namespace App\Http\Controllers;

use Adlerprogr\GeneratingSiteMap\SitemapGenerator;
use Illuminate\Http\JsonResponse;

class TestSitemapController extends Controller
{
    public function test(): JsonResponse
    {
        try {
            $pages = [
                [
                    'loc' => 'https://example.com',
                    'lastmod' => '2024-03-12',
                    'priority' => 1,
                    'changefreq' => 'daily'
                ],
                [
                    'loc' => 'https://example.com/about',
                    'lastmod' => '2024-03-12',
                    'priority' => 0.8,
                    'changefreq' => 'weekly'
                ]
            ];

            // Тестируем XML формат
            $xmlGenerator = new SitemapGenerator(
                pages: $pages,
                format: 'xml',
                filePath: storage_path('app/public/sitemap.xml')
            );
            $xmlGenerator->generate();

            // Тестируем CSV формат
            $csvGenerator = new SitemapGenerator(
                pages: $pages,
                format: 'csv',
                filePath: storage_path('app/public/sitemap.csv')
            );
            $csvGenerator->generate();

            // Тестируем JSON формат
            $jsonGenerator = new SitemapGenerator(
                pages: $pages,
                format: 'json',
                filePath: storage_path('app/public/sitemap.json')
            );
            $jsonGenerator->generate();

            return response()->json([
                'success' => true,
                'message' => 'All sitemaps generated successfully',
                'files' => [
                    'xml' => asset('storage/sitemap.xml'),
                    'csv' => asset('storage/sitemap.csv'),
                    'json' => asset('storage/sitemap.json')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
