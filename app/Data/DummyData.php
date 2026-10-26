<?php

declare(strict_types=1);

namespace App\Data;

class DummyData
{
    public static function nav(): array
    {
        return [
            ['label' => ['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'], 'url' => ''],
            ['label' => ['en' => 'About Us', 'tr' => 'Hakkımızda', 'ar' => 'من نحن'], 'url' => 'about-us'],
            ['label' => ['en' => 'Our Stores', 'tr' => 'Mağazalarımız', 'ar' => 'متاجرنا'], 'url' => 'our-stores'],
            ['label' => ['en' => 'Our Brands', 'tr' => 'Markalarımız', 'ar' => 'علاماتنا'], 'url' => 'partnerships'],
            ['label' => ['en' => 'Corporate', 'tr' => 'Kurumsal', 'ar' => 'المؤسسي'], 'url' => 'news'],
            ['label' => ['en' => 'Contact', 'tr' => 'İletişim', 'ar' => 'اتصل بنا'], 'url' => 'contact'],
        ];
    }

    public static function footerMenus(): array
    {
        return [
            'footer_col1' => [
                'title' => ['en' => 'Quick Links', 'tr' => 'Hızlı Bağlantılar', 'ar' => 'روابط سريعة'],
                'links' => [
                    ['label' => ['en' => 'About Us', 'tr' => 'Hakkımızda', 'ar' => 'من نحن'], 'url' => 'about-us', 'target' => '_self'],
                    ['label' => ['en' => 'Our Stores', 'tr' => 'Mağazalarımız', 'ar' => 'متاجرنا'], 'url' => 'our-stores', 'target' => '_self'],
                    ['label' => ['en' => 'Our Brands', 'tr' => 'Markalarımız', 'ar' => 'علاماتنا'], 'url' => 'partnerships', 'target' => '_self'],
                    ['label' => ['en' => 'Corporate', 'tr' => 'Kurumsal', 'ar' => 'المؤسسي'], 'url' => 'news', 'target' => '_self'],
                ],
            ],
            'footer_col2' => [
                'title' => ['en' => 'Explore', 'tr' => 'Keşfedin', 'ar' => 'استكشف'],
                'links' => [
                    ['label' => ['en' => 'Our Brand Portfolio', 'tr' => 'Marka Portföyümüz', 'ar' => 'محفظة العلامات'], 'url' => 'partnerships', 'target' => '_self'],
                    ['label' => ['en' => 'Stores', 'tr' => 'Mağazalar', 'ar' => 'المتاجر'], 'url' => 'our-stores', 'target' => '_self'],
                    ['label' => ['en' => 'Partnership Inquiries', 'tr' => 'İş Birliği Talepleri', 'ar' => 'طلبات التعاون'], 'url' => 'contact', 'target' => '_self'],
                ],
            ],
            'footer_col3' => [
                'title' => ['en' => 'Legal', 'tr' => 'Yasal', 'ar' => 'قانوني'],
                'links' => [
                    ['label' => ['en' => 'Privacy Policy', 'tr' => 'Gizlilik Politikası', 'ar' => 'سياسة الخصوصية'], 'url' => 'privacy-policy', 'target' => '_self'],
                    ['label' => ['en' => 'Cookie Policy', 'tr' => 'Çerez Politikası', 'ar' => 'سياسة ملفات تعريف الارتباط'], 'url' => 'cookie-policy', 'target' => '_self'],
                    ['label' => ['en' => 'Cookie Settings', 'tr' => 'Çerez Ayarları', 'ar' => 'إعدادات ملفات تعريف الارتباط'], 'url' => 'cookie-settings', 'target' => '_self'],
                ],
            ],
        ];
    }

