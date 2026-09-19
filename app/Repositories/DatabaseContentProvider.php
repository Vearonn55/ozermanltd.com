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

    public function footerMenus(string $locale): array
    {
        $stmt = $this->pdo->query(
            "SELECT m.location,
                    mt.title, tl.code AS title_lang,
                    mi.id AS item_id, mi.url, mi.target, mi.sort_order,
                    mit.label, il.code AS label_lang
             FROM menus m
             LEFT JOIN menu_translations mt ON mt.menu_id = m.id
             LEFT JOIN languages tl ON tl.id = mt.language_id
             LEFT JOIN menu_items mi ON mi.menu_id = m.id AND mi.is_active = 1
             LEFT JOIN menu_item_translations mit ON mit.item_id = mi.id
             LEFT JOIN languages il ON il.id = mit.language_id
             WHERE m.location IN ('footer_col1', 'footer_col2', 'footer_col3')
             ORDER BY m.location, mi.sort_order, mi.id"
        );

        $columns = [];
        foreach ($stmt->fetchAll() as $row) {
            $location = $row['location'];
            $columns[$location] ??= ['title' => [], 'items' => []];

            if ($row['title_lang'] !== null && $row['title'] !== null) {
                $columns[$location]['title'][$row['title_lang']] = $row['title'];
            }

            if ($row['item_id'] !== null) {
                $itemId = (int) $row['item_id'];
                $columns[$location]['items'][$itemId]['url'] = $this->normalizeInternalPath((string) $row['url']);
                $columns[$location]['items'][$itemId]['target'] = $row['target'] ?: '_self';
                if ($row['label_lang'] !== null && $row['label'] !== null) {
                    $columns[$location]['items'][$itemId]['label'][$row['label_lang']] = $row['label'];
                }
            }
        }

        $dummy = DummyData::footerMenus();
        $menus = [];

        foreach (['footer_col1', 'footer_col2', 'footer_col3'] as $location) {
            $column = $columns[$location] ?? null;

            if ($column === null || $column['items'] === []) {
                $menus[$location] = $dummy[$location];
                continue;
            }

            $links = [];
            foreach ($column['items'] as $item) {
                if (empty($item['label'])) {
                    continue;
                }
                $links[] = [
                    'label' => $item['label'],
                    'url' => $item['url'],
                    'target' => $item['target'],
                ];
            }

            $menus[$location] = [
                'title' => $this->mergeTranslations($column['title'], $dummy[$location]['title'] ?? []),
                'links' => $links !== [] ? $links : ($dummy[$location]['links'] ?? []),
            ];
        }

        return $menus;
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
                'image' => $this->resolveMediaUrl($row['image'] ?: null, $dummy['image'] ?? ''),
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
            $collections[$id]['cover'] = $this->resolveMediaUrl($row['cover'] ?: null);
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
                    'image' => $this->resolveMediaUrl($item['image'] ?: null, $dummyItem['image'] ?? ''),
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
                'image' => $this->resolveMediaUrl($row['image'] ?: null, $dummy['image'] ?? ''),
            ];
        }

        return $team;
    }

    public function aboutContent(string $locale): array
    {
        $dummy = DummyData::aboutContent();
        $row = $this->fetchAboutContentRow($locale);

        // EN seed may store a multilingual JSON payload — reuse it for other locales.
        if ((!$row || empty($row['content'])) && $locale !== 'en') {
            $row = $this->fetchAboutContentRow('en');
        }

        if (!$row || empty($row['content'])) {
            $content = $dummy;
        } else {
            $raw = trim((string) $row['content']);
            $decoded = json_decode($raw, true);

            if (!is_array($decoded)) {
                $content = [
                    'history' => $this->mergeTranslations([$locale => $raw], $dummy['history']),
                    'vision' => $dummy['vision'],
                    'mission' => $dummy['mission'],
                ];
            } else {
                $content = [
                    'history' => $this->mergeTranslations(
                        is_array($decoded['history'] ?? null) ? $decoded['history'] : [$locale => $decoded['history'] ?? ''],
                        $dummy['history']
                    ),
                    'vision' => $this->mergeTranslations(
                        is_array($decoded['vision'] ?? null) ? $decoded['vision'] : [$locale => $decoded['vision'] ?? ''],
                        $dummy['vision']
                    ),
                    'mission' => $this->mergeTranslations(
                        is_array($decoded['mission'] ?? null) ? $decoded['mission'] : [$locale => $decoded['mission'] ?? ''],
                        $dummy['mission']
                    ),
                ];
            }
        }

        $visionBlock = $this->loadContentBlocks('about_vision');
        if ($visionBlock !== []) {
            $content['vision'] = $this->mergeTranslations($visionBlock[0]['body'] ?? [], $content['vision']);
        }

        $missionBlock = $this->loadContentBlocks('about_mission');
        if ($missionBlock !== []) {
            $content['mission'] = $this->mergeTranslations($missionBlock[0]['body'] ?? [], $content['mission']);
        }

        return $content;
    }

    /** @return array<string, mixed>|false */
    private function fetchAboutContentRow(string $locale)
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

        return $stmt->fetch();
    }

    public function values(string $locale): array
    {
        $blocks = $this->loadContentBlocks('about_values');
        if ($blocks === []) {
            return DummyData::values();
        }

        $dummy = DummyData::values();
        $values = [];
        foreach ($blocks as $index => $block) {
            $fallback = $dummy[$index] ?? [];
            $values[] = [
                'title' => $this->mergeTranslations($block['title'] ?? [], $fallback['title'] ?? []),
                'description' => $this->mergeTranslations($block['body'] ?? [], $fallback['description'] ?? []),
                'icon' => $block['icon'] ?: ($fallback['icon'] ?? 'star'),
            ];
        }

        return $values;
    }

    public function stores(string $locale): array
    {
        $stmt = $this->pdo->query(
            'SELECT s.id, s.phone, s.email, s.sort_order, m.file_path AS image,
                    st.name, st.slug, st.city, st.address, st.working_hours, l.code AS lang
             FROM stores s
             INNER JOIN store_translations st ON st.store_id = s.id
             INNER JOIN languages l ON l.id = st.language_id
             LEFT JOIN media m ON m.id = s.media_id
             WHERE s.is_active = 1
             ORDER BY s.sort_order, s.id, l.code'
        );

        $grouped = $this->groupTranslatedEntities($stmt->fetchAll(), function (array $entity): array {
            return [
                'slug' => $entity['fields']['slug']['en'] ?? array_values($entity['fields']['slug'])[0] ?? '',
                'name' => $entity['fields']['name'],
                'city' => $entity['fields']['city'],
                'address' => $entity['fields']['address'],
                'hours' => $entity['fields']['working_hours'],
                'phone' => $entity['meta']['phone'] ?? '',
                'email' => $entity['meta']['email'] ?? '',
                'image' => $entity['meta']['image'] ?? '',
            ];
        }, [
            'slug' => 'slug',
            'name' => 'name',
            'city' => 'city',
            'address' => 'address',
            'working_hours' => 'working_hours',
        ], [
            'phone' => 'phone',
            'email' => 'email',
            'image' => 'image',
        ]);

        if ($grouped === []) {
            return DummyData::stores();
        }

        $dummy = DummyData::stores();
        foreach ($grouped as $index => &$store) {
            $fallback = $dummy[$index] ?? [];
            if ($store['image'] === '' || $store['image'] === null) {
                $store['image'] = $fallback['image'] ?? '';
            } else {
                $store['image'] = $this->resolveMediaUrl((string) $store['image'], $fallback['image'] ?? '');
            }
            $store['name'] = $this->mergeTranslations($store['name'] ?? [], $fallback['name'] ?? []);
            $store['city'] = $this->mergeTranslations($store['city'] ?? [], $fallback['city'] ?? []);
            $store['address'] = $this->mergeTranslations($store['address'] ?? [], $fallback['address'] ?? []);
            $store['hours'] = $this->mergeTranslations($store['hours'] ?? [], $fallback['hours'] ?? []);
        }
        unset($store);

        return array_values($grouped);
    }

    public function partnerships(string $locale): array
    {
        $stmt = $this->pdo->query(
            'SELECT b.id, b.slug, b.logo, b.website_url, b.sort_order, m.file_path AS image,
                    bt.name, bt.tagline, bt.description, l.code AS lang
             FROM brands b
             INNER JOIN brand_translations bt ON bt.brand_id = b.id
             INNER JOIN languages l ON l.id = bt.language_id
             LEFT JOIN media m ON m.id = b.media_id
             WHERE b.is_active = 1
             ORDER BY b.sort_order, b.id, l.code'
        );

        $grouped = $this->groupTranslatedEntities($stmt->fetchAll(), function (array $entity): array {
            $nameEn = $entity['fields']['name']['en'] ?? array_values($entity['fields']['name'])[0] ?? '';

            return [
                'slug' => $entity['meta']['slug'] ?: strtolower(preg_replace('/[^a-z0-9]+/i', '-', $nameEn) ?? ''),
                'name' => $nameEn,
                'tagline' => $entity['fields']['tagline'],
                'description' => $entity['fields']['description'],
                'url' => $entity['meta']['website_url'] ?? '',
                'image' => $entity['meta']['image'] ?: ($entity['meta']['logo'] ?? ''),
            ];
        }, [
            'name' => 'name',
            'tagline' => 'tagline',
            'description' => 'description',
        ], [
            'slug' => 'slug',
            'logo' => 'logo',
            'website_url' => 'website_url',
            'image' => 'image',
        ]);

        if ($grouped === []) {
            return DummyData::partnerships();
        }

        $dummy = DummyData::partnerships();
        foreach ($grouped as $index => &$partner) {
            $fallback = $dummy[$index] ?? [];
            if ($partner['image'] === '' || $partner['image'] === null) {
                $partner['image'] = $fallback['image'] ?? '';
            } elseif (!str_starts_with((string) $partner['image'], 'http')) {
                $partner['image'] = $this->resolveMediaUrl((string) $partner['image'], $fallback['image'] ?? '');
            }
            if ($partner['url'] === '') {
                $partner['url'] = $fallback['url'] ?? '';
            }
            if ($partner['name'] === '') {
                $partner['name'] = $fallback['name'] ?? '';
            }
            $partner['tagline'] = $this->mergeTranslations($partner['tagline'] ?? [], $fallback['tagline'] ?? []);
            $partner['description'] = $this->mergeTranslations($partner['description'] ?? [], $fallback['description'] ?? []);
        }
        unset($partner);

        return array_values($grouped);
    }

    public function operations(string $locale): array
    {
        $blocks = $this->loadContentBlocks('home_operations');
        if ($blocks === []) {
            return DummyData::operations();
        }

        $dummy = DummyData::operations();
        $operations = [];
        foreach ($blocks as $index => $block) {
            $fallback = $dummy[$index] ?? [];
            $operations[] = [
                'title' => $this->mergeTranslations($block['title'] ?? [], $fallback['title'] ?? []),
                'description' => $this->mergeTranslations($block['body'] ?? [], $fallback['description'] ?? []),
            ];
        }

        return $operations;
    }

    /**
     * @return list<array{icon: ?string, title: array<string, string>, body: array<string, string>}>
     */
    private function loadContentBlocks(string $area): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT cb.id, cb.icon, cb.sort_order, cbt.title, cbt.body, l.code AS lang
             FROM content_blocks cb
             INNER JOIN content_block_translations cbt ON cbt.block_id = cb.id
             INNER JOIN languages l ON l.id = cbt.language_id
             WHERE cb.area = :area AND cb.is_active = 1
             ORDER BY cb.sort_order, cb.id, l.code'
        );
        $stmt->execute(['area' => $area]);

        return $this->groupTranslatedEntities($stmt->fetchAll(), static function (array $entity): array {
            return [
                'icon' => $entity['meta']['icon'] ?? null,
                'title' => $entity['fields']['title'] ?? [],
                'body' => $entity['fields']['body'] ?? [],
            ];
        }, [
            'title' => 'title',
            'body' => 'body',
        ], [
            'icon' => 'icon',
        ]);
    }

    private function loadSectors(): array
    {
        $stmt = $this->pdo->query(
            'SELECT s.id, s.icon, s.color, s.sort_order, st.slug, st.name, st.overview, st.services_text, l.code AS lang,
                    m.file_path AS image
             FROM sectors s
             INNER JOIN sector_translations st ON st.sector_id = s.id
             INNER JOIN languages l ON l.id = st.language_id
             LEFT JOIN media m ON m.id = s.media_id
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
                'image' => $this->resolveMediaUrl($entity['meta']['image'] ?? null),
            ];
        }, [
            'slug' => 'slug',
            'name' => 'name',
            'overview' => 'overview',
            'services_text' => 'services_text',
        ], [
            'icon' => 'icon',
            'color' => 'color',
            'image' => 'image',
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
                'image' => $this->resolveMediaUrl($entity['meta']['image'] ?? null),
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
               AND (n.publish_date IS NULL OR n.publish_date <= NOW())
             ORDER BY n.publish_date DESC, l.code'
        );

        return $this->groupTranslatedEntities($stmt->fetchAll(), function (array $entity): array {
            return [
                'id' => (int) ($entity['meta']['id'] ?? 0),
                'slug' => $entity['fields']['slug']['en'] ?? array_values($entity['fields']['slug'])[0] ?? '',
                'category' => $entity['fields']['category_name'],
                'publish_date' => $entity['meta']['publish_date'] ? date('Y-m-d', strtotime((string) $entity['meta']['publish_date'])) : null,
                'is_featured' => (bool) $entity['meta']['is_featured'],
                'title' => $entity['fields']['title'],
                'excerpt' => $entity['fields']['excerpt'],
                'content' => $entity['fields']['content'],
                'image' => $this->resolveMediaUrl($entity['meta']['image'] ?? null),
            ];
        }, [
            'slug' => 'slug',
            'title' => 'title',
            'excerpt' => 'excerpt',
            'content' => 'content',
            'category_name' => 'category_name',
        ], [
            'id' => 'id',
            'publish_date' => 'publish_date',
            'is_featured' => 'is_featured',
            'image' => 'image',
        ]);
    }

    public function banner(string $location): string
    {
        $stmt = $this->pdo->prepare(
            'SELECT sb.fallback_url, m.file_path
             FROM site_banners sb
             LEFT JOIN media m ON m.id = sb.media_id
             WHERE sb.location = :location
             LIMIT 1'
        );
        $stmt->execute(['location' => $location]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return DummyData::banner($location);
        }

        return $this->resolveMediaUrl($row['file_path'] ?: null, (string) ($row['fallback_url'] ?: DummyData::banner($location)));
    }

    public function newsComments(int $newsId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, author_name, content, created_at
             FROM news_comments
             WHERE news_id = :news_id AND status = 'approved'
             ORDER BY created_at DESC"
        );
        $stmt->execute(['news_id' => $newsId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function addNewsComment(int $newsId, string $name, string $email, string $content): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO news_comments (news_id, author_name, author_email, content, status)
             VALUES (:news_id, :name, :email, :content, 'pending')"
        );
        $stmt->execute([
            'news_id' => $newsId,
            'name' => $name,
            'email' => $email,
            'content' => $content,
        ]);
    }

    /**
     * Published CMS page for a public locale path (custom_path, then slug).
     *
     * @return array<string, mixed>|null
     */
    public function cmsPageByPath(string $path, string $locale): ?array
    {
        $path = trim($path, '/');
        if ($path === '') {
            return null;
        }

        $languageId = $this->languageId($locale);
        $stmt = $this->pdo->prepare(
            'SELECT p.id, p.slug, p.custom_path, p.template, p.embed_mode, p.status,
                    pt.title, pt.content, pt.excerpt, pt.html_embed, pt.css_embed, pt.js_embed, pt.php_embed,
                    pt.meta_title, pt.meta_description
             FROM pages p
             LEFT JOIN page_translations pt ON pt.page_id = p.id AND pt.language_id = :language_id
             WHERE p.status = \'published\'
               AND (p.custom_path = :path OR p.slug = :path2)
             LIMIT 1'
        );

        try {
            $stmt->execute([
                'language_id' => $languageId,
                'path' => $path,
                'path2' => $path,
            ]);
        } catch (\Throwable) {
            return null;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
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
            'image' => $this->resolveMediaUrl($sector['image'] ?? null, $dummy['image'] ?? ''),
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
            'image' => $this->resolveMediaUrl($project['image'] ?? null, $dummy['image'] ?? ''),
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
            'image' => $this->resolveMediaUrl($article['image'] ?? null, $dummy['image'] ?? ''),
            'id' => $article['id'] ?? null,
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

    private function resolveMediaUrl(?string $path, string $fallback = ''): string
    {
        return media_url($path, $fallback);
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
