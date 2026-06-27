<?php

declare(strict_types=1);

namespace App\Services\Seo;

class SitemapService
{
    public function urls(): array
    {
        $dbUrls = $this->urlsFromDatabase();
        if ($dbUrls !== []) {
            return $dbUrls;
        }

        $content = content();
        $locale = app_locale();
        $urls = [];

        foreach ($this->staticPaths() as $path) {
            foreach (config('locales', []) as $lang => $_meta) {
                $urls[] = $this->entry($path, $lang, 'weekly', $path === '' ? '1.0' : '0.8');
            }
        }

        foreach ($content->sectors($locale) as $sector) {
            foreach (config('locales', []) as $lang => $_meta) {
                $urls[] = $this->entry('sectors/' . $sector['slug'], $lang, 'monthly', '0.7');
            }
        }

        foreach ($content->projects($locale) as $project) {
            foreach (config('locales', []) as $lang => $_meta) {
                $urls[] = $this->entry('projects/' . $project['slug'], $lang, 'monthly', '0.7');
            }
        }

        foreach ($content->news($locale) as $article) {
            foreach (config('locales', []) as $lang => $_meta) {
                $urls[] = $this->entry(
                    'news/' . $article['slug'],
                    $lang,
                    'weekly',
                    '0.6',
                    $article['published_at'] ?? null
                );
            }
        }

        return $urls;
    }

    public function toXml(): string
    {
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];

        foreach ($this->urls() as $url) {
            $lines[] = '  <url>';
            $lines[] = '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1) . '</loc>';
            if (!empty($url['lastmod'])) {
                $lines[] = '    <lastmod>' . htmlspecialchars($url['lastmod'], ENT_XML1) . '</lastmod>';
            }
            $lines[] = '    <changefreq>' . htmlspecialchars($url['changefreq'], ENT_XML1) . '</changefreq>';
            $lines[] = '    <priority>' . htmlspecialchars($url['priority'], ENT_XML1) . '</priority>';
            $lines[] = '  </url>';
        }

        $lines[] = '</urlset>';

        return implode("\n", $lines) . "\n";
    }

    private function staticPaths(): array
    {
        return ['', 'about-us', 'sectors', 'projects', 'news', 'gallery', 'contact', 'privacy-policy', 'cookie-policy', 'cookie-settings'];
    }

    private function entry(string $path, string $locale, string $changefreq, string $priority, ?string $lastmod = null): array
    {
        return [
            'loc' => rtrim(config('url'), '/') . url($path, $locale),
            'changefreq' => $changefreq,
            'priority' => $priority,
            'lastmod' => $lastmod ? date('Y-m-d', strtotime($lastmod)) : date('Y-m-d'),
        ];
    }

    private function urlsFromDatabase(): array
    {
        $pdo = \App\Infrastructure\Database::connection();
        if ($pdo === null) {
            return [];
        }

        $stmt = $pdo->query('SELECT url, changefreq, priority, lastmod FROM sitemaps WHERE is_active = 1 ORDER BY priority DESC');
        $rows = $stmt ? $stmt->fetchAll() : [];

        if ($rows === []) {
            return [];
        }

        return array_map(static function (array $row): array {
            return [
                'loc' => $row['url'],
                'changefreq' => $row['changefreq'] ?? 'weekly',
                'priority' => (string) ($row['priority'] ?? '0.5'),
                'lastmod' => !empty($row['lastmod']) ? date('Y-m-d', strtotime((string) $row['lastmod'])) : date('Y-m-d'),
            ];
        }, $rows);
    }
}