    public static function heroSlides(): array
    {
        return [
            [
                'title' => [
                    'en' => 'Distinguished brands. A strong local structure.',
                    'tr' => 'Seçkin markalar. Güçlü bir yerel yapı.',
                    'ar' => 'علامات مميزة. بنية محلية قوية.',
                ],
                'subtitle' => [
                    'en' => 'Özerman Ticaret is a distribution and trading company in Cyprus that connects brands with quality retail service.',
                    'tr' => 'Özerman Ticaret, Kıbrıs’ta markaları nitelikli perakende hizmetleriyle buluşturan bir distribütörlük ve ticaret şirketidir.',
                    'ar' => 'أوزرمان للتجارة شركة توزيع وتجارة في قبرص تجمع العلامات مع خدمات تجزئة نوعية.',
                ],
                'cta_text' => ['en' => 'Contact Us', 'tr' => 'Bize Ulaşın', 'ar' => 'تواصلوا معنا'],
                'cta_url' => 'contact',
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1600&q=80',
            ],
            [
                'title' => [
                    'en' => 'Our Brands and Partnerships',
                    'tr' => 'Markalarımız ve İş Birliklerimiz',
                    'ar' => 'علاماتنا وتعاوناتنا',
                ],
                'subtitle' => [
                    'en' => 'We develop our own brand Daphne Baby together with our Lajivert and Aymini distributorships within the same corporate structure.',
                    'tr' => 'Kendi markamız Daphne Baby ile Lajivert ve Aymini distribütörlüklerimizi aynı kurumsal yapı içinde geliştiriyoruz.',
                    'ar' => 'نطوّر علامتنا الخاصة دافني بيبي إلى جانب وكالات لاجيڤيرت وأيميني ضمن الهيكل المؤسسي نفسه.',
                ],
                'cta_text' => ['en' => 'Discover Our Brands', 'tr' => 'Markalarımızı Keşfedin', 'ar' => 'اكتشفوا علاماتنا'],
                'cta_url' => 'partnerships',
                'image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1600&q=80',
            ],
            [
                'title' => [
                    'en' => 'We are with you in Cyprus',
                    'tr' => 'Kıbrıs’ta Yanınızdayız',
                    'ar' => 'نحن معكم في قبرص',
                ],
                'subtitle' => [
                    'en' => 'Visit our stores to see products in person and get support from our specialist team. We offer free delivery and installation across the island.',
                    'tr' => 'Mağazalarımızda ürünleri yakından inceleyebilir, uzman ekibimizden destek alabilirsiniz. Ada genelinde ücretsiz teslimat ve kurulum sunuyoruz.',
                    'ar' => 'يمكنكم معاينة المنتجات في متاجرنا والحصول على دعم فريقنا المختص. نقدّم التوصيل والتركيب مجانًا في جميع أنحاء الجزيرة.',
                ],
                'cta_text' => ['en' => 'Our Stores', 'tr' => 'Mağazalarımız', 'ar' => 'متاجرنا'],
                'cta_url' => 'our-stores',
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&q=80',
            ],
        ];
    }

    public static function stats(): array
    {
        return [
            ['value' => '2020', 'label' => ['en' => 'Year Founded', 'tr' => 'Kuruluş Yılı', 'ar' => 'سنة التأسيس']],
            ['value' => '3.000+', 'label' => ['en' => 'Customers Reached', 'tr' => 'Ulaşılan Müşteri', 'ar' => 'عملاء تم الوصول إليهم']],
            ['value' => '2', 'label' => ['en' => 'Store Count', 'tr' => 'Mağaza Sayısı', 'ar' => 'عدد المتاجر']],
            ['value' => '10', 'label' => ['en' => 'Team Members', 'tr' => 'Ekip Üyesi', 'ar' => 'أعضاء الفريق']],
        ];
    }

    public static function operations(): array
    {
        return [
            [
                'title' => ['en' => 'Distribution & Trade', 'tr' => 'Distribütörlük ve Ticaret', 'ar' => 'التوزيع والتجارة'],
                'description' => [
                    'en' => 'We connect the brands we select with the Cyprus market and coordinate product supply and commercial processes.',
                    'tr' => 'Seçtiğimiz markaları Kıbrıs pazarıyla buluşturuyor, ürün tedariği ve ticari süreçleri koordineli biçimde yürütüyoruz.',
                    'ar' => 'نجمع العلامات التي نختارها مع سوق قبرص وننسّق توريد المنتجات والعمليات التجارية.',
                ],
            ],
            [
                'title' => ['en' => 'Brand Partnerships', 'tr' => 'Marka İş Birlikleri', 'ar' => 'تعاونات العلامات'],
                'description' => [
                    'en' => 'We support brand positioning and local-market growth with a long-term partnership approach.',
                    'tr' => 'Markaların konumlandırmasını ve yerel pazardaki gelişimini uzun vadeli iş ortaklığı anlayışıyla destekliyoruz.',
                    'ar' => 'ندعم تموضع العلامات ونموها في السوق المحلي بمنهج شراكة طويل الأمد.',
                ],
            ],
            [
                'title' => ['en' => 'Retail & After-Sales Service', 'tr' => 'Perakende ve Satış Sonrası Hizmet', 'ar' => 'التجزئة وخدمة ما بعد البيع'],
                'description' => [
                    'en' => 'We provide store retail, product consultancy, free island-wide delivery and installation, plus warranty and spare-parts support.',
                    'tr' => 'Mağazacılık, ürün danışmanlığı, ücretsiz ada geneli teslimat ve kurulum ile garanti ve yedek parça desteği sunuyoruz.',
                    'ar' => 'نقدّم البيع بالتجزئة واستشارات المنتج والتوصيل والتركيب المجاني في الجزيرة ودعم الضمان وقطع الغيار.',
                ],
            ],
        ];
    }

