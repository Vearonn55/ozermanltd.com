<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\Analytics\EventCollector;

class PageController
{
    public function home(): void
    {
        $content = content();
        $locale = app_locale();

        $this->render('pages.home', [
            'slides' => $content->heroSlides($locale),
            'stats' => $content->stats($locale),
            'operations' => $content->operations($locale),
            'partnerships' => $content->partnerships($locale),
            'stores' => $content->stores($locale),
            'news' => array_slice($content->news($locale), 0, 3),
        ], [
            'entity_type' => 'page',
            'entity_key' => 'home',
            'path' => '',
            'title' => ['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'],
            'description' => [
                'en' => 'Özerman Ticaret — importer limited company connecting trusted brands with retail markets.',
                'tr' => 'Özerman Ticaret — güvenilir markaları perakende pazarlara bağlayan ithalatçı limited şirket.',
                'ar' => 'أوزرمان للتجارة — شركة استيراد محدودة تربط العلامات الموثوقة بأسواق التجزئة.',
            ],
        ]);
    }

    public function about(): void
    {
        $content = content();
        $locale = app_locale();

        $this->render('pages.about', [
            'content' => $content->aboutContent($locale),
            'values' => $content->values($locale),
            'team' => $content->team($locale),
            'stats' => $content->stats($locale),
        ], [
            'entity_type' => 'page',
            'entity_key' => 'about-us',
            'path' => 'about-us',
            'title' => ['en' => 'About Us', 'tr' => 'Hakkımızda', 'ar' => 'من نحن'],
            'description' => [
                'en' => 'Learn about Özerman Ticaret — our story as an importer limited company, values, and leadership.',
                'tr' => 'Özerman Ticaret hakkında bilgi edinin — ithalatçı limited şirket hikâyemiz, değerlerimiz ve liderlik.',
                'ar' => 'تعرف على أوزرمان للتجارة — قصتنا كشركة استيراد محدودة وقيمنا وقيادتنا.',
            ],
            'breadcrumbs' => [
                ['label' => ['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'], 'path' => ''],
                ['label' => ['en' => 'About Us', 'tr' => 'Hakkımızda', 'ar' => 'من نحن'], 'path' => 'about-us'],
            ],
        ]);
    }

    public function stores(): void
    {
        $content = content();
        $locale = app_locale();

        $this->render('pages.stores', [
            'stores' => $content->stores($locale),
        ], [
            'entity_type' => 'page',
            'entity_key' => 'our-stores',
            'path' => 'our-stores',
            'title' => ['en' => 'Our Stores', 'tr' => 'Mağazalarımız', 'ar' => 'متاجرنا'],
            'description' => [
                'en' => 'Visit Özerman Ticaret showrooms and retail points — placeholder locations pending final details.',
                'tr' => 'Özerman Ticaret showroom ve satış noktalarını ziyaret edin — konum detayları yakında güncellenecek.',
                'ar' => 'زوروا صالات عرض ونقاط بيع أوزرمان للتجارة — تفاصيل المواقع قيد التحديث.',
            ],
            'breadcrumbs' => [
                ['label' => ['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'], 'path' => ''],
                ['label' => ['en' => 'Our Stores', 'tr' => 'Mağazalarımız', 'ar' => 'متاجرنا'], 'path' => 'our-stores'],
            ],
        ]);
    }

    public function partnerships(): void
    {
        $content = content();
        $locale = app_locale();

        $this->render('pages.partnerships', [
            'partnerships' => $content->partnerships($locale),
        ], [
            'entity_type' => 'page',
            'entity_key' => 'partnerships',
            'path' => 'partnerships',
            'title' => ['en' => 'Partnerships', 'tr' => 'İş Ortaklıkları', 'ar' => 'الشراكات'],
            'description' => [
                'en' => 'Brands we represent and import — including Lajivert and Aymini — as part of our broader trade portfolio.',
                'tr' => 'Temsil ettiğimiz ve ithal ettiğimiz markalar — Lajivert ve Aymini dahil — daha geniş ticaret portföyümüzün parçası.',
                'ar' => 'العلامات التي نمثلها ونستوردها — بما في ذلك لاجيڤيرت وأيميني — ضمن محفظة تجارتنا الأوسع.',
            ],
            'breadcrumbs' => [
                ['label' => ['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'], 'path' => ''],
                ['label' => ['en' => 'Partnerships', 'tr' => 'İş Ortaklıkları', 'ar' => 'الشراكات'], 'path' => 'partnerships'],
            ],
        ]);
    }

