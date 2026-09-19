<?php

declare(strict_types=1);

namespace App\Repositories;

interface ContentProviderInterface
{
    public function nav(string $locale): array;

    public function footerMenus(string $locale): array;

    public function heroSlides(string $locale): array;

    public function stats(string $locale): array;

    public function sectors(string $locale): array;

    public function sectorBySlug(string $slug, string $locale): ?array;

    public function projects(string $locale): array;

    public function projectBySlug(string $slug, string $locale): ?array;

    public function news(string $locale): array;

    public function newsBySlug(string $slug, string $locale): ?array;

    public function gallery(string $locale): array;

    public function offices(string $locale): array;

    public function team(string $locale): array;

    public function aboutContent(string $locale): array;

    public function values(string $locale): array;
    public function stores(string $locale): array;
    public function partnerships(string $locale): array;
    public function operations(string $locale): array;

    public function banner(string $location): string;

    public function newsComments(int $newsId): array;

    public function addNewsComment(int $newsId, string $name, string $email, string $content): void;
}