    public static function stores(): array
    {
        return [
            [
                'slug' => 'lefkosa-store',
                'name' => ['en' => 'Nicosia Store', 'tr' => 'Lefkoşa Mağazası', 'ar' => 'متجر نيقوسيا'],
                'city' => ['en' => 'Cyprus', 'tr' => 'Kıbrıs', 'ar' => 'قبرص'],
                'address' => [
                    'en' => 'Current address and visiting details will be added.',
                    'tr' => 'Güncel adres ve ziyaret bilgileri eklenecek.',
                    'ar' => 'سيتم إضافة العنوان الحالي وتفاصيل الزيارة.',
                ],
                'phone' => '',
                'email' => 'info@ozermanltd.com',
                'hours' => ['en' => 'Visiting hours will be added.', 'tr' => 'Ziyaret saatleri eklenecek.', 'ar' => 'ستُضاف ساعات الزيارة.'],
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&q=80',
            ],
            [
                'slug' => 'gazimagusa-store',
                'name' => ['en' => 'Famagusta Store', 'tr' => 'Gazimağusa Mağazası', 'ar' => 'متجر فاماغوستا'],
                'city' => ['en' => 'Cyprus', 'tr' => 'Kıbrıs', 'ar' => 'قبرص'],
                'address' => [
                    'en' => 'Current address and visiting details will be added.',
                    'tr' => 'Güncel adres ve ziyaret bilgileri eklenecek.',
                    'ar' => 'سيتم إضافة العنوان الحالي وتفاصيل الزيارة.',
                ],
                'phone' => '',
                'email' => 'info@ozermanltd.com',
                'hours' => ['en' => 'Visiting hours will be added.', 'tr' => 'Ziyaret saatleri eklenecek.', 'ar' => 'ستُضاف ساعات الزيارة.'],
                'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80',
            ],
        ];
    }

    public static function partnerships(): array
    {
        return [
            [
                'slug' => 'lajivert',
                'name' => 'Lajivert',
                'tagline' => [
                    'en' => 'Baby, kids and youth rooms',
                    'tr' => 'Bebek, Çocuk ve Genç Odaları',
                    'ar' => 'غرف الرضع والأطفال والشباب',
                ],
                'description' => [
                    'en' => 'As Lajivert’s Cyprus distributor, we bring collections for children’s and youth living spaces to our customers.',
                    'tr' => 'Lajivert’in Kıbrıs distribütörlüğünü üstlenerek çocuk ve genç yaşam alanlarına yönelik koleksiyonları müşterilerimizle buluşturuyoruz.',
                    'ar' => 'بصفتنا موزّع لاجيڤيرت في قبرص، نقدّم مجموعات مساحات معيشة الأطفال والشباب لعملائنا.',
                ],
                'url' => 'https://www.lajivert.com.tr',
                'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&q=80',
            ],
            [
                'slug' => 'aymini',
                'name' => 'Aymini',
                'tagline' => [
                    'en' => 'Baby furniture',
                    'tr' => 'Bebek Mobilyaları',
                    'ar' => 'أثاث الرضع',
                ],
                'description' => [
                    'en' => 'Through the Aymini Cyprus distributorship, we bring baby-room furniture to the local market.',
                    'tr' => 'Aymini markasının Kıbrıs distribütörlüğüyle bebek odası mobilyalarını yerel pazara sunuyoruz.',
                    'ar' => 'من خلال وكالة أيميني في قبرص نقدّم أثاث غرف الرضع للسوق المحلي.',
                ],
                'url' => 'https://www.aymini.com',
                'image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&q=80',
            ],
            [
                'slug' => 'daphne-baby',
                'name' => 'Daphne Baby',
                'tagline' => [
                    'en' => 'Özerman Ticaret’s own brand',
                    'tr' => 'Özerman Ticaret\'in kendi markası',
                    'ar' => 'العلامة الخاصة بأوزرمان للتجارة',
                ],
                'description' => [
                    'en' => 'The original brand of our retail journey in baby products.',
                    'tr' => 'Bebek ürünleri alanında başlayan perakende deneyimimizin özgün markası.',
                    'ar' => 'العلامة الأصيلة لتجربتنا في تجزئة منتجات الرضع.',
                ],
                'url' => '',
                'image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&q=80',
            ],
        ];
    }

