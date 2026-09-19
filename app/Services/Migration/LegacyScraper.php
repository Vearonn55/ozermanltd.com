<?php

declare(strict_types=1);

namespace App\Services\Migration;

class LegacyScraper
{
    public function scrapeUrl(string $url): array
    {
        $html = $this->fetch($url);
        if ($html === null) {
            return [
                'source_url' => $url,
                'error' => 'Failed to fetch URL',
            ];
        }

        return array_merge(
            ['source_url' => $url],
            $this->parseHtml($html, $url)
        );
    }

    public function scrapeSite(string $baseUrl, array $locales, array $paths): array
    {
        $results = [];

        foreach ($locales as $locale) {
            foreach ($paths as $path) {
                $url = rtrim($baseUrl, '/') . '/' . $locale;
                if ($path !== '') {
                    $url .= '/' . ltrim($path, '/');
                }

                $parsed = $this->scrapeUrl($url);
                $parsed['locale'] = $locale;
                $parsed['path'] = $path;
                $results[] = $parsed;
            }
        }

        return $results;
    }

    private function fetch(string $url): ?string
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_USERAGENT => 'OzermanSeoImporter/1.0',
            ]);
            $body = curl_exec($ch);
            $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($body === false || $status >= 400) {
                return null;
            }

            return (string) $body;
        }

        $context = stream_context_create([
            'http' => [
                'timeout' => 20,
                'header' => "User-Agent: OzermanSeoImporter/1.0\r\n",
            ],
        ]);

        $body = @file_get_contents($url, false, $context);
        return $body === false ? null : $body;
    }

    private function parseHtml(string $html, string $url): array
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        return [
            'meta_title' => $this->nodeText($xpath, '//title'),
            'meta_description' => $this->metaContent($xpath, 'description'),
            'robots' => $this->metaContent($xpath, 'robots') ?: 'index, follow',
            'canonical_url' => $this->linkHref($xpath, 'canonical') ?: $url,
            'og_title' => $this->metaProperty($xpath, 'og:title'),
            'og_description' => $this->metaProperty($xpath, 'og:description'),
            'og_image' => $this->metaProperty($xpath, 'og:image'),
            'og_type' => $this->metaProperty($xpath, 'og:type') ?: 'website',
            'structured_data' => $this->extractJsonLd($xpath),
            'hreflang' => $this->extractHreflang($xpath),
        ];
    }

    private function nodeText(\DOMXPath $xpath, string $query): string
    {
        $node = $xpath->query($query)->item(0);
        return $node ? trim($node->textContent) : '';
    }

    private function metaContent(\DOMXPath $xpath, string $name): string
    {
        $node = $xpath->query("//meta[@name='{$name}']")->item(0);
        return $node instanceof \DOMElement ? trim($node->getAttribute('content')) : '';
    }

    private function metaProperty(\DOMXPath $xpath, string $property): string
    {
        $node = $xpath->query("//meta[@property='{$property}']")->item(0);
        return $node instanceof \DOMElement ? trim($node->getAttribute('content')) : '';
    }

    private function linkHref(\DOMXPath $xpath, string $rel): string
    {
        $node = $xpath->query("//link[@rel='{$rel}']")->item(0);
        return $node instanceof \DOMElement ? trim($node->getAttribute('href')) : '';
    }

    private function extractJsonLd(\DOMXPath $xpath): array
    {
        $node = $xpath->query("//script[@type='application/ld+json']")->item(0);
        if (!$node) {
            return [];
        }

        $decoded = json_decode(trim($node->textContent), true);
        return is_array($decoded) ? $decoded : [];
    }

    private function extractHreflang(\DOMXPath $xpath): array
    {
        $links = [];
        $nodes = $xpath->query("//link[@rel='alternate' and @hreflang]");

        foreach ($nodes as $node) {
            if (!$node instanceof \DOMElement) {
                continue;
            }

            $links[] = [
                'locale' => $node->getAttribute('hreflang'),
                'url' => $node->getAttribute('href'),
            ];
        }

        return $links;
    }
}
