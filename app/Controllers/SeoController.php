<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\Seo\SitemapService;

class SeoController
{
    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=UTF-8');
        echo (new SitemapService())->toXml();
    }
}