    public static function sectors(): array
    {
        return [
            [
                'slug' => 'distribution-trade',
                'icon' => 'globe',
                'color' => '#EA4C37',
                'name' => ['en' => 'Distribution & Trade', 'tr' => 'Distribütörlük ve Ticaret', 'ar' => 'التوزيع والتجارة'],
                'overview' => [
                    'en' => 'We connect the brands we select with the Cyprus market and coordinate product supply and commercial processes.',
                    'tr' => 'Seçtiğimiz markaları Kıbrıs pazarıyla buluşturuyor, ürün tedariği ve ticari süreçleri koordineli biçimde yürütüyoruz.',
                    'ar' => 'نجمع العلامات التي نختارها مع سوق قبرص وننسّق توريد المنتجات والعمليات التجارية.',
                ],
                'services' => [
                    'en' => "• Brand distribution in Cyprus\n• Product supply coordination\n• Commercial process management\n• Local market representation",
                    'tr' => "• Kıbrıs’ta marka distribütörlüğü\n• Ürün tedarik koordinasyonu\n• Ticari süreç yönetimi\n• Yerel pazar temsilciliği",
                    'ar' => "• توزيع العلامات في قبرص\n• تنسيق توريد المنتجات\n• إدارة العمليات التجارية\n• التمثيل في السوق المحلي",
                ],
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&q=80',
            ],
            [
                'slug' => 'brand-partnerships',
                'icon' => 'handshake',
                'color' => '#3A3A3C',
                'name' => ['en' => 'Brand Partnerships', 'tr' => 'Marka İş Birlikleri', 'ar' => 'تعاونات العلامات'],
                'overview' => [
                    'en' => 'We support brand positioning and local-market growth with a long-term partnership approach.',
                    'tr' => 'Markaların konumlandırmasını ve yerel pazardaki gelişimini uzun vadeli iş ortaklığı anlayışıyla destekliyoruz.',
                    'ar' => 'ندعم تموضع العلامات ونموها في السوق المحلي بمنهج شراكة طويل الأمد.',
                ],
                'services' => [
                    'en' => "• Long-term brand representation\n• Local positioning\n• Portfolio development\n• Partner communication",
                    'tr' => "• Uzun vadeli marka temsilciliği\n• Yerel konumlandırma\n• Portföy geliştirme\n• İş ortağı iletişimi",
                    'ar' => "• تمثيل طويل الأمد للعلامات\n• التموضع المحلي\n• تطوير المحفظة\n• التواصل مع الشركاء",
                ],
                'image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=800&q=80',
            ],
            [
                'slug' => 'retail-after-sales',
                'icon' => 'store',
                'color' => '#EA4C37',
                'name' => ['en' => 'Retail & After-Sales Service', 'tr' => 'Perakende ve Satış Sonrası Hizmet', 'ar' => 'التجزئة وخدمة ما بعد البيع'],
                'overview' => [
                    'en' => 'We provide store retail, product consultancy, free island-wide delivery and installation, plus warranty and spare-parts support.',
                    'tr' => 'Mağazacılık, ürün danışmanlığı, ücretsiz ada geneli teslimat ve kurulum ile garanti ve yedek parça desteği sunuyoruz.',
                    'ar' => 'نقدّم البيع بالتجزئة واستشارات المنتج والتوصيل والتركيب المجاني في الجزيرة ودعم الضمان وقطع الغيار.',
                ],
                'services' => [
                    'en' => "• Store retail in Nicosia and Famagusta\n• Product consultancy\n• Free island-wide delivery and installation\n• Warranty and spare-parts support",
                    'tr' => "• Lefkoşa ve Gazimağusa mağazacılığı\n• Ürün danışmanlığı\n• Ücretsiz ada geneli teslimat ve kurulum\n• Garanti ve yedek parça desteği",
                    'ar' => "• البيع في متاجر نيقوسيا وفاماغوستا\n• استشارات المنتج\n• توصيل وتركيب مجاني في الجزيرة\n• دعم الضمان وقطع الغيار",
                ],
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&q=80',
            ],
        ];
    }

