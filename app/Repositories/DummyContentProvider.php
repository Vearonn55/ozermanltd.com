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
}