    public function sectors(): void
    {
        $content = content();
        $locale = app_locale();

        $this->render('pages.sectors.index', [
            'sectors' => $content->sectors($locale),
        ], [
            'entity_type' => 'page',
            'entity_key' => 'sectors',
            'path' => 'sectors',
            'title' => ['en' => 'Business Sectors', 'tr' => 'İş Sektörleri', 'ar' => 'قطاعات الأعمال'],
            'description' => [
                'en' => 'Explore Ozerman Ltd business sectors — trading, construction, real estate, manufacturing, logistics, and energy.',
                'tr' => 'Ozerman Ltd iş sektörlerini keşfedin — ticaret, inşaat, gayrimenkul, üretim, lojistik ve enerji.',
                'ar' => 'استكشف قطاعات أعمال أوزرمان — التجارة والبناء والعقارات والتصنيع واللوجستيات والطاقة.',
            ],
        ]);
    }

    public function sector(string $slug): void
    {
        $content = content();
        $locale = app_locale();
        $sector = $content->sectorBySlug($slug, $locale);

        if (!$sector) {
            $this->notFound();
            return;
        }

        $relatedProjects = array_filter(
            $content->projects($locale),
            fn($p) => in_array($slug, ['real-estate', 'construction']) || $p['is_featured']
        );

        $this->render('pages.sectors.show', [
            'sector' => $sector,
            'projects' => array_slice($relatedProjects, 0, 3),
        ], [
            'entity_type' => 'sector',
            'entity_key' => $slug,
            'path' => 'sectors/' . $slug,
            'title' => $sector['name'],
            'description' => $sector['overview'],
            'og_image' => $sector['image'] ?? null,
            'breadcrumbs' => [
                ['label' => ['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'], 'path' => ''],
                ['label' => ['en' => 'Sectors', 'tr' => 'Sektörler', 'ar' => 'القطاعات'], 'path' => 'sectors'],
                ['label' => $sector['name'], 'path' => 'sectors/' . $slug],
            ],
        ]);
    }

    public function projects(): void
    {
        $content = content();
        $locale = app_locale();

        $this->render('pages.projects.index', [
            'projects' => $content->projects($locale),
        ], [
            'entity_type' => 'page',
            'entity_key' => 'projects',
            'path' => 'projects',
            'title' => ['en' => 'Projects', 'tr' => 'Projeler', 'ar' => 'المشاريع'],
            'description' => [
                'en' => 'Browse Ozerman Ltd project portfolio — residential, commercial, and industrial developments worldwide.',
                'tr' => 'Ozerman Ltd proje portföyüne göz atın — dünya çapında konut, ticari ve endüstriyel gelişmeler.',
                'ar' => 'تصفح محفظة مشاريع أوزرمان — تطويرات سكنية وتجارية وصناعية حول العالم.',
            ],
        ]);
    }

    public function project(string $slug): void
    {
        $content = content();
        $locale = app_locale();
        $project = $content->projectBySlug($slug, $locale);

        if (!$project) {
            $this->notFound();
            return;
        }

        $this->render('pages.projects.show', [
            'project' => $project,
        ], [
            'entity_type' => 'project',
            'entity_key' => $slug,
            'path' => 'projects/' . $slug,
            'title' => $project['title'],
            'description' => $project['description'],
            'og_image' => $project['image'] ?? null,
            'breadcrumbs' => [
                ['label' => ['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'], 'path' => ''],
                ['label' => ['en' => 'Projects', 'tr' => 'Projeler', 'ar' => 'المشاريع'], 'path' => 'projects'],
                ['label' => $project['title'], 'path' => 'projects/' . $slug],
            ],
        ]);
    }

    public function news(): void
    {
        $content = content();
        $locale = app_locale();

        $this->render('pages.news.index', [
            'articles' => $content->news($locale),
        ], [
            'entity_type' => 'page',
            'entity_key' => 'news',
            'path' => 'news',
            'title' => ['en' => 'News & Announcements', 'tr' => 'Haberler ve Duyurular', 'ar' => 'الأخبار والإعلانات'],
            'description' => [
                'en' => 'Latest news and announcements from Ozerman Ltd.',
                'tr' => 'Ozerman Ltd\'den son haberler ve duyurular.',
                'ar' => 'آخر الأخبار والإعلانات من أوزرمان المحدودة.',
            ],
        ]);
    }

    public function article(string $slug): void
    {
        $content = content();
        $locale = app_locale();
        $article = $content->newsBySlug($slug, $locale);

        if (!$article) {
            $this->notFound();
            return;
        }

        $this->render('pages.news.show', [
            'article' => $article,
            'related' => array_filter($content->news($locale), fn($a) => $a['slug'] !== $slug),
            'comments' => !empty($article['id']) ? $content->newsComments((int) $article['id']) : [],
        ], [
            'entity_type' => 'news',
            'entity_key' => $slug,
            'path' => 'news/' . $slug,
            'title' => $article['title'],
            'description' => $article['excerpt'],
            'og_type' => 'article',
            'schema_type' => 'Article',
            'published_at' => $article['published_at'] ?? null,
            'headline' => $article['title'],
            'og_image' => $article['image'] ?? null,
            'include_organization' => false,
            'breadcrumbs' => [
                ['label' => ['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'], 'path' => ''],
                ['label' => ['en' => 'News', 'tr' => 'Haberler', 'ar' => 'الأخبار'], 'path' => 'news'],
                ['label' => $article['title'], 'path' => 'news/' . $slug],
            ],
        ]);
    }