    public static function projects(): array
    {
        return [];
    }

    public static function news(): array
    {
        return [
            [
                'slug' => 'bebecik-online',
                'category' => ['en' => 'First step', 'tr' => 'İlk Adım', 'ar' => 'الخطوة الأولى'],
                'publish_date' => '2016-01-01',
                'is_featured' => true,
                'title' => [
                    'en' => 'Bebecik Online',
                    'tr' => 'Bebecik Online',
                    'ar' => 'بيبيسيك أونلاين',
                ],
                'excerpt' => [
                    'en' => 'The entrepreneurial journey that began with online sales in baby products.',
                    'tr' => 'Bebek ürünleri alanında çevrim içi satışla başlayan girişimcilik yolculuğu.',
                    'ar' => 'رحلة ريادية بدأت بالبيع عبر الإنترنت في مجال منتجات الرضع.',
                ],
                'content' => [
                    'en' => '<p>Bebecik Online is the first step of the journey that later became Özerman Ticaret. It started with online sales in baby products and built the retail experience behind today’s stores and distributorships.</p>',
                    'tr' => '<p>Bebecik Online, bugün Özerman Ticaret’e dönüşen yolculuğun ilk adımıdır. Bebek ürünleri alanında çevrim içi satışla başlamış, bugünkü mağazaların ve distribütörlüklerin dayandığı perakende deneyimini oluşturmuştur.</p>',
                    'ar' => '<p>بيبيسيك أونلاين هي الخطوة الأولى في الرحلة التي أصبحت أوزرمان للتجارة. بدأت بالبيع عبر الإنترنت في منتجات الرضع، وشكّلت تجربة التجزئة التي تقوم عليها المتاجر والوكالات اليوم.</p>',
                ],
                'image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&q=80',
            ],
            [
                'slug' => 'daphne-baby-2018',
                'category' => ['en' => '2018', 'tr' => '2018', 'ar' => '2018'],
                'publish_date' => '2018-01-01',
                'is_featured' => true,
                'title' => [
                    'en' => 'Daphne Baby',
                    'tr' => 'Daphne Baby',
                    'ar' => 'دافني بيبي',
                ],
                'excerpt' => [
                    'en' => 'Daphne Baby opened its store in Famagusta.',
                    'tr' => 'Gazimağusa’da Daphne Baby mağazasının açılması.',
                    'ar' => 'افتتاح متجر دافني بيبي في فاماغوستا.',
                ],
                'content' => [
                    'en' => '<p>In 2018 Daphne Baby opened in Famagusta. The store turned an online beginning into a physical retail presence and became the company’s own brand in baby products.</p>',
                    'tr' => '<p>2018 yılında Daphne Baby, Gazimağusa’da açıldı. Mağaza, çevrim içi başlangıcı fiziksel perakendeye taşıdı ve bebek ürünlerinde şirketin kendi markası haline geldi.</p>',
                    'ar' => '<p>في عام 2018 افتُتحت دافني بيبي في فاماغوستا. نقل المتجر البداية الإلكترونية إلى حضور تجزئة فعلي، وأصبحت العلامة الخاصة بالشركة في منتجات الرضع.</p>',
                ],
                'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80',
            ],
            [
                'slug' => 'ozerman-ticaret-2020',
                'category' => ['en' => '2020', 'tr' => '2020', 'ar' => '2020'],
                'publish_date' => '2020-01-01',
                'is_featured' => true,
                'title' => [
                    'en' => 'Özerman Ticaret',
                    'tr' => 'Özerman Ticaret',
                    'ar' => 'أوزرمان للتجارة',
                ],
                'excerpt' => [
                    'en' => 'Retail experience became a corporate trading and distribution structure.',
                    'tr' => 'Perakende deneyiminin kurumsal ticaret ve distribütörlük yapısına dönüşmesi.',
                    'ar' => 'تحوّل تجربة التجزئة إلى هيكل تجارة وتوزيع مؤسسي.',
                ],
                'content' => [
                    'en' => '<p>Özerman Ticaret was founded in 2020 by Emin Özerman in Cyprus. The company now runs Daphne Baby together with the Cyprus distributorships of Lajivert and Aymini.</p>',
                    'tr' => '<p>Özerman Ticaret, 2020 yılında Emin Özerman tarafından Kıbrıs’ta kuruldu. Şirket bugün kendi markası Daphne Baby ile Lajivert ve Aymini markalarının Kıbrıs distribütörlüklerini yürütür.</p>',
                    'ar' => '<p>تأسست أوزرمان للتجارة عام 2020 على يد أمين أوزرمان في قبرص. تدير الشركة اليوم دافني بيبي إلى جانب وكالات لاجيڤيرت وأيميني في قبرص.</p>',
                ],
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&q=80',
            ],
        ];
    }

