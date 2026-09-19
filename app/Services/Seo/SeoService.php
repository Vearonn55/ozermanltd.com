<?php

declare(strict_types=1);

namespace App\Services\Seo;

class SeoService
{
    private SeoRepository $repository;

    public function __construct(?SeoRepository $repository = null)
    {
        $this->repository = $repository ?? new SeoRepository();
    }

    public function build(array $context): SeoDto
    {
        $locale = $context['locale'] ?? app_locale();
        $entityType = $context['entity_type'] ?? 'page';
        $entityKey = $context['entity_key'] ?? 'home';
        $path = $context['path'] ?? '';
        $stored = $this->repository->find($entityType, $entityKey, $locale) ?? [];

        $title = $stored['meta_title']
            ?? $stored['og_title']
            ?? $this->localized($context['title'] ?? [], $locale);
        $description = $stored['meta_description']
            ?? $stored['og_description']
            ?? $this->localized($context['description'] ?? [], $locale);

        $title = $this->normalizeTitle($title, $context['append_suffix'] ?? true);
        $description = trim($description);

        $canonicalPath = $stored['canonical_path'] ?? $path;
        $canonicalUrl = $stored['canonical_url'] ?? $this->absoluteUrl($canonicalPath, $locale);
        $robots = $stored['robots'] ?? $context['robots'] ?? seo_config('default_robots', 'index, follow');
        $ogType = $context['og_type'] ?? 'website';
        $ogImage = $stored['og_image'] ?? $context['og_image'] ?? seo_config('default_og_image');

        if ($ogImage && !str_starts_with($ogImage, 'http')) {
            $ogImage = rtrim(config('url'), '/') . '/' . ltrim($ogImage, '/');
        }

        $structuredData = $this->buildStructuredData($context, $stored, $locale, $title, $description, $canonicalUrl);

        return new SeoDto(
            title: $title,
            description: $description,
            canonicalUrl: $canonicalUrl,
            robots: $robots,
            ogTitle: $stored['og_title'] ?? $title,
            ogDescription: $stored['og_description'] ?? $description,
            ogType: $ogType,
            ogUrl: $canonicalUrl,
            ogImage: $ogImage,
            hreflang: $this->buildHreflang($path, $locale),
            structuredData: $structuredData,
        );
    }

    private function buildStructuredData(
        array $context,
        array $stored,
        string $locale,
        string $title,
        string $description,
        string $canonicalUrl
    ): array {
        if (!empty($stored['structured_data']) && is_array($stored['structured_data'])) {
            return $stored['structured_data'];
        }

        if (!empty($context['structured_data'])) {
            return $context['structured_data'];
        }

        $schemaType = $context['schema_type'] ?? 'WebPage';
        $graph = [
            '@context' => 'https://schema.org',
            '@type' => $schemaType,
            'name' => $title,
            'description' => $description,
            'url' => $canonicalUrl,
            'inLanguage' => $locale,
        ];

        if ($schemaType === 'Article') {
            $graph['headline'] = $this->localized($context['headline'] ?? $context['title'] ?? [], $locale);
            $graph['datePublished'] = $context['published_at'] ?? null;
            $graph['author'] = [
                '@type' => 'Organization',
                'name' => config('name'),
            ];
        }

        if (!empty($context['breadcrumbs'])) {
            return [
                '@context' => 'https://schema.org',
                '@graph' => [
                    $graph,
                    $this->breadcrumbSchema($context['breadcrumbs'], $locale),
                ],
            ];
        }

        if (($context['include_organization'] ?? true) && ($context['schema_type'] ?? 'WebPage') === 'WebPage') {
            return [
                '@context' => 'https://schema.org',
                '@graph' => [
                    seo_config('organization_schema'),
                    $graph,
                ],
            ];
        }

        return $graph;
    }

    private function breadcrumbSchema(array $breadcrumbs, string $locale): array
    {
        $items = [];
        $position = 1;

        foreach ($breadcrumbs as $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $this->localized($crumb['label'], $locale),
                'item' => $this->absoluteUrl($crumb['path'] ?? '', $locale),
            ];
        }

        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    private function buildHreflang(string $path, string $currentLocale): array
    {
        $alternates = [];

        foreach (config('locales', []) as $locale => $meta) {
            $alternates[] = [
                'locale' => $locale,
                'url' => $this->absoluteUrl($path, $locale),
                'current' => $locale === $currentLocale,
            ];
        }

        $alternates[] = [
            'locale' => 'x-default',
            'url' => $this->absoluteUrl($path, config('default_locale', 'en')),
            'current' => false,
        ];

        return $alternates;
    }

    private function absoluteUrl(string $path, string $locale): string
    {
        return rtrim(config('url'), '/') . url($path, $locale);
    }

    private function localized(array|string $value, string $locale): string
    {
        if (is_string($value)) {
            return $value;
        }

        return $value[$locale] ?? $value['en'] ?? '';
    }

    private function normalizeTitle(string $title, bool $appendSuffix): string
    {
        $title = trim($title);
        $suffix = seo_config('title_suffix', ' | Ozerman Ltd');

        if ($appendSuffix && $suffix && !str_contains($title, config('name'))) {
            return $title . $suffix;
        }

        return $title;
    }
}
