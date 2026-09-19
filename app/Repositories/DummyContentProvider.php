<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Data\DummyData;

class DummyContentProvider implements ContentProviderInterface
{
    public function nav(string $locale): array
    {
        return DummyData::nav();
    }

    public function footerMenus(string $locale): array
    {
        return DummyData::footerMenus();
    }

    public function heroSlides(string $locale): array
    {
        return DummyData::heroSlides();
    }

    public function stats(string $locale): array
    {
        return DummyData::stats();
    }

    public function sectors(string $locale): array
    {
        return DummyData::sectors();
    }

    public function sectorBySlug(string $slug, string $locale): ?array
    {
        return DummyData::findBySlug(DummyData::sectors(), $slug);
    }

    public function projects(string $locale): array
    {
        return DummyData::projects();
    }

    public function projectBySlug(string $slug, string $locale): ?array
    {
        return DummyData::findBySlug(DummyData::projects(), $slug);
    }

    public function news(string $locale): array
    {
        return DummyData::news();
    }

    public function newsBySlug(string $slug, string $locale): ?array
    {
        return DummyData::findBySlug(DummyData::news(), $slug);
    }

    public function gallery(string $locale): array
    {
        return DummyData::gallery();
    }

    public function offices(string $locale): array
    {
        return DummyData::offices();
    }

    public function team(string $locale): array
    {
        return DummyData::team();
    }

    public function aboutContent(string $locale): array
    {
        return DummyData::aboutContent();
    }

    public function values(string $locale): array
    {
        return DummyData::values();
    }

    public function stores(string $locale): array
    {
        return DummyData::stores();
    }

    public function partnerships(string $locale): array
    {
        return DummyData::partnerships();
    }

    public function operations(string $locale): array
    {
        return DummyData::operations();
    }

    public function banner(string $location): string
    {
        return DummyData::banner($location);
    }

    public function newsComments(int $newsId): array
    {
        return [];
    }

    public function addNewsComment(int $newsId, string $name, string $email, string $content): void
    {
        // Dummy provider cannot persist comments.
    }
}
