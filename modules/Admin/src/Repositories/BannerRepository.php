<?php

declare(strict_types=1);

namespace Admin\Repositories;

use PDO;

class BannerRepository extends BaseAdminRepository
{
    public const LOCATIONS = [
        'about' => 'About page header',
        'contact' => 'Contact page header',
        'stores' => 'Stores page header',
        'partnerships' => 'Partnerships page header',
        'gallery' => 'Gallery page header',
        'news' => 'News listing header',
        'projects' => 'Projects listing header',
        'sectors' => 'Sectors listing header',
        'home_mid' => 'Home — mid-section image',
        'home_cta' => 'Home — CTA band background',
    ];

    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT sb.*, m.file_path, m.original_name
             FROM site_banners sb
             LEFT JOIN media m ON m.id = sb.media_id
             ORDER BY sb.location'
        );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $byLocation = [];
        foreach ($rows as $row) {
            $byLocation[$row['location']] = $row;
        }

        $items = [];
        foreach (self::LOCATIONS as $location => $label) {
            $row = $byLocation[$location] ?? null;
            $items[] = [
                'id' => $row['id'] ?? null,
                'location' => $location,
                'label' => $label,
                'media_id' => $row['media_id'] ?? null,
                'file_path' => $row['file_path'] ?? null,
                'fallback_url' => $row['fallback_url'] ?? null,
                'preview' => media_url($row['file_path'] ?? null, (string) ($row['fallback_url'] ?? '')),
            ];
        }

        return $items;
    }

    public function findByLocation(string $location): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM site_banners WHERE location = :location LIMIT 1');
        $stmt->execute(['location' => $location]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function save(string $location, ?int $mediaId, ?string $fallbackUrl): void
    {
        $existing = $this->findByLocation($location);
        if ($existing) {
            $stmt = $this->pdo->prepare(
                'UPDATE site_banners SET media_id = :media_id, fallback_url = :fallback_url WHERE location = :location'
            );
        } else {
            $stmt = $this->pdo->prepare(
                'INSERT INTO site_banners (location, media_id, fallback_url) VALUES (:location, :media_id, :fallback_url)'
            );
        }
        $stmt->execute([
            'location' => $location,
            'media_id' => $mediaId,
            'fallback_url' => $fallbackUrl !== '' ? $fallbackUrl : null,
        ]);
    }
}
