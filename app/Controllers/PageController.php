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
            'news' => array_reverse(array_slice($content->news($locale), 0, 3)),
        ], [
            'entity_type' => 'page',
            'entity_key' => 'home',
            'path' => '',
            'title' => ['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'],
            'description' => [
                'en' => 'Özerman Ticaret — a distribution and trading company in Cyprus connecting brands with quality retail service.',
                'tr' => 'Özerman Ticaret — Kıbrıs’ta markaları nitelikli perakende hizmetleriyle buluşturan distribütörlük ve ticaret şirketi.',
                'ar' => 'أوزرمان للتجارة — شركة توزيع وتجارة في قبرص تجمع العلامات مع خدمات تجزئة نوعية.',
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
                'en' => 'Özerman Ticaret was founded in 2020 in Cyprus — our story, vision, and mission.',
                'tr' => 'Özerman Ticaret 2020 yılında Kıbrıs’ta kurulmuştur — hikâyemiz, vizyonumuz ve misyonumuz.',
                'ar' => 'تأسست أوزرمان للتجارة عام 2020 في قبرص — قصتنا ورؤيتنا ومهمتنا.',
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
                'en' => 'Visit our Nicosia and Famagusta stores. Free delivery and installation across Cyprus.',
                'tr' => 'Lefkoşa ve Gazimağusa mağazalarımızı ziyaret edin. Ada genelinde ücretsiz teslimat ve kurulum.',
                'ar' => 'زوروا متاجرنا في نيقوسيا وفاماغوستا. توصيل وتركيب مجاني في قبرص.',
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
            'title' => ['en' => 'Our Brands', 'tr' => 'Markalarımız', 'ar' => 'علاماتنا'],
            'description' => [
                'en' => 'Our own brand Daphne Baby together with Lajivert and Aymini distributorships in Cyprus.',
                'tr' => 'Kendi markamız Daphne Baby ile Lajivert ve Aymini Kıbrıs distribütörlükleri.',
                'ar' => 'علامتنا دافني بيبي إلى جانب وكالات لاجيڤيرت وأيميني في قبرص.',
            ],
            'breadcrumbs' => [
                ['label' => ['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'], 'path' => ''],
                ['label' => ['en' => 'Our Brands', 'tr' => 'Markalarımız', 'ar' => 'علاماتنا'], 'path' => 'partnerships'],
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
            'title' => ['en' => 'Areas of Work', 'tr' => 'Çalışma Alanlarımız', 'ar' => 'مجالات عملنا'],
            'description' => [
                'en' => 'Distribution, brand partnerships, and retail after-sales service from Özerman Ticaret in Cyprus.',
                'tr' => 'Özerman Ticaret’in Kıbrıs’taki distribütörlük, marka iş birlikleri ve satış sonrası perakende hizmeti.',
                'ar' => 'التوزيع وتعاونات العلامات وخدمة التجزئة بعد البيع من أوزرمان للتجارة في قبرص.',
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
            fn($p) => !empty($p['is_featured'])
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
            'title' => ['en' => 'Our Work', 'tr' => 'Çalışmalarımız', 'ar' => 'أعمالنا'],
            'description' => [
                'en' => 'Özerman Ticaret focuses on brands, stores, and distribution in Cyprus.',
                'tr' => 'Özerman Ticaret, Kıbrıs’ta markalar, mağazalar ve distribütörlük üzerine çalışır.',
                'ar' => 'تركّز أوزرمان للتجارة على العلامات والمتاجر والتوزيع في قبرص.',
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
            'title' => ['en' => 'Corporate', 'tr' => 'Kurumsal', 'ar' => 'المؤسسي'],
            'description' => [
                'en' => 'Milestones of Özerman Ticaret, from Bebecik Online and Daphne Baby to the company founded in 2020.',
                'tr' => 'Bebecik Online ve Daphne Baby’den 2020’de kurulan Özerman Ticaret’e kilometre taşlarımız.',
                'ar' => 'محطات أوزرمان للتجارة، من بيبيسيك أونلاين ودافني بيبي إلى الشركة التي تأسست عام 2020.',
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
                ['label' => ['en' => 'Corporate', 'tr' => 'Kurumsal', 'ar' => 'المؤسسي'], 'path' => 'news'],
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
                'en' => 'Photos from Özerman Ticaret stores and the brands we represent in Cyprus.',
                'tr' => 'Özerman Ticaret mağazalarından ve Kıbrıs’ta temsil ettiğimiz markalardan görüntüler.',
                'ar' => 'صور من متاجر أوزرمان للتجارة والعلامات التي نمثلها في قبرص.',
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
                'en' => 'Contact Özerman Ticaret to discuss brand representation and commercial partnerships in Cyprus.',
                'tr' => 'Marka temsilciliği ve ticari iş birlikleri için Özerman Ticaret ile iletişime geçin.',
                'ar' => 'تواصلوا مع أوزرمان للتجارة لمناقشة تمثيل العلامات والتعاونات التجارية في قبرص.',
            ],
        ]);
    }

    public function contactSubmit(): void
    {
        if (!verify_csrf((string) ($_POST['_csrf'] ?? ''))) {
            redirect(url('contact') . '?error=consent');
            return;
        }

        if (trim((string) ($_POST['company_website'] ?? '')) !== '') {
            redirect(url('contact') . '?sent=1');
            return;
        }

        $limiter = \Admin\Support\RateLimiter::forAdmin();
        $rateKey = 'contact:' . (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        if ($limiter->tooManyAttempts($rateKey, 5, 900)) {
            redirect(url('contact') . '?error=submit');
            return;
        }
        $limiter->hit($rateKey, 900);

        $visitorUuid = trim((string) ($_POST['visitor_uuid'] ?? ''));
        $privacyAccepted = isset($_POST['consent_privacy']);
        $marketingOptIn = isset($_POST['consent_marketing']);
        $name = mb_substr(trim((string) ($_POST['name'] ?? '')), 0, 120);
        $email = mb_substr(trim((string) ($_POST['email'] ?? '')), 0, 180);
        $phone = mb_substr(trim((string) ($_POST['phone'] ?? '')), 0, 40);
        $subject = mb_substr(trim((string) ($_POST['subject'] ?? '')), 0, 160);
        $message = mb_substr(trim((string) ($_POST['message'] ?? '')), 0, 4000);

        if (!$privacyAccepted || $visitorUuid === '' || $name === '' || $message === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            redirect(url('contact') . '?error=consent');
            return;
        }

        try {
            (new EventCollector())->saveContact([
                'visitor_uuid' => $visitorUuid,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message,
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
                'en' => 'Privacy Policy for Özerman Ticaret — how we collect, use, and protect your personal data.',
                'tr' => 'Özerman Ticaret Gizlilik Politikası — kişisel verilerinizi nasıl topladığımız, kullandığımız ve koruduğumuz.',
                'ar' => 'سياسة الخصوصية لأوزرمان للتجارة — كيف نجمع بياناتك الشخصية ونستخدمها ونحميها.',
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
                'en' => 'Cookie Policy for Özerman Ticaret — essential, analytics, and marketing cookies.',
                'tr' => 'Özerman Ticaret Çerez Politikası — gerekli, analitik ve pazarlama çerezleri.',
                'ar' => 'سياسة ملفات تعريف الارتباط لأوزرمان للتجارة.',
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
                'en' => 'Manage your cookie and data collection preferences for Özerman Ticaret.',
                'tr' => 'Özerman Ticaret için çerez ve veri toplama tercihlerinizi yönetin.',
                'ar' => 'إدارة تفضيلات ملفات تعريف الارتباط وجمع البيانات لأوزرمان للتجارة.',
            ],
        ]);
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
