<?php

declare(strict_types=1);

namespace App\Services\Migration;

use App\Services\Seo\SeoRepository;

class SeoImporter
{
    private LegacyScraper $scraper;
    private SeoRepository $repository;

    public function __construct(?LegacyScraper $scraper = null, ?SeoRepository $repository = null)
    {
        $this->scraper = $scraper ?? new LegacyScraper();
        $this->repository = $repository ?? new SeoRepository();
    }

    public function importFromSite(?string $baseUrl = null): array
    {
        $config = seo_config('legacy_import', []);
        $baseUrl = $baseUrl ?? ($config['base_url'] ?? 'http://localhost:8080');
        $locales = $config['locales'] ?? ['en'];
        $paths = $config['paths'] ?? [];

        $scraped = $this->scraper->scrapeSite($baseUrl, $locales, $paths);
        $records = [];
        $redirects = [];
        $report = ['imported' => 0, 'skipped' => 0, 'errors' => []];

        foreach ($scraped as $item) {
            if (!empty($item['error'])) {
                $report['errors'][] = $item['source_url'] . ': ' . $item['error'];
                $report['skipped']++;
                continue;
            }

            $entity = $this->mapPathToEntity($item['path'] ?? '');
            $key = $entity['entity_type'] . ':' . $entity['entity_key'] . ':' . $item['locale'];
            $records[$key] = [
                'meta_title' => $item['meta_title'] ?? '',
                'meta_description' => $item['meta_description'] ?? '',
                'og_title' => $item['og_title'] ?? '',
                'og_description' => $item['og_description'] ?? '',
                'og_image' => $item['og_image'] ?? null,
                'canonical_url' => $item['canonical_url'] ?? null,
                'robots' => $item['robots'] ?? 'index, follow',
                'structured_data' => $item['structured_data'] ?? [],
                'source_url' => $item['source_url'] ?? null,
            ];

            if (!empty($item['canonical_url']) && !empty($item['source_url']) && $item['canonical_url'] !== $item['source_url']) {
                $redirects[] = [
                    'from' => parse_url($item['source_url'], PHP_URL_PATH) ?: '',
                    'to' => parse_url($item['canonical_url'], PHP_URL_PATH) ?: '',
                    'type' => '301',
                ];
            }

            $report['imported']++;
        }

        $this->repository->replaceFileStore($records);
        $this->writeRedirects($redirects);
        $this->writeSqlExport($records, $redirects);

        return $report;
    }

    private function mapPathToEntity(string $path): array
    {
        $path = trim($path, '/');

        if ($path === '') {
            return ['entity_type' => 'page', 'entity_key' => 'home'];
        }

        $segments = explode('/', $path);

        return match ($segments[0]) {
            'sectors' => ['entity_type' => 'sector', 'entity_key' => $segments[1] ?? 'sectors'],
            'projects' => ['entity_type' => 'project', 'entity_key' => $segments[1] ?? 'projects'],
            'news' => ['entity_type' => 'news', 'entity_key' => $segments[1] ?? 'news'],
            default => ['entity_type' => 'page', 'entity_key' => $segments[0]],
        };
    }

    private function writeRedirects(array $redirects): void
    {
        $path = BASE_PATH . '/storage/seo/redirects.json';
        $dir = dirname($path);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents(
            $path,
            json_encode($redirects, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    private function writeSqlExport(array $records, array $redirects): void
    {
        $lines = ["-- Generated SEO import " . date('Y-m-d H:i:s'), 'USE ozermanltd;', ''];

        foreach ($records as $key => $record) {
            [$entityType, $entityKey, $locale] = explode(':', $key, 3);
            $languageId = match ($locale) {
                'tr' => 2,
                'ar' => 3,
                default => 1,
            };

            $entityId = '@entity_id';
            $lines[] = sprintf('-- %s', $key);
            $lines[] = sprintf(
                "INSERT INTO seo_meta (entity_type, entity_id, language_id, meta_title, meta_description, og_title, og_description, canonical_url, robots, structured_data)
VALUES ('%s', %s, %d, %s, %s, %s, %s, %s, %s, %s)
ON DUPLICATE KEY UPDATE meta_title = VALUES(meta_title), meta_description = VALUES(meta_description), og_title = VALUES(og_title), og_description = VALUES(og_description), canonical_url = VALUES(canonical_url), robots = VALUES(robots), structured_data = VALUES(structured_data);",
                addslashes($entityType),
                $this->entityIdExpression($entityType, $entityKey),
                $languageId,
                $this->sqlValue($record['meta_title'] ?? ''),
                $this->sqlValue($record['meta_description'] ?? ''),
                $this->sqlValue($record['og_title'] ?? ''),
                $this->sqlValue($record['og_description'] ?? ''),
                $this->sqlValue($record['canonical_url'] ?? null),
                $this->sqlValue($record['robots'] ?? 'index, follow'),
                $this->sqlValue(json_encode($record['structured_data'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            );
            $lines[] = '';
        }

        foreach ($redirects as $redirect) {
            if ($redirect['from'] === '' || $redirect['to'] === '') {
                continue;
            }

            $lines[] = sprintf(
                "INSERT INTO redirects (from_path, to_path, type) VALUES (%s, %s, '%s') ON DUPLICATE KEY UPDATE to_path = VALUES(to_path), type = VALUES(type);",
                $this->sqlValue($redirect['from']),
                $this->sqlValue($redirect['to']),
                addslashes($redirect['type'])
            );
        }

        $path = BASE_PATH . '/storage/seo/import.sql';
        file_put_contents($path, implode("\n", $lines) . "\n");
    }

    private function entityIdExpression(string $entityType, string $entityKey): string
    {
        if ($entityType === 'page') {
            return "(SELECT id FROM pages WHERE slug = " . $this->sqlValue($entityKey === 'home' ? 'home' : $entityKey) . " LIMIT 1)";
        }

        return '1';
    }

    private function sqlValue(?string $value): string
    {
        if ($value === null || $value === '') {
            return 'NULL';
        }

        return "'" . addslashes($value) . "'";
    }
}