    public static function team(): array
    {
        return [
            [
                'name' => ['en' => 'Emin Özerman', 'tr' => 'Emin Özerman', 'ar' => 'أمين أوزرمان'],
                'position' => ['en' => 'Founder', 'tr' => 'Kurucu', 'ar' => 'المؤسس'],
                'bio' => [
                    'en' => 'Founded Özerman Ticaret in Cyprus in 2020, building on the Daphne Baby retail journey that began in Famagusta in 2018.',
                    'tr' => 'Özerman Ticaret’i 2020 yılında Kıbrıs’ta kurdu. 2018’de Gazimağusa’da başlayan Daphne Baby perakende yolculuğunu kurumsal ticaret ve distribütörlük yapısına taşıdı.',
                    'ar' => 'أسس أوزرمان للتجارة في قبرص عام 2020، انطلاقًا من تجربة دافني بيبي للتجزئة التي بدأت في فاماغوستا عام 2018.',
                ],
                'type' => 'founder',
                'image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&q=80',
            ],
        ];
    }

    public static function values(): array
    {
        return [
            [
                'title' => ['en' => 'Care', 'tr' => 'Özen', 'ar' => 'العناية'],
                'description' => [
                    'en' => 'We are careful in the products and services we choose, and we take responsibility at every stage of the shopping experience.',
                    'tr' => 'Ürün ve hizmet seçiminde titizlik gösterir, alışverişin her aşamasında sorumluluk üstleniriz.',
                    'ar' => 'نختار المنتجات والخدمات بعناية ونتحمّل المسؤولية في كل مرحلة من تجربة التسوق.',
                ],
                'icon' => 'shield',
            ],
            [
                'title' => ['en' => 'Partnership', 'tr' => 'İş Birliği', 'ar' => 'الشراكة'],
                'description' => [
                    'en' => 'We protect the values of the brands we represent and build stable, transparent relationships with partners and customers.',
                    'tr' => 'Temsil ettiğimiz markaların değerlerini korur, iş ortaklarımız ve müşterilerimizle istikrarlı ve şeffaf ilişkiler kurarız.',
                    'ar' => 'نحمي قيم العلامات التي نمثلها ونبني علاقات مستقرة وشفافة مع الشركاء والعملاء.',
                ],
                'icon' => 'handshake',
            ],
            [
                'title' => ['en' => 'Service', 'tr' => 'Hizmet', 'ar' => 'الخدمة'],
                'description' => [
                    'en' => 'Product consultancy, free island-wide delivery and installation, warranty work, and spare parts are handled together.',
                    'tr' => 'Ürün danışmanlığı, ücretsiz ada geneli teslimat ve kurulum, garanti işlemleri ve yedek parça desteğini birlikte yürütürüz.',
                    'ar' => 'نقدّم استشارات المنتج والتوصيل والتركيب المجاني في الجزيرة وإجراءات الضمان وقطع الغيار معًا.',
                ],
                'icon' => 'star',
            ],
            [
                'title' => ['en' => 'Local focus', 'tr' => 'Yerel Odak', 'ar' => 'التركيز المحلي'],
                'description' => [
                    'en' => 'We work in Cyprus with a long-term view, from our stores in Nicosia and Famagusta.',
                    'tr' => 'Lefkoşa ve Gazimağusa mağazalarımızla Kıbrıs’ta uzun vadeli bir bakışla çalışırız.',
                    'ar' => 'نعمل في قبرص بنظرة طويلة الأمد، من متاجرنا في نيقوسيا وفاماغوستا.',
                ],
                'icon' => 'pin',
            ],
        ];
    }