    public function gallery(): void
    {
        $content = content();
        $locale = app_locale();

        $this->render('pages.gallery', [
            'collections' => $content->gallery($locale),
        ], [
            'entity_type' => 'page',
            'entity_key' => 'gallery',
            'path' => 'gallery',
            'title' => ['en' => 'Media Gallery', 'tr' => 'Medya Galerisi', 'ar' => 'معرض الوسائط'],
            'description' => [
                'en' => 'Corporate media gallery — events, project sites, and team culture at Ozerman Ltd.',
                'tr' => 'Kurumsal medya galerisi — Ozerman Ltd\'de etkinlikler, proje sahaları ve ekip kültürü.',
                'ar' => 'معرض الوسائط المؤسسية — الفعاليات ومواقع المشاريع وثقافة الفريق في أوزرمان.',
            ],
        ]);
    }

    public function contact(): void
    {
        $success = isset($_GET['sent']);
        $content = content();
        $locale = app_locale();

        $this->render('pages.contact', [
            'offices' => $content->offices($locale),
            'success' => $success,
        ], [
            'entity_type' => 'page',
            'entity_key' => 'contact',
            'path' => 'contact',
            'title' => ['en' => 'Contact Us', 'tr' => 'İletişim', 'ar' => 'اتصل بنا'],
            'description' => [
                'en' => 'Get in touch with Ozerman Ltd — contact form, office locations, and department contacts.',
                'tr' => 'Ozerman Ltd ile iletişime geçin — iletişim formu, ofis konumları ve departman iletişim bilgileri.',
                'ar' => 'تواصل مع أوزرمان المحدودة — نموذج الاتصال ومواقع المكاتب وجهات الاتصال.',
            ],
        ]);
    }

    public function contactSubmit(): void
    {
        $visitorUuid = trim((string) ($_POST['visitor_uuid'] ?? ''));
        $privacyAccepted = isset($_POST['consent_privacy']);
        $marketingOptIn = isset($_POST['consent_marketing']);

        if (!$privacyAccepted || $visitorUuid === '') {
            redirect(url('contact') . '?error=consent');
            return;
        }

        try {
            (new EventCollector())->saveContact([
                'visitor_uuid' => $visitorUuid,
                'name' => trim((string) ($_POST['name'] ?? '')),
                'email' => trim((string) ($_POST['email'] ?? '')),
                'phone' => trim((string) ($_POST['phone'] ?? '')),
                'subject' => trim((string) ($_POST['subject'] ?? '')),
                'message' => trim((string) ($_POST['message'] ?? '')),
                'locale' => app_locale(),
                'marketing_opt_in' => $marketingOptIn,
                'analytics_opt_in' => isset($_POST['consent_analytics']),
            ]);
        } catch (\Throwable) {
            redirect(url('contact') . '?error=submit');
            return;
        }

        redirect(url('contact') . '?sent=1');
    }

    public function privacyPolicy(): void
    {
        $this->render('pages.legal.privacy-policy', [], [
            'entity_type' => 'page',
            'entity_key' => 'privacy-policy',
            'path' => 'privacy-policy',
            'title' => ['en' => 'Privacy Policy', 'tr' => 'Gizlilik Politikası', 'ar' => 'سياسة الخصوصية'],
            'description' => [
                'en' => 'Privacy Policy for Ozerman Ltd — how we collect, use, and protect your personal data.',
                'tr' => 'Ozerman Ltd Gizlilik Politikası — kişisel verilerinizi nasıl topladığımız, kullandığımız ve koruduğumuz.',
                'ar' => 'سياسة الخصوصية لأوزرمان المحدودة — كيف نجمع بياناتك الشخصية ونستخدمها ونحميها.',
            ],
        ]);
    }

    public function cookiePolicy(): void
    {
        $this->render('pages.legal.cookie-policy', [], [
            'entity_type' => 'page',
            'entity_key' => 'cookie-policy',
            'path' => 'cookie-policy',
            'title' => ['en' => 'Cookie Policy', 'tr' => 'Çerez Politikası', 'ar' => 'سياسة ملفات تعريف الارتباط'],
            'description' => [
                'en' => 'Cookie Policy for Ozerman Ltd — essential, analytics, and marketing cookies.',
                'tr' => 'Ozerman Ltd Çerez Politikası — gerekli, analitik ve pazarlama çerezleri.',
                'ar' => 'سياسة ملفات تعريف الارتباط لأوزرمان المحدودة.',
            ],
        ]);
    }

