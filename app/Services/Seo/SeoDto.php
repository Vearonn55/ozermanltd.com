<?php

declare(strict_types=1);

namespace App\Services\Seo;

class SeoDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $canonicalUrl,
        public readonly string $robots,
        public readonly string $ogTitle,
        public readonly string $ogDescription,
        public readonly string $ogType,
        public readonly string $ogUrl,
        public readonly ?string $ogImage,
        public readonly array $hreflang,
        public readonly array $structuredData,
    ) {
    }
}