    public static function gallery(): array
    {
        return [
            [
                'slug' => 'our-stores',
                'title' => ['en' => 'Our Stores', 'tr' => 'Mağazalarımız', 'ar' => 'متاجرنا'],
                'description' => [
                    'en' => 'Moments from our Nicosia and Famagusta stores.',
                    'tr' => 'Lefkoşa ve Gazimağusa mağazalarımızdan kareler.',
                    'ar' => 'لحظات من متاجرنا في نيقوسيا وفاماغوستا.',
                ],
                'cover' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&q=80',
                'items' => [
                    ['image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&q=80', 'caption' => ['en' => 'Nicosia store', 'tr' => 'Lefkoşa mağazası', 'ar' => 'متجر نيقوسيا']],
                    ['image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&q=80', 'caption' => ['en' => 'Famagusta store', 'tr' => 'Gazimağusa mağazası', 'ar' => 'متجر فاماغوستا']],
                ],
            ],
            [
                'slug' => 'our-brands',
                'title' => ['en' => 'Our Brands', 'tr' => 'Markalarımız', 'ar' => 'علاماتنا'],
                'description' => [
                    'en' => 'Daphne Baby, Lajivert, and Aymini in our retail spaces.',
                    'tr' => 'Perakende alanlarımızda Daphne Baby, Lajivert ve Aymini.',
                    'ar' => 'دافني بيبي ولاجيڤيرت وأيميني في مساحات التجزئة لدينا.',
                ],
                'cover' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&q=80',
                'items' => [
                    ['image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&q=80', 'caption' => ['en' => 'Lajivert collections', 'tr' => 'Lajivert koleksiyonları', 'ar' => 'مجموعات لاجيڤيرت']],
                    ['image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&q=80', 'caption' => ['en' => 'Baby room collections', 'tr' => 'Bebek odası koleksiyonları', 'ar' => 'مجموعات غرف الرضع']],
                ],
            ],
            [
                'slug' => 'corporate',
                'title' => ['en' => 'Corporate', 'tr' => 'Kurumsal', 'ar' => 'مؤسسي'],
                'description' => [
                    'en' => 'Our team, operations, and working culture in Cyprus.',
                    'tr' => 'Kıbrıs’taki ekibimiz, operasyonlarımız ve çalışma kültürümüz.',
                    'ar' => 'فريقنا وعملياتنا وثقافة العمل في قبرص.',
                ],
                'cover' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&q=80',
                'items' => [
                    ['image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&q=80', 'caption' => ['en' => 'Our team', 'tr' => 'Ekibimiz', 'ar' => 'فريقنا']],
                ],
            ],
        ];
    }

    public static function offices(): array
    {
        return [
            [
                'label' => ['en' => 'Head Office', 'tr' => 'Genel Merkez', 'ar' => 'المقر الرئيسي'],
                'address' => ['en' => 'Cyprus', 'tr' => 'Kıbrıs', 'ar' => 'قبرص'],
                'city' => 'Nicosia',
                'country' => ['en' => 'Cyprus', 'tr' => 'Kıbrıs', 'ar' => 'قبرص'],
                'phone' => '',
                'email' => 'info@ozermanltd.com',
                'hours' => ['en' => 'Visiting hours will be added.', 'tr' => 'Ziyaret saatleri eklenecek.', 'ar' => 'ستُضاف ساعات الزيارة.'],
                'is_headquarters' => true,
            ],
        ];
    }

