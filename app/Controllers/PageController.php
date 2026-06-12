<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Data\DummyData;

class PageController
{
    public function home(): void
    {
        view('pages.home', [
            'title' => page_title(t([
                'en' => 'Home',
                'tr' => 'Ana Sayfa',
                'ar' => 'الرئيسية',
            ])),
            'meta_description' => t([
                'en' => 'Ozerman Ltd — A diversified international business group operating across trading, construction, real estate, manufacturing, logistics, and energy.',
                'tr' => 'Ozerman Ltd — Ticaret, inşaat, gayrimenkul, üretim, lojistik ve enerji sektörlerinde faaliyet gösteren çeşitlendirilmiş uluslararası iş grubu.',
                'ar' => 'أوزرمان المحدودة — مجموعة أعمال دولية متنوعة تعمل في التجارة والبناء والعقارات والتصنيع واللوجستيات والطاقة.',
            ]),
            'slides' => DummyData::heroSlides(),
            'stats' => DummyData::stats(),
            'sectors' => array_slice(DummyData::sectors(), 0, 6),
            'projects' => array_filter(DummyData::projects(), fn($p) => $p['is_featured']),
            'news' => array_slice(DummyData::news(), 0, 3),
        ]);
    }

    public function about(): void
    {
        view('pages.about', [
            'title' => page_title(t([
                'en' => 'About Us',
                'tr' => 'Hakkımızda',
                'ar' => 'من نحن',
            ])),
            'meta_description' => t([
                'en' => 'Learn about Ozerman Ltd — our history, vision, mission, core values, and leadership team.',
                'tr' => 'Ozerman Ltd hakkında bilgi edinin — tarihimiz, vizyonumuz, misyonumuz, temel değerlerimiz ve liderlik ekibimiz.',
                'ar' => 'تعرف على أوزرمان المحدودة — تاريخنا ورؤيتنا ومهمتنا وقيمنا الأساسية وفريق القيادة.',
            ]),
            'content' => DummyData::aboutContent(),
            'values' => DummyData::values(),
            'team' => DummyData::team(),
            'stats' => DummyData::stats(),
        ]);
    }

    public function sectors(): void
    {
        view('pages.sectors.index', [
            'title' => page_title(t([
                'en' => 'Business Sectors',
                'tr' => 'İş Sektörleri',
                'ar' => 'قطاعات الأعمال',
            ])),
            'meta_description' => t([
                'en' => 'Explore Ozerman Ltd business sectors — trading, construction, real estate, manufacturing, logistics, and energy.',
                'tr' => 'Ozerman Ltd iş sektörlerini keşfedin — ticaret, inşaat, gayrimenkul, üretim, lojistik ve enerji.',
                'ar' => 'استكشف قطاعات أعمال أوزرمان — التجارة والبناء والعقارات والتصنيع واللوجستيات والطاقة.',
            ]),
            'sectors' => DummyData::sectors(),
        ]);
    }

    public function sector(string $slug): void
    {
        $sector = DummyData::findBySlug(DummyData::sectors(), $slug);

        if (!$sector) {
            $this->notFound();
            return;
        }

        $relatedProjects = array_filter(
            DummyData::projects(),
            fn($p) => in_array($slug, ['real-estate', 'construction']) || $p['is_featured']
        );

        view('pages.sectors.show', [
            'title' => page_title(t($sector['name'])),
            'meta_description' => t($sector['overview']),
            'sector' => $sector,
            'projects' => array_slice($relatedProjects, 0, 3),
        ]);
    }

    public function projects(): void
    {
        view('pages.projects.index', [
            'title' => page_title(t([
                'en' => 'Projects',
                'tr' => 'Projeler',
                'ar' => 'المشاريع',
            ])),
            'meta_description' => t([
                'en' => 'Browse Ozerman Ltd project portfolio — residential, commercial, and industrial developments worldwide.',
                'tr' => 'Ozerman Ltd proje portföyüne göz atın — dünya çapında konut, ticari ve endüstriyel gelişmeler.',
                'ar' => 'تصفح محفظة مشاريع أوزرمان — تطويرات سكنية وتجارية وصناعية حول العالم.',
            ]),
            'projects' => DummyData::projects(),
        ]);
    }

    public function project(string $slug): void
    {
        $project = DummyData::findBySlug(DummyData::projects(), $slug);

        if (!$project) {
            $this->notFound();
            return;
        }

        view('pages.projects.show', [
            'title' => page_title(t($project['title'])),
            'meta_description' => t($project['description']),
            'project' => $project,
        ]);
    }

    public function news(): void
    {
        view('pages.news.index', [
            'title' => page_title(t([
                'en' => 'News & Announcements',
                'tr' => 'Haberler ve Duyurular',
                'ar' => 'الأخبار والإعلانات',
            ])),
            'meta_description' => t([
                'en' => 'Latest news and announcements from Ozerman Ltd.',
                'tr' => 'Ozerman Ltd\'den son haberler ve duyurular.',
                'ar' => 'آخر الأخبار والإعلانات من أوزرمان المحدودة.',
            ]),
            'articles' => DummyData::news(),
        ]);
    }

    public function article(string $slug): void
    {
        $article = DummyData::findBySlug(DummyData::news(), $slug);

        if (!$article) {
            $this->notFound();
            return;
        }

        view('pages.news.show', [
            'title' => page_title(t($article['title'])),
            'meta_description' => t($article['excerpt']),
            'article' => $article,
            'related' => array_filter(DummyData::news(), fn($a) => $a['slug'] !== $slug),
        ]);
    }

    public function gallery(): void
    {
        view('pages.gallery', [
            'title' => page_title(t([
                'en' => 'Media Gallery',
                'tr' => 'Medya Galerisi',
                'ar' => 'معرض الوسائط',
            ])),
            'meta_description' => t([
                'en' => 'Corporate media gallery — events, project sites, and team culture at Ozerman Ltd.',
                'tr' => 'Kurumsal medya galerisi — Ozerman Ltd\'de etkinlikler, proje sahaları ve ekip kültürü.',
                'ar' => 'معرض الوسائط المؤسسية — الفعاليات ومواقع المشاريع وثقافة الفريق في أوزرمان.',
            ]),
            'collections' => DummyData::gallery(),
        ]);
    }

    public function contact(): void
    {
        $success = isset($_GET['sent']);

        view('pages.contact', [
            'title' => page_title(t([
                'en' => 'Contact Us',
                'tr' => 'İletişim',
                'ar' => 'اتصل بنا',
            ])),
            'meta_description' => t([
                'en' => 'Get in touch with Ozerman Ltd — contact form, office locations, and department contacts.',
                'tr' => 'Ozerman Ltd ile iletişime geçin — iletişim formu, ofis konumları ve departman iletişim bilgileri.',
                'ar' => 'تواصل مع أوزرمان المحدودة — نموذج الاتصال ومواقع المكاتب وجهات الاتصال.',
            ]),
            'offices' => DummyData::offices(),
            'success' => $success,
        ]);
    }

    public function contactSubmit(): void
    {
        redirect(url('contact') . '?sent=1');
    }

    public function notFound(): void
    {
        http_response_code(404);
        view('pages.404', [
            'title' => page_title('404'),
            'meta_description' => 'Page not found',
        ]);
    }
}
