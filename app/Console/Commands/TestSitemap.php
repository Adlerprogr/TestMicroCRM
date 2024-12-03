<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Adlerprogr\GeneratingSiteMap\SitemapGenerator;

class TestSitemap extends Command
{
    protected $signature = 'sitemap:test';
    protected $description = 'Test sitemap generator package';

    public function handle(): void
    {
        $this->info('Testing sitemap generator...');

        try {
            $pages = [
                [
                    'loc' => 'https://example.com',
                    'lastmod' => date('Y-m-d'),
                    'priority' => 1,
                    'changefreq' => 'daily'
                ]
            ];

            foreach (['xml', 'csv', 'json'] as $format) {
                $filePath = storage_path("app/public/sitemap.{$format}");

                $generator = new SitemapGenerator($pages, $format, $filePath);
                $generator->generate();

                $this->info("Generated {$format} sitemap: {$filePath}");
            }

            $this->info('Все карты сайта созданы успешно!');

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