    public function cookieSettings(): void
    {
        $this->render('pages.legal.cookie-settings', [], [
            'entity_type' => 'page',
            'entity_key' => 'cookie-settings',
            'path' => 'cookie-settings',
            'title' => ['en' => 'Cookie Settings', 'tr' => 'Çerez Ayarları', 'ar' => 'إعدادات ملفات تعريف الارتباط'],
            'description' => [
                'en' => 'Manage your cookie and data collection preferences for Ozerman Ltd.',
                'tr' => 'Ozerman Ltd için çerez ve veri toplama tercihlerinizi yönetin.',
                'ar' => 'إدارة تفضيلات ملفات تعريف الارتباط وجمع البيانات.',
            ],
        ]);
    }

    public function articleReviewSubmit(string $slug): void
    {
        (new \Admin\Services\Auth\AuthService())->startSession();

        $token = $_POST['_csrf'] ?? '';
        if (!verify_csrf($token)) {
            flash('error', t(['en' => 'Invalid security token.', 'tr' => 'Geçersiz güvenlik jetonu.', 'ar' => 'رمز أمان غير صالح.']));
            redirect(url('news/' . $slug) . '#reviews');
        }

        $name = trim((string) ($_POST['author_name'] ?? ''));
        $email = trim((string) ($_POST['author_email'] ?? ''));
        $body = trim((string) ($_POST['content'] ?? ''));

        if ($name === '' || $email === '' || $body === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', t(['en' => 'Please fill in a valid name, email, and review.', 'tr' => 'Lütfen geçerli ad, e-posta ve yorum girin.', 'ar' => 'يرجى إدخال اسم وبريد ومراجعة صالحة.']));
            redirect(url('news/' . $slug) . '#reviews');
        }

        $content = content();
        $article = $content->newsBySlug($slug, app_locale());
        if (!$article || empty($article['id'])) {
            $this->notFound();
            return;
        }

        try {
            $content->addNewsComment((int) $article['id'], mb_substr($name, 0, 100), mb_substr($email, 0, 180), mb_substr($body, 0, 2000));
            flash('success', t(['en' => 'Thank you — your review was submitted and awaits moderation.', 'tr' => 'Teşekkürler — yorumunuz gönderildi ve onay bekliyor.', 'ar' => 'شكرًا — تم إرسال مراجعتك وهي بانتظار الإشراف.']));
        } catch (\Throwable) {
            flash('error', t(['en' => 'Could not save your review. Please try again.', 'tr' => 'Yorum kaydedilemedi. Lütfen tekrar deneyin.', 'ar' => 'تعذر حفظ المراجعة. حاول مرة أخرى.']));
        }

        redirect(url('news/' . $slug) . '#reviews');
    }

    public function cms(string $path): void
    {
        $page = content()->cmsPageByPath($path, app_locale());
        if ($page === null) {
            $this->notFound();
            return;
        }

        $page['php_output'] = $this->runEmbeddedPhp((string) ($page['php_embed'] ?? ''));
        $seoContext = [
            'entity_type' => 'page',
            'entity_key' => $page['slug'] ?? $path,
            'path' => $path,
            'title' => $page['meta_title'] ?: ($page['title'] ?? $path),
            'description' => $page['meta_description'] ?: ($page['excerpt'] ?? ''),
        ];

        if (($page['embed_mode'] ?? 'site') === 'blank') {
            $data = [
                'page' => $page,
                'seo' => build_seo($seoContext),
            ];
            extract($data);
            require APP_PATH . '/Views/pages/custom-blank.php';
            return;
        }

        $this->render('pages.custom', ['page' => $page], $seoContext);
    }

    private function runEmbeddedPhp(string $code): string
    {
        $code = trim($code);
        if ($code === '' || !admin_config('allow_page_php', false)) {
            return '';
        }

        $code = preg_replace('/^\s*<\?(php)?/i', '', $code) ?? $code;
        $code = preg_replace('/\?>\s*$/', '', $code) ?? $code;

        ob_start();
        try {
            eval($code);
        } catch (\Throwable) {
            echo '<!-- PHP embed error -->';
        }

        return (string) ob_get_clean();
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->render('pages.404', [], [
            'entity_type' => 'page',
            'entity_key' => '404',
            'path' => current_path(),
            'title' => '404',
            'description' => 'Page not found',
            'append_suffix' => false,
            'robots' => 'noindex, nofollow',
        ]);
    }

    private function render(string $view, array $data, array $seoContext): void
    {
        $data['seo'] = build_seo($seoContext);
        view($view, $data);
    }
}
