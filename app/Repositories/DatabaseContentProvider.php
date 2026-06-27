<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Data\DummyData;
use App\Infrastructure\Database;
use PDO;

class DatabaseContentProvider implements ContentProviderInterface
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $pdo = $pdo ?? Database::connection(force: true);
        if ($pdo === null) {
            throw new \RuntimeException('Database connection is not available.');
        }
        $this->pdo = $pdo;
    }

    public function nav(string $locale): array
    {
        $stmt = $this->pdo->query(
            'SELECT mi.id, mi.url, mi.sort_order, l.code AS lang, mit.label
             FROM menu_items mi
             INNER JOIN menus m ON m.id = mi.menu_id AND m.location = \'header\'
             INNER JOIN menu_item_translations mit ON mit.item_id = mi.id
             INNER JOIN languages l ON l.id = mit.language_id
             WHERE mi.is_active = 1
             ORDER BY mi.sort_order, l.code'
        );

        $grouped = [];
        foreach ($stmt->fetchAll() as $row) {
            $id = (int) $row['id'];
            $grouped[$id]['url'] = $this->menuPath((string) $row['url'], $locale);
            $grouped[$id]['label'][$row['lang']] = $row['label'];
        }

        $dummyNav = DummyData::nav();
        $nav = [];
        $i = 0;
        foreach ($grouped as $item) {
            $nav[] = [
                'label' => $this->mergeTranslations($item['label'], $dummyNav[$i]['label'] ?? []),
                'url' => $item['url'],
            ];
            $i++;
        }

        return $nav !== [] ? $nav : DummyData::nav();
    }

    public function heroSlides(string $locale): array
    {
        $languageId = $this->languageId($locale);
        $stmt = $this->pdo->prepare(
            'SELECT hs.title, hs.subtitle, hs.cta_text, hs.cta_url, hs.sort_order, m.file_path AS image
             FROM hero_slides hs
             INNER JOIN pages p ON p.id = hs.page_id AND p.slug = \'home\'
             LEFT JOIN media m ON m.id = hs.media_id
             WHERE hs.language_id = :language_id AND hs.is_active = 1
             ORDER BY hs.sort_order'
        );
        $stmt->execute(['language_id' => $languageId]);
        $rows = $stmt->fetchAll();

        if ($rows === []) {
            return DummyData::heroSlides();
        }

        $dummySlides = DummyData::heroSlides();
        $slides = [];
        foreach ($rows as $index => $row) {
            $dummy = $dummySlides[$index] ?? [];
            $slides[] = [
                'title' => $this->mergeScalarTranslation($row['title'], $dummy['title'] ?? [], $locale),
                'subtitle' => $this->mergeScalarTranslation($row['subtitle'], $dummy['subtitle'] ?? [], $locale),
                'cta_text' => $this->mergeScalarTranslation($row['cta_text'], $dummy['cta_text'] ?? [], $locale),
                'cta_url' => $this->normalizeInternalPath($row['cta_url'] ?? ''),
                'image' => $row['image'] ?: ($dummy['image'] ?? ''),
            ];
        }

        return $slides;
    }

    public function stats(string $locale): array
    {
        $languageId = $this->languageId($locale);
        $stmt = $this->pdo->prepare(
            'SELECT sc.value, sc.label, sc.sort_order, l.code AS lang
             FROM stat_counters sc
             INNER JOIN pages p ON p.id = sc.page_id AND p.slug = \'home\'
             INNER JOIN languages l ON l.id = sc.language_id
             WHERE sc.language_id = :language_id OR l.code = \'en\'
             ORDER BY sc.sort_order, l.code'
        );
        $stmt->execute(['language_id' => $languageId]);
        $rows = $stmt->fetchAll();

        if ($rows === []) {
            return DummyData::stats();
        }

        $grouped = [];
        foreach ($rows as $row) {
            $sort = (int) $row['sort_order'];
            $grouped[$sort]['value'] = $row['value'];
            $grouped[$sort]['label'][$row['lang']] = $row['label'];
        }

        ksort($grouped);
        $dummyStats = DummyData::stats();
        $stats = [];
        $i = 0;
        foreach ($grouped as $item) {
            $stats[] = [
                'value' => $item['value'],
                'label' => $this->mergeTranslations($item['label'], $dummyStats[$i]['label'] ?? []),
            ];
            $i++;
        }

        return $stats;
    }

    public function sectors(string $locale): array
    {
        return array_values(array_map(
            fn(array $sector): array => $this->formatSector($sector, $locale),
            $this->loadSectors()
        ));
    }

    public function sectorBySlug(string $slug, string $locale): ?array
    {
        foreach ($this->loadSectors() as $sector) {
            if (($sector['slug'] ?? '') === $slug) {
                return $this->formatSector($sector, $locale);
            }
        }

        return null;
    }

    public function projects(string $locale): array
    {
        return array_values(array_map(
            fn(array $project): array => $this->formatProject($project, $locale),
            $this->loadProjects()
        ));
    }

    public function projectBySlug(string $slug, string $locale): ?array
    {
        foreach ($this->loadProjects() as $project) {
            if (($project['slug'] ?? '') === $slug) {
                return $this->formatProject($project, $locale);
            }
        }

        return null;
    }

    public function news(string $locale): array
    {
        return array_values(array_map(
            fn(array $article): array => $this->formatNews($article, $locale),
            $this->loadNews()
        ));
    }

    public function newsBySlug(string $slug, string $locale): ?array
    {
        foreach ($this->loadNews() as $article) {
            if (($article['slug'] ?? '') === $slug) {
                return $this->formatNews($article, $locale);
            }
        }

        return null;
    }

    public function gallery(string $locale): array
    {
        $stmt = $this->pdo->query(
            'SELECT gc.id, gct.slug, gct.title, gct.description, l.code AS lang,
                    cover.file_path AS cover
             FROM gallery_collections gc
             INNER JOIN gallery_collection_translations gct ON gct.collection_id = gc.id
             INNER JOIN languages l ON l.id = gct.language_id
             LEFT JOIN media cover ON cover.id = gc.cover_image_id
             WHERE gc.is_active = 1
             ORDER BY gc.sort_order, l.code'
        );

        $collections = [];
        foreach ($stmt->fetchAll() as $row) {
            $id = (int) $row['id'];
            $collections[$id]['slug'] = $row['slug'];
            $collections[$id]['cover'] = $row['cover'];
            $collections[$id]['title'][$row['lang']] = $row['title'];
            $collections[$id]['description'][$row['lang']] = $row['description'];
        }

        if ($collections === []) {
            return DummyData::gallery();
        }

        $dummyGallery = DummyData::gallery();
        $result = [];
        $i = 0;
        foreach ($collections as $id => $collection) {
            $dummy = DummyData::findBySlug($dummyGallery, $collection['slug']) ?? ($dummyGallery[$i] ?? []);
            $itemsStmt = $this->pdo->prepare(
                'SELECT gi.caption, m.file_path AS image
                 FROM gallery_items gi
                 INNER JOIN media m ON m.id = gi.media_id
                 WHERE gi.collection_id = :collection_id
                 ORDER BY gi.sort_order'
            );
            $itemsStmt->execute(['collection_id' => $id]);
            $items = [];
            foreach ($itemsStmt->fetchAll() as $itemIndex => $item) {
                $dummyItem = $dummy['items'][$itemIndex] ?? [];
                $items[] = [
                    'image' => $item['image'] ?: ($dummyItem['image'] ?? ''),
                    'caption' => $this->mergeScalarTranslation(
                        $item['caption'],
                        $dummyItem['caption'] ?? [],
                        $locale
                    ),
                ];
            }

            $result[] = [
                'slug' => $collection['slug'],
                'title' => $this->mergeTranslations($collection['title'], $dummy['title'] ?? []),
                'description' => $this->mergeTranslations($collection['description'], $dummy['description'] ?? []),
                'cover' => $collection['cover'] ?: ($dummy['cover'] ?? ''),
                'items' => $items !== [] ? $items : ($dummy['items'] ?? []),
            ];
            $i++;
        }

        return $result;
    }

    public function offices(string $locale): array
    {
        $stmt = $this->pdo->query(
            'SELECT o.id, o.phone, o.email, o.is_headquarters, ot.label, ot.address, ot.city, ot.country, ot.working_hours, l.code AS lang
             FROM offices o
             INNER JOIN office_translations ot ON ot.office_id = o.id
             INNER JOIN languages l ON l.id = ot.language_id
             WHERE o.is_active = 1
             ORDER BY o.is_headquarters DESC, o.id, l.code'
        );

        $offices = [];
        foreach ($stmt->fetchAll() as $row) {
            $id = (int) $row['id'];
            $offices[$id]['phone'] = $row['phone'];
            $offices[$id]['email'] = $row['email'];
            $offices[$id]['city'] = $row['city'];
            $offices[$id]['is_headquarters'] = (bool) $row['is_headquarters'];
            $offices[$id]['label'][$row['lang']] = $row['label'];
            $offices[$id]['address'][$row['lang']] = $row['address'];
            $offices[$id]['country'][$row['lang']] = $row['country'];
            $offices[$id]['hours'][$row['lang']] = $row['working_hours'];
        }

        if ($offices === []) {
            return DummyData::offices();
        }

        $dummyOffices = DummyData::offices();
        $result = [];
        $i = 0;
        foreach ($offices as $office) {
            $dummy = $dummyOffices[$i] ?? [];
            $result[] = [
                'label' => $this->mergeTranslations($office['label'], $dummy['label'] ?? []),
                'address' => $this->mergeTranslations($office['address'], $dummy['address'] ?? []),
                'city' => $office['city'] ?: ($dummy['city'] ?? ''),
                'country' => $this->mergeTranslations($office['country'], $dummy['country'] ?? []),
                'phone' => $office['phone'] ?: ($dummy['phone'] ?? ''),
                'email' => $office['email'] ?: ($dummy['email'] ?? ''),
                'hours' => $this->mergeTranslations($office['hours'], $dummy['hours'] ?? []),
                'is_headquarters' => $office['is_headquarters'],
            ];
            $i++;
        }

        return $result;
    }

    public function team(string $locale): array
    {
        $languageId = $this->languageId($locale);
        $stmt = $this->pdo->prepare(
            'SELECT tm.type, tm.sort_order, tmt.full_name, tmt.position, tmt.bio, m.file_path AS image
             FROM team_members tm
             INNER JOIN team_member_translations tmt ON tmt.member_id = tm.id
             LEFT JOIN media m ON m.id = tm.media_id
             WHERE tm.is_active = 1 AND tmt.language_id = :language_id
             ORDER BY tm.sort_order'
        );
        $stmt->execute(['language_id' => $languageId]);
        $rows = $stmt->fetchAll();

        if ($rows === []) {
            return DummyData::team();
        }

        $dummyTeam = DummyData::team();
        $team = [];
        foreach ($rows as $index => $row) {
            $dummy = $dummyTeam[$index] ?? [];
            $team[] = [
                'name' => $this->mergeScalarTranslation($row['full_name'], $dummy['name'] ?? [], $locale),
                'position' => $this->mergeScalarTranslation($row['position'], $dummy['position'] ?? [], $locale),
                'bio' => $this->mergeScalarTranslation($row['bio'], $dummy['bio'] ?? [], $locale),
                'type' => $row['type'],
                'image' => $row['image'] ?: ($dummy['image'] ?? ''),
            ];
        }

        return $team;
    }

    public function aboutContent(string $locale): array
    {
        $languageId = $this->languageId($locale);
        $stmt = $this->pdo->prepare(
            'SELECT pt.content
             FROM page_translations pt
             INNER JOIN pages p ON p.id = pt.page_id AND p.slug = \'about-us\'
             WHERE pt.language_id = :language_id
             LIMIT 1'
        );
        $stmt->execute(['language_id' => $languageId]);
        $row = $stmt->fetch();

        if (!$row || empty($row['content'])) {
            return DummyData::aboutContent();
        }

        $raw = trim((string) $row['content']);
        $decoded = json_decode($raw, true);

        if (!is_array($decoded)) {
            return [
                'history' => ['en' => $raw, 'tr' => $raw, 'ar' => $raw],
                'vision' => ['en' => '', 'tr' => '', 'ar' => ''],
                'mission' => ['en' => '', 'tr' => '', 'ar' => ''],
            ];
        }

        $dummy = DummyData::aboutContent();
        return [
            'history' => $this->mergeTranslations(
                is_array($decoded['history'] ?? null) ? $decoded['history'] : ['en' => $decoded['history'] ?? ''],
                $dummy['history']
            ),
            'vision' => $this->mergeTranslations(
                is_array($decoded['vision'] ?? null) ? $decoded['vision'] : ['en' => $decoded['vision'] ?? ''],
                $dummy['vision']
            ),
            'mission' => $this->mergeTranslations(
                is_array($decoded['mission'] ?? null) ? $decoded['mission'] : ['en' => $decoded['mission'] ?? ''],
                $dummy['mission']
            ),
        ];
    }

    public function values(string $locale): array
    {
        return DummyData::values();
    }

    private function loadSectors(): array
    {
        $stmt = $this->pdo->query(
            'SELECT s.id, s.icon, s.color, s.sort_order, st.slug, st.name, st.overview, st.services_text, l.code AS lang
             FROM sectors s
             INNER JOIN sector_translations st ON st.sector_id = s.id
             INNER JOIN languages l ON l.id = st.language_id
             WHERE s.is_active = 1
             ORDER BY s.sort_order, l.code'
        );

        return $this->groupTranslatedEntities($stmt->fetchAll(), function (array $entity): array {
            return [
                'slug' => $entity['fields']['slug']['en'] ?? array_values($entity['fields']['slug'])[0] ?? '',
                'icon' => $entity['meta']['icon'],
                'color' => $entity['meta']['color'],
                'name' => $entity['fields']['name'],
                'overview' => $entity['fields']['overview'],
                'services' => $entity['fields']['services_text'],
                'image' => $entity['meta']['image'],
            ];
        }, [
            'slug' => 'slug',
            'name' => 'name',
            'overview' => 'overview',
            'services_text' => 'services_text',
        ]);
    }

    private function loadProjects(): array
    {
        $stmt = $this->pdo->query(
            'SELECT p.id, p.status, p.location, p.delivery_date, p.start_price, p.currency, p.is_featured,
                    pt.slug, pt.title, pt.description, pt.features, l.code AS lang,
                    pct.name AS category_name, m.file_path AS image
             FROM projects p
             INNER JOIN project_translations pt ON pt.project_id = p.id
             INNER JOIN languages l ON l.id = pt.language_id
             LEFT JOIN project_categories pc ON pc.id = p.category_id
             LEFT JOIN project_category_translations pct ON pct.category_id = pc.id AND pct.language_id = pt.language_id
             LEFT JOIN media m ON m.id = p.featured_image_id
             WHERE p.is_active = 1
             ORDER BY p.is_featured DESC, p.id, l.code'
        );

        return $this->groupTranslatedEntities($stmt->fetchAll(), function (array $entity): array {
            return [
                'slug' => $entity['fields']['slug']['en'] ?? array_values($entity['fields']['slug'])[0] ?? '',
                'category' => $entity['fields']['category_name'],
                'status' => $entity['meta']['status'],
                'location' => $entity['meta']['location'],
                'delivery_date' => $entity['meta']['delivery_date'],
                'start_price' => $entity['meta']['start_price'] !== null ? (float) $entity['meta']['start_price'] : null,
                'currency' => $entity['meta']['currency'],
                'is_featured' => (bool) $entity['meta']['is_featured'],
                'title' => $entity['fields']['title'],
                'description' => $entity['fields']['description'],
                'features' => $entity['fields']['features'],
                'image' => $entity['meta']['image'],
            ];
        }, [
            'slug' => 'slug',
            'title' => 'title',
            'description' => 'description',
            'features' => 'features',
            'category_name' => 'category_name',
        ], [
            'status' => 'status',
            'location' => 'location',
            'delivery_date' => 'delivery_date',
            'start_price' => 'start_price',
            'currency' => 'currency',
            'is_featured' => 'is_featured',
            'image' => 'image',
        ]);
    }

    private function loadNews(): array
    {
        $stmt = $this->pdo->query(
            'SELECT n.id, n.publish_date, n.is_featured, nt.slug, nt.title, nt.excerpt, nt.content, l.code AS lang,
                    nct.name AS category_name, m.file_path AS image
             FROM news n
             INNER JOIN news_translations nt ON nt.news_id = n.id
             INNER JOIN languages l ON l.id = nt.language_id
             LEFT JOIN news_categories nc ON nc.id = n.category_id
             LEFT JOIN news_category_translations nct ON nct.category_id = nc.id AND nct.language_id = nt.language_id
             LEFT JOIN media m ON m.id = n.featured_image_id
             WHERE n.status = \'published\'
             ORDER BY n.publish_date DESC, l.code'
        );

        return $this->groupTranslatedEntities($stmt->fetchAll(), function (array $entity): array {
            return [
                'slug' => $entity['fields']['slug']['en'] ?? array_values($entity['fields']['slug'])[0] ?? '',
                'category' => $entity['fields']['category_name'],
                'publish_date' => $entity['meta']['publish_date'] ? date('Y-m-d', strtotime((string) $entity['meta']['publish_date'])) : null,
                'is_featured' => (bool) $entity['meta']['is_featured'],
                'title' => $entity['fields']['title'],
                'excerpt' => $entity['fields']['excerpt'],
                'content' => $entity['fields']['content'],
                'image' => $entity['meta']['image'],
            ];
        }, [
            'slug' => 'slug',
            'title' => 'title',
            'excerpt' => 'excerpt',
            'content' => 'content',
            'category_name' => 'category_name',
        ], [
            'publish_date' => 'publish_date',
            'is_featured' => 'is_featured',
            'image' => 'image',
        ]);
    }

    private function groupTranslatedEntities(array $rows, callable $formatter, array $fieldMap, array $metaMap = []): array
    {
        $entities = [];

        foreach ($rows as $row) {
            $id = (int) $row['id'];
            if (!isset($entities[$id])) {
                $entities[$id] = ['fields' => [], 'meta' => []];
                foreach ($metaMap as $target => $source) {
                    $entities[$id]['meta'][$target] = $row[$source] ?? null;
                }
                if ($metaMap === []) {
                    $entities[$id]['meta'] = [
                        'icon' => $row['icon'] ?? null,
                        'color' => $row['color'] ?? null,
                        'image' => $row['image'] ?? null,
                    ];
                }
            }

            foreach ($fieldMap as $target => $source) {
                $entities[$id]['fields'][$target][$row['lang']] = $row[$source];
            }
        }

        return array_map($formatter, $entities);
    }

    private function formatSector(array $sector, string $locale): array
    {
        $dummy = DummyData::findBySlug(DummyData::sectors(), $sector['slug']) ?? [];

        return [
            'slug' => $sector['slug'],
            'icon' => $sector['icon'],
            'color' => $sector['color'],
            'name' => $sector['name'],
            'overview' => $sector['overview'],
            'services' => $sector['services'] ?? [],
            'image' => $sector['image'] ?: ($dummy['image'] ?? ''),
        ];
    }

    private function formatProject(array $project, string $locale): array
    {
        $dummy = DummyData::findBySlug(DummyData::projects(), $project['slug']) ?? [];

        return [
            'slug' => $project['slug'],
            'category' => $project['category'] ?? [],
            'status' => $project['status'],
            'location' => $project['location'],
            'delivery_date' => $project['delivery_date'],
            'start_price' => $project['start_price'],
            'currency' => $project['currency'],
            'is_featured' => $project['is_featured'],
            'title' => $project['title'],
            'description' => $project['description'],
            'features' => $project['features'] ?? [],
            'image' => $project['image'] ?: ($dummy['image'] ?? ''),
        ];
    }

    private function formatNews(array $article, string $locale): array
    {
        $dummy = DummyData::findBySlug(DummyData::news(), $article['slug']) ?? [];

        return [
            'slug' => $article['slug'],
            'category' => $article['category'] ?? [],
            'publish_date' => $article['publish_date'],
            'is_featured' => $article['is_featured'],
            'title' => $article['title'],
            'excerpt' => $article['excerpt'],
            'content' => $article['content'],
            'image' => $article['image'] ?: ($dummy['image'] ?? ''),
        ];
    }

    private function languageId(string $locale): int
    {
        $stmt = $this->pdo->prepare('SELECT id FROM languages WHERE code = :code LIMIT 1');
        $stmt->execute(['code' => $locale]);
        $row = $stmt->fetch();
        if ($row) {
            return (int) $row['id'];
        }

        $stmt = $this->pdo->query('SELECT id FROM languages WHERE is_default = 1 LIMIT 1');
        return (int) ($stmt->fetchColumn() ?: 1);
    }

    private function mergeTranslations(array $primary, array $fallback): array
    {
        return array_merge($fallback, array_filter($primary, static fn($value) => $value !== null && $value !== ''));
    }

    private function mergeScalarTranslation(?string $value, array $fallback, string $locale): array
    {
        if ($value !== null && $value !== '') {
            return $this->mergeTranslations([$locale => $value], $fallback);
        }

        return $fallback;
    }

    private function menuPath(string $url, string $locale): string
    {
        if (preg_match('#^/[a-z]{2}(?:/(.*))?$#', $url, $matches)) {
            return $matches[1] ?? '';
        }

        return ltrim($url, '/');
    }

    private function normalizeInternalPath(string $url): string
    {
        if (preg_match('#^/[a-z]{2}/(.+)$#', $url, $matches)) {
            return $matches[1];
        }

        return ltrim($url, '/');
    }
}
