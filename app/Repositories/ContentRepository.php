<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Infrastructure\Database;

class ContentRepository implements ContentProviderInterface
{
    private DummyContentProvider $dummy;
    private ?DatabaseContentProvider $database = null;

    public function __construct()
    {
        $this->dummy = new DummyContentProvider();
    }

    public function nav(string $locale): array
    {
        return $this->resolve()->nav($locale);
    }

    public function footerMenus(string $locale): array
    {
        return $this->resolve()->footerMenus($locale);
    }

    public function heroSlides(string $locale): array
    {
        return $this->resolve()->heroSlides($locale);
    }

    public function stats(string $locale): array
    {
        return $this->resolve()->stats($locale);
    }

    public function sectors(string $locale): array
    {
        return $this->resolve()->sectors($locale);
    }

    public function sectorBySlug(string $slug, string $locale): ?array
    {
        return $this->resolve()->sectorBySlug($slug, $locale);
    }

    public function projects(string $locale): array
    {
        return $this->resolve()->projects($locale);
    }

    public function projectBySlug(string $slug, string $locale): ?array
    {
        return $this->resolve()->projectBySlug($slug, $locale);
    }

    public function news(string $locale): array
    {
        return $this->resolve()->news($locale);
    }

    public function newsBySlug(string $slug, string $locale): ?array
    {
        return $this->resolve()->newsBySlug($slug, $locale);
    }

    public function gallery(string $locale): array
    {
        return $this->resolve()->gallery($locale);
    }

    public function offices(string $locale): array
    {
        return $this->resolve()->offices($locale);
    }

    public function team(string $locale): array
    {
        return $this->resolve()->team($locale);
    }

    public function aboutContent(string $locale): array
    {
        return $this->resolve()->aboutContent($locale);
    }

    public function values(string $locale): array
    {
        return $this->resolve()->values($locale);
    }

    public function stores(string $locale): array
    {
        return $this->resolve()->stores($locale);
    }

    public function partnerships(string $locale): array
    {
        return $this->resolve()->partnerships($locale);
    }

    public function operations(string $locale): array
    {
        return $this->resolve()->operations($locale);
    }

    public function banner(string $location): string
    {
        return $this->resolve()->banner($location);
    }

    public function newsComments(int $newsId): array
    {
        return $this->resolve()->newsComments($newsId);
    }

    public function addNewsComment(int $newsId, string $name, string $email, string $content): void
    {
        $this->resolve()->addNewsComment($newsId, $name, $email, $content);
    }

    public function usingDatabase(): bool
    {
        return $this->shouldUseDatabase();
    }

    /** @return array<string, mixed>|null */
    public function cmsPageByPath(string $path, string $locale): ?array
    {
        if (!$this->shouldUseDatabase()) {
            return null;
        }

        try {
            $provider = $this->database ??= new DatabaseContentProvider(
                Database::connection(force: true)
            );
            return $provider->cmsPageByPath($path, $locale);
        } catch (\Throwable) {
            return null;
        }
    }

    private function resolve(): ContentProviderInterface
    {
        if (!$this->shouldUseDatabase()) {
            return $this->dummy;
        }

        try {
            return $this->database ??= new DatabaseContentProvider(
                Database::connection(force: true)
            );
        } catch (\Throwable $e) {
            if (db_fallback_to_dummy()) {
                return $this->dummy;
            }

            throw new \RuntimeException('Database content is required but unavailable.', 0, $e);
        }
    }

    private function shouldUseDatabase(): bool
    {
        $config = require CONFIG_PATH . '/database.php';

        if (!empty($config['use_dummy_data'])) {
            return false;
        }

        return Database::connection(force: true) !== null;
    }
}