    public static function aboutContent(): array
    {
        return [
            'history' => [
                'en' => '<p>Özerman Ticaret was founded in 2020 by Emin Özerman in Cyprus. The entrepreneurial journey that began with Bebecik Online and Daphne Baby, opened in Famagusta in 2018, forms the foundation of today’s retail and distribution structure. Today we operate our own brand Daphne Baby together with the Cyprus distributorships of Lajivert and Aymini.</p>',
                'tr' => '<p>Özerman Ticaret, 2020 yılında Emin Özerman tarafından Kıbrıs’ta kurulmuştur. Bebecik Online ile başlayan girişimcilik yolculuğu ve 2018 yılında Gazimağusa’da hayata geçirilen Daphne Baby, şirketin bugünkü perakende ve distribütörlük yapısının temelini oluşturur. Bugün kendi markamız Daphne Baby ile Lajivert ve Aymini markalarının Kıbrıs distribütörlüklerini yürütüyoruz.</p>',
                'ar' => '<p>تأسست أوزرمان للتجارة عام 2020 على يد أمين أوزرمان في قبرص. بدأت الرحلة الريادية مع بيبيسيك أونلاين ومع دافني بيبي التي افتُتحت في فاماغوستا عام 2018، وهما أساس هيكل التجزئة والتوزيع اليوم. ندير اليوم علامتنا دافني بيبي إلى جانب وكالات لاجيڤيرت وأيميني في قبرص.</p>',
            ],
            'vision' => [
                'en' => '<p>To be one of the reference companies in retail and distribution in Cyprus, distinguished by a strong brand portfolio and corporate structure.</p><p>To carry the partnerships we build with national and international brands into different sectors and categories, support the growth of the brands we represent in the island market, and raise retail service standards in Cyprus.</p>',
                'tr' => '<p>Kıbrıs’ta perakende ve distribütörlük alanında referans gösterilen, güçlü marka portföyü ve kurumsal yapısıyla öne çıkan şirketlerden biri olmak.</p><p>Ulusal ve uluslararası markalarla geliştirdiğimiz iş birliklerini farklı sektör ve kategorilere taşıyarak, temsil ettiğimiz markaların ada pazarındaki gelişimine katkı sağlamak ve Kıbrıs’ta perakendeciliğin hizmet standartlarını ileriye taşımak.</p>',
                'ar' => '<p>أن نكون من الشركات المرجعية في التجزئة والتوزيع في قبرص، بفضل محفظة علامات قوية وهيكل مؤسسي متين.</p><p>نقل التعاونات التي نبنيها مع العلامات الوطنية والدولية إلى قطاعات وفئات مختلفة، ودعم نمو العلامات التي نمثلها في سوق الجزيرة، والارتقاء بمعايير خدمة التجزئة في قبرص.</p>',
            ],
            'mission' => [
                'en' => '<p>To work with an approach that understands customer expectations, is careful in product and service selection, and takes responsibility at every stage of shopping.</p><p>To deliver product consultancy, free island-wide delivery and installation, warranty procedures, and spare-parts support in a coordinated way with our professional team.</p><p>To protect the values of the brands we represent and to build stable, transparent, and sustainable commercial relationships for our partners and customers.</p>',
                'tr' => '<p>Müşterilerimizin beklentilerini doğru anlayan, ürün ve hizmet seçiminde titizlik gösteren, alışverişin her aşamasında sorumluluk üstlenen bir çalışma anlayışı benimsemek.</p><p>Profesyonel ekibimizle ürün danışmanlığı, ada genelinde ücretsiz teslimat ve kurulum, garanti kapsamındaki işlemler ve yedek parça desteğini koordineli biçimde yürütmek.</p><p>Temsil ettiğimiz markaların değerlerini koruyarak, iş ortaklarımız ve müşterilerimiz için istikrarlı, şeffaf ve sürdürülebilir ticari ilişkiler geliştirmek.</p>',
                'ar' => '<p>تبنّي أسلوب عمل يفهم توقعات عملائنا بدقة، ويختار المنتجات والخدمات بعناية، ويتحمّل المسؤولية في كل مرحلة من مراحل التسوق.</p><p>تقديم استشارات المنتج والتوصيل والتركيب المجاني في الجزيرة وإجراءات الضمان ودعم قطع الغيار بتنسيق مع فريقنا المهني.</p><p>حماية قيم العلامات التي نمثلها وبناء علاقات تجارية مستقرة وشفافة ومستدامة لشركائنا وعملائنا.</p>',
            ],
        ];
    }

    public static function findBySlug(array $items, string $slug): ?array
    {
        foreach ($items as $item) {
            if (($item['slug'] ?? '') === $slug) {
                return $item;
            }
        }
        return null;
    }

    public static function banner(string $location): string
    {
        $map = [
            'about' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1600&q=80',
            'contact' => 'https://images.unsplash.com/photo-1423666639041-f56000c27a9e?w=1600&q=80',
            'stores' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&q=80',
            'partnerships' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1600&q=80',
            'gallery' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1600&q=80',
            'news' => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1600&q=80',
            'projects' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1600&q=80',
            'sectors' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1600&q=80',
            'home_mid' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&q=80',
            'home_cta' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1600&q=80',
        ];

        return $map[$location] ?? $map['about'];
    }
}
