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
            ['label' => ['en' => 'Sectors', 'tr' => 'Sektörler', 'ar' => 'القطاعات'], 'url' => 'sectors'],
            ['label' => ['en' => 'Projects', 'tr' => 'Projeler', 'ar' => 'المشاريع'], 'url' => 'projects'],
            ['label' => ['en' => 'News', 'tr' => 'Haberler', 'ar' => 'الأخبار'], 'url' => 'news'],
            ['label' => ['en' => 'Gallery', 'tr' => 'Galeri', 'ar' => 'المعرض'], 'url' => 'gallery'],
            ['label' => ['en' => 'Contact', 'tr' => 'İletişim', 'ar' => 'اتصل بنا'], 'url' => 'contact'],
        ];
    }

    public static function heroSlides(): array
    {
        return [
            [
                'title' => [
                    'en' => 'Building a Global Legacy of Excellence',
                    'tr' => 'Mükemmellikte Küresel Bir Miras İnşa Ediyoruz',
                    'ar' => 'نبني إرثًا عالميًا من التميز',
                ],
                'subtitle' => [
                    'en' => 'Ozerman Ltd is a diversified international business group operating across trading, construction, real estate, and energy sectors.',
                    'tr' => 'Ozerman Ltd, ticaret, inşaat, gayrimenkul ve enerji sektörlerinde faaliyet gösteren çeşitlendirilmiş uluslararası bir iş grubudur.',
                    'ar' => 'أوزرمان المحدودة مجموعة أعمال دولية متنوعة تعمل في التجارة والبناء والعقارات والطاقة.',
                ],
                'cta_text' => ['en' => 'Explore Our Sectors', 'tr' => 'Sektörlerimizi Keşfedin', 'ar' => 'استكشف قطاعاتنا'],
                'cta_url' => 'sectors',
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1600&q=80',
            ],
            [
                'title' => [
                    'en' => 'Delivering Landmark Projects Worldwide',
                    'tr' => 'Dünya Çapında Öncü Projeler',
                    'ar' => 'تسليم مشاريع بارزة حول العالم',
                ],
                'subtitle' => [
                    'en' => 'From residential developments to commercial complexes, we create spaces that inspire communities and drive economic growth.',
                    'tr' => 'Konut projelerinden ticari komplekslere kadar, topluluklara ilham veren ve ekonomik büyümeyi destekleyen alanlar yaratıyoruz.',
                    'ar' => 'من المشاريع السكنية إلى المجمعات التجارية، نخلق مساحات تلهم المجتمعات وتدفع النمو الاقتصادي.',
                ],
                'cta_text' => ['en' => 'View Projects', 'tr' => 'Projeleri Görüntüle', 'ar' => 'عرض المشاريع'],
                'cta_url' => 'projects',
                'image' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1600&q=80',
            ],
            [
                'title' => [
                    'en' => 'Trusted Partner in International Trade',
                    'tr' => 'Uluslararası Ticarette Güvenilir Ortak',
                    'ar' => 'شريك موثوق في التجارة الدولية',
                ],
                'subtitle' => [
                    'en' => 'Connecting markets across continents with integrity, efficiency, and a commitment to sustainable business practices.',
                    'tr' => 'Kıtalar arası pazarları dürüstlük, verimlilik ve sürdürülebilir iş uygulamalarına bağlılıkla birbirine bağlıyoruz.',
                    'ar' => 'نربط الأسواق عبر القارات بنزاهة وكفاءة والتزام بممارسات الأعمال المستدامة.',
                ],
                'cta_text' => ['en' => 'Get in Touch', 'tr' => 'İletişime Geçin', 'ar' => 'تواصل معنا'],
                'cta_url' => 'contact',
                'image' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbcd?w=1600&q=80',
            ],
        ];
    }

    public static function stats(): array
    {
        return [
            ['value' => '36+', 'label' => ['en' => 'Years of Experience', 'tr' => 'Yıllık Deneyim', 'ar' => 'سنوات من الخبرة']],
            ['value' => '12', 'label' => ['en' => 'Business Sectors', 'tr' => 'İş Sektörü', 'ar' => 'قطاعات أعمال']],
            ['value' => '4,000+', 'label' => ['en' => 'Homes Delivered', 'tr' => 'Teslim Edilen Konut', 'ar' => 'وحدات سكنية مُسلّمة']],
            ['value' => '28', 'label' => ['en' => 'Countries Worldwide', 'tr' => 'Ülkede Faaliyet', 'ar' => 'دولة حول العالم']],
        ];
    }

    public static function sectors(): array
    {
        return [
            [
                'slug' => 'trading',
                'icon' => 'globe',
                'color' => '#1e3a5f',
                'name' => ['en' => 'Trading', 'tr' => 'Ticaret', 'ar' => 'التجارة'],
                'overview' => [
                    'en' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Our trading division connects global markets through strategic partnerships and efficient supply chain management across commodities, consumer goods, and industrial materials.',
                    'tr' => 'Lorem ipsum dolor sit amet. Ticaret bölümümüz, stratejik ortaklıklar ve verimli tedarik zinciri yönetimi ile küresel pazarları birbirine bağlar.',
                    'ar' => 'لوريم إيبسوم دولور سيت أميت. يربط قسم التجارة لدينا الأسواق العالمية من خلال الشراكات الاستراتيجية وإدارة سلسلة التوريد الفعالة.',
                ],
                'services' => [
                    'en' => "• International commodity trading\n• Import & export logistics\n• Supply chain optimization\n• Market analysis & advisory",
                    'tr' => "• Uluslararası emtia ticareti\n• İthalat ve ihracat lojistiği\n• Tedarik zinciri optimizasyonu\n• Pazar analizi ve danışmanlık",
                    'ar' => "• تجارة السلع الدولية\n• لوجستيات الاستيراد والتصدير\n• تحسين سلسلة التوريد\n• تحليل السوق والاستشارات",
                ],
                'image' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbcd?w=800&q=80',
            ],
            [
                'slug' => 'construction',
                'icon' => 'building',
                'color' => '#c9a227',
                'name' => ['en' => 'Construction', 'tr' => 'İnşaat', 'ar' => 'البناء'],
                'overview' => [
                    'en' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. We deliver large-scale infrastructure and building projects with uncompromising quality standards and innovative construction methodologies.',
                    'tr' => 'Sed do eiusmod tempor incididunt. Büyük ölçekli altyapı ve bina projelerini tavizsiz kalite standartları ve yenilikçi inşaat metodolojileriyle teslim ediyoruz.',
                    'ar' => 'نقدم مشاريع البنية التحتية والمباني واسعة النطاق بمعايير جودة لا هوادة فيها ومنهجيات بناء مبتكرة.',
                ],
                'services' => [
                    'en' => "• Commercial & residential construction\n• Infrastructure development\n• Project management\n• Design-build solutions",
                    'tr' => "• Ticari ve konut inşaatı\n• Altyapı geliştirme\n• Proje yönetimi\n• Tasarım-yapım çözümleri",
                    'ar' => "• البناء التجاري والسكني\n• تطوير البنية التحتية\n• إدارة المشاريع\n• حلول التصميم والبناء",
                ],
                'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&q=80',
            ],
            [
                'slug' => 'real-estate',
                'icon' => 'home',
                'color' => '#2d6a4f',
                'name' => ['en' => 'Real Estate', 'tr' => 'Gayrimenkul', 'ar' => 'العقارات'],
                'overview' => [
                    'en' => 'Ut enim ad minim veniam, quis nostrud exercitation. Our real estate portfolio spans luxury residential, mixed-use developments, and premium commercial properties in key global markets.',
                    'tr' => 'Ut enim ad minim veniam. Gayrimenkul portföyümüz, önemli küresel pazarlarda lüks konut, karma kullanımlı gelişmeler ve premium ticari mülkleri kapsar.',
                    'ar' => 'تمتد محفظتنا العقارية لتشمل المساكن الفاخرة والتطويرات متعددة الاستخدامات والعقارات التجارية المتميزة في الأسواق العالمية الرئيسية.',
                ],
                'services' => [
                    'en' => "• Property development\n• Investment advisory\n• Property management\n• Sales & leasing",
                    'tr' => "• Gayrimenkul geliştirme\n• Yatırım danışmanlığı\n• Mülk yönetimi\n• Satış ve kiralama",
                    'ar' => "• تطوير العقارات\n• الاستشارات الاستثمارية\n• إدارة الممتلكات\n• المبيعات والتأجير",
                ],
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80',
            ],
            [
                'slug' => 'manufacturing',
                'icon' => 'factory',
                'color' => '#6b4c9a',
                'name' => ['en' => 'Manufacturing', 'tr' => 'Üretim', 'ar' => 'التصنيع'],
                'overview' => [
                    'en' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore. State-of-the-art manufacturing facilities producing high-quality goods for domestic and international markets.',
                    'tr' => 'Duis aute irure dolor in reprehenderit. Yurt içi ve uluslararası pazarlar için yüksek kaliteli ürünler üreten son teknoloji üretim tesisleri.',
                    'ar' => 'مرافق تصنيع حديثة تنتج سلعًا عالية الجودة للأسواق المحلية والدولية.',
                ],
                'services' => [
                    'en' => "• Industrial manufacturing\n• Quality assurance\n• Custom fabrication\n• Export packaging",
                    'tr' => "• Endüstriyel üretim\n• Kalite güvencesi\n• Özel imalat\n• İhracat paketleme",
                    'ar' => "• التصنيع الصناعي\n• ضمان الجودة\n• التصنيع المخصص\n• تعبئة التصدير",
                ],
                'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&q=80',
            ],
            [
                'slug' => 'logistics',
                'icon' => 'truck',
                'color' => '#e76f51',
                'name' => ['en' => 'Logistics', 'tr' => 'Lojistik', 'ar' => 'اللوجستيات'],
                'overview' => [
                    'en' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim. Comprehensive logistics solutions ensuring timely delivery and supply chain efficiency worldwide.',
                    'tr' => 'Excepteur sint occaecat cupidatat. Dünya çapında zamanında teslimat ve tedarik zinciri verimliliği sağlayan kapsamlı lojistik çözümler.',
                    'ar' => 'حلول لوجستية شاملة تضمن التسليم في الوقت المناسب وكفاءة سلسلة التوريد في جميع أنحاء العالم.',
                ],
                'services' => [
                    'en' => "• Freight forwarding\n• Warehousing & distribution\n• Customs clearance\n• Last-mile delivery",
                    'tr' => "• Nakliye organizasyonu\n• Depolama ve dağıtım\n• Gümrük işlemleri\n• Son mil teslimat",
                    'ar' => "• الشحن الدولي\n• التخزين والتوزيع\n• التخليص الجمركي\n• التسليم للمرحلة الأخيرة",
                ],
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&q=80',
            ],
            [
                'slug' => 'energy',
                'icon' => 'bolt',
                'color' => '#f4a261',
                'name' => ['en' => 'Energy', 'tr' => 'Enerji', 'ar' => 'الطاقة'],
                'overview' => [
                    'en' => 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit. Investing in renewable energy and sustainable power solutions for a greener future.',
                    'tr' => 'Nemo enim ipsam voluptatem. Daha yeşil bir gelecek için yenilenebilir enerji ve sürdürülebilir güç çözümlerine yatırım yapıyoruz.',
                    'ar' => 'نستثمر في الطاقة المتجددة وحلول الطاقة المستدامة من أجل مستقبل أكثر خضرة.',
                ],
                'services' => [
                    'en' => "• Renewable energy projects\n• Solar & wind installations\n• Energy consulting\n• Power distribution",
                    'tr' => "• Yenilenebilir enerji projeleri\n• Güneş ve rüzgar kurulumları\n• Enerji danışmanlığı\n• Enerji dağıtımı",
                    'ar' => "• مشاريع الطاقة المتجددة\n• تركيبات الطاقة الشمسية والرياح\n• استشارات الطاقة\n• توزيع الطاقة",
                ],
                'image' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=800&q=80',
            ],
        ];
    }

    public static function projects(): array
    {
        return [
            [
                'slug' => 'marina-heights-residences',
                'category' => ['en' => 'Residential', 'tr' => 'Konut', 'ar' => 'سكني'],
                'status' => 'ongoing',
                'location' => 'Dubai, UAE',
                'delivery_date' => 'June 2027',
                'start_price' => 285000,
                'currency' => 'GBP',
                'is_featured' => true,
                'title' => ['en' => 'Marina Heights Residences', 'tr' => 'Marina Heights Konutları', 'ar' => 'مساكن مارينا هايتس'],
                'description' => [
                    'en' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. A premium waterfront residential development featuring 420 luxury apartments with panoramic marina views, world-class amenities, and sustainable design principles.',
                    'tr' => 'Lorem ipsum dolor sit amet. Panoramik marina manzaralı 420 lüks daire, dünya standartlarında olanaklar ve sürdürülebilir tasarım ilkeleri içeren premium bir sahil konut projesi.',
                    'ar' => 'مشروع سكني فاخر على الواجهة البحرية يضم 420 شقة فاخرة بإطلالات بانورامية على المارينا ومرافق عالمية المستوى.',
                ],
                'features' => [
                    'en' => "• Infinity pool & spa\n• 24/7 concierge service\n• Smart home technology\n• Underground parking\n• Landscaped gardens",
                    'tr' => "• Sonsuzluk havuzu ve spa\n• 7/24 concierge hizmeti\n• Akıllı ev teknolojisi\n• Yeraltı otoparkı\n• Peyzajlı bahçeler",
                    'ar' => "• مسبح لا متناهي وسبا\n• خدمة كونسيرج على مدار الساعة\n• تقنية المنزل الذكي\n• موقف سيارات تحت الأرض\n• حدائق منسقة",
                ],
                'image' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&q=80',
            ],
            [
                'slug' => 'central-business-tower',
                'category' => ['en' => 'Commercial', 'tr' => 'Ticari', 'ar' => 'تجاري'],
                'status' => 'completed',
                'location' => 'London, UK',
                'delivery_date' => 'March 2025',
                'start_price' => null,
                'currency' => 'GBP',
                'is_featured' => true,
                'title' => ['en' => 'Central Business Tower', 'tr' => 'Merkez İş Kulesi', 'ar' => 'برج الأعمال المركزي'],
                'description' => [
                    'en' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. A 42-storey Grade A office tower in the heart of London\'s financial district, setting new standards for workplace design and environmental performance.',
                    'tr' => 'Sed do eiusmod tempor. Londra\'nın finans bölgesinin kalbinde, işyeri tasarımı ve çevresel performans için yeni standartlar belirleyen 42 katlı A sınıfı bir ofis kulesi.',
                    'ar' => 'برج مكاتب من الدرجة A بارتفاع 42 طابقًا في قلب الحي المالي في لندن، يضع معايير جديدة لتصميم مكان العمل والأداء البيئي.',
                ],
                'features' => [
                    'en' => "• LEED Platinum certified\n• 85,000 sqm office space\n• Retail & dining podium\n• Sky lounge & terrace\n• EV charging stations",
                    'tr' => "• LEED Platin sertifikalı\n• 85.000 m² ofis alanı\n• Perakende ve yemek podiumu\n• Gökyüzü salonu ve teras\n• EV şarj istasyonları",
                    'ar' => "• معتمد LEED البلاتيني\n• 85,000 متر مربع من المساحات المكتبية\n• منصة تجارية ومطاعم\n• صالة سماء وتراس\n• محطات شحن السيارات الكهربائية",
                ],
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80',
            ],
            [
                'slug' => 'greenfield-industrial-park',
                'category' => ['en' => 'Industrial', 'tr' => 'Endüstriyel', 'ar' => 'صناعي'],
                'status' => 'planning',
                'location' => 'Istanbul, Turkey',
                'delivery_date' => 'December 2028',
                'start_price' => null,
                'currency' => 'GBP',
                'is_featured' => false,
                'title' => ['en' => 'Greenfield Industrial Park', 'tr' => 'Greenfield Endüstri Parkı', 'ar' => 'حديقة غرينفيلد الصناعية'],
                'description' => [
                    'en' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris. A next-generation industrial park designed for advanced manufacturing, logistics, and technology companies with integrated sustainability features.',
                    'tr' => 'Ut enim ad minim veniam. Entegre sürdürülebilirlik özellikleriyle ileri üretim, lojistik ve teknoloji şirketleri için tasarlanmış yeni nesil bir endüstri parkı.',
                    'ar' => 'حديقة صناعية من الجيل التالي مصممة لشركات التصنيع المتقدم واللوجستيات والتكنولوجيا مع ميزات استدامة متكاملة.',
                ],
                'features' => [
                    'en' => "• 250-hectare development\n• Rail & highway access\n• Renewable energy grid\n• Worker housing complex\n• R&D innovation center",
                    'tr' => "• 250 hektarlık gelişim\n• Demiryolu ve otoyol erişimi\n• Yenilenebilir enerji şebekesi\n• İşçi konut kompleksi\n• Ar-Ge inovasyon merkezi",
                    'ar' => "• تطوير بمساحة 250 هكتار\n• وصول بالسكك الحديدية والطرق السريعة\n• شبكة طاقة متجددة\n• مجمع سكني للعمال\n• مركز ابتكار للبحث والتطوير",
                ],
                'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&q=80',
            ],
            [
                'slug' => 'seaside-villa-collection',
                'category' => ['en' => 'Residential', 'tr' => 'Konut', 'ar' => 'سكني'],
                'status' => 'ongoing',
                'location' => 'Antalya, Turkey',
                'delivery_date' => 'September 2026',
                'start_price' => 425000,
                'currency' => 'GBP',
                'is_featured' => true,
                'title' => ['en' => 'Seaside Villa Collection', 'tr' => 'Sahil Villaları Koleksiyonu', 'ar' => 'مجموعة فيلات الساحل'],
                'description' => [
                    'en' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. An exclusive collection of 68 Mediterranean-style villas with private beaches, yacht berths, and resort-style amenities.',
                    'tr' => 'Duis aute irure dolor. Özel plajlar, yat bağlama yerleri ve tatil köyü tarzı olanaklarla 68 Akdeniz tarzı villadan oluşan özel bir koleksiyon.',
                    'ar' => 'مجموعة حصرية من 68 فيلا على الطراز المتوسطي مع شواطئ خاصة ومراسي يخوت ومرافق على طراز المنتجعات.',
                ],
                'features' => [
                    'en' => "• Private beach access\n• Infinity-edge pools\n• Mediterranean architecture\n• 24/7 security\n• Clubhouse & wellness center",
                    'tr' => "• Özel plaj erişimi\n• Sonsuzluk kenarlı havuzlar\n• Akdeniz mimarisi\n• 7/24 güvenlik\n• Kulüp binası ve wellness merkezi",
                    'ar' => "• وصول خاص للشاطئ\n• مسابح بحافة لا متناهية\n• عمارة متوسطية\n• أمن على مدار الساعة\n• نادي ومركز صحي",
                ],
                'image' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&q=80',
            ],
        ];
    }

    public static function news(): array
    {
        return [
            [
                'slug' => 'ozerman-expands-into-renewable-energy',
                'category' => ['en' => 'Corporate', 'tr' => 'Kurumsal', 'ar' => 'شركات'],
                'publish_date' => '2026-05-15',
                'is_featured' => true,
                'title' => [
                    'en' => 'Ozerman Ltd Expands into Renewable Energy Sector',
                    'tr' => 'Ozerman Ltd Yenilenebilir Enerji Sektörüne Genişliyor',
                    'ar' => 'أوزرمان المحدودة تتوسع في قطاع الطاقة المتجددة',
                ],
                'excerpt' => [
                    'en' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. The group announces a strategic investment in solar and wind energy projects across the Middle East and Europe.',
                    'tr' => 'Lorem ipsum dolor sit amet. Grup, Orta Doğu ve Avrupa genelinde güneş ve rüzgar enerjisi projelerine stratejik yatırım duyurdu.',
                    'ar' => 'أعلنت المجموعة عن استثمار استراتيجي في مشاريع الطاقة الشمسية والرياح في الشرق الأوسط وأوروبا.',
                ],
                'content' => [
                    'en' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>',
                    'tr' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Grup, sürdürülebilir enerji çözümlerine olan bağlılığını güçlendirmek amacıyla yenilenebilir enerji sektörüne stratejik bir yatırım duyurdu.</p><p>Bu yatırım, Orta Doğu ve Avrupa genelinde güneş ve rüzgar enerjisi projelerini kapsayacak ve grubun çevresel sürdürülebilirlik hedeflerine önemli katkı sağlayacaktır.</p>',
                    'ar' => '<p>أعلنت مجموعة أوزرمان عن استثمار استراتيجي في قطاع الطاقة المتجددة، مما يعزز التزامها بحلول الطاقة المستدامة.</p><p>سيغطي هذا الاستثمار مشاريع الطاقة الشمسية والرياح في جميع أنحاء الشرق الأوسط وأوروبا، مما يساهم بشكل كبير في أهداف الاستدامة البيئية للمجموعة.</p>',
                ],
                'image' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=800&q=80',
            ],
            [
                'slug' => 'marina-heights-groundbreaking-ceremony',
                'category' => ['en' => 'Projects', 'tr' => 'Projeler', 'ar' => 'مشاريع'],
                'publish_date' => '2026-04-22',
                'is_featured' => false,
                'title' => [
                    'en' => 'Groundbreaking Ceremony Held for Marina Heights Residences',
                    'tr' => 'Marina Heights Konutları İçin Temel Atma Töreni Düzenlendi',
                    'ar' => 'حفل وضع حجر الأساس لمشروع مساكن مارينا هايتس',
                ],
                'excerpt' => [
                    'en' => 'Sed do eiusmod tempor incididunt ut labore. Senior executives and government officials attended the ceremony marking the start of construction on this landmark Dubai development.',
                    'tr' => 'Sed do eiusmod tempor. Üst düzey yöneticiler ve devlet yetkilileri, bu önemli Dubai projesinin inşaatının başlangıcını işaretleyen törene katıldı.',
                    'ar' => 'حضر كبار المسؤولين والمسؤولين الحكوميين الحفل الذي يمثل بداية بناء هذا المشروع البارز في دبي.',
                ],
                'content' => [
                    'en' => '<p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. The groundbreaking ceremony for Marina Heights Residences was attended by over 200 guests, including senior government officials, industry leaders, and international investors.</p><p>The development is expected to create over 1,500 jobs during construction and contribute significantly to the local economy upon completion.</p>',
                    'tr' => '<p>Marina Heights Konutları temel atma törenine devlet yetkilileri, sektör liderleri ve uluslararası yatırımcılar dahil 200\'den fazla konuk katıldı.</p><p>Gelişme, inşaat sırasında 1.500\'den fazla iş yaratması ve tamamlandığında yerel ekonomiye önemli katkı sağlaması bekleniyor.</p>',
                    'ar' => '<p>حضر حفل وضع حجر الأساس لمساكن مارينا هايتس أكثر من 200 ضيف، بما في ذلك كبار المسؤولين الحكوميين وقادة الصناعة والمستثمرين الدوليين.</p><p>من المتوقع أن يخلق المشروع أكثر من 1500 وظيفة أثناء البناء ويساهم بشكل كبير في الاقتصاد المحلي عند اكتماله.</p>',
                ],
                'image' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&q=80',
            ],
            [
                'slug' => 'sustainability-report-2025',
                'category' => ['en' => 'Sustainability', 'tr' => 'Sürdürülebilirlik', 'ar' => 'الاستدامة'],
                'publish_date' => '2026-03-10',
                'is_featured' => false,
                'title' => [
                    'en' => 'Ozerman Publishes 2025 Sustainability Report',
                    'tr' => 'Ozerman 2025 Sürdürülebilirlik Raporunu Yayınladı',
                    'ar' => 'أوزرمان تنشر تقرير الاستدامة 2025',
                ],
                'excerpt' => [
                    'en' => 'Ut enim ad minim veniam, quis nostrud exercitation. The annual report highlights progress on carbon reduction, community investment, and ethical business practices.',
                    'tr' => 'Ut enim ad minim veniam. Yıllık rapor, karbon azaltma, topluluk yatırımı ve etik iş uygulamalarındaki ilerlemeyi vurguluyor.',
                    'ar' => 'يسلط التقرير السنوي الضوء على التقدم المحرز في خفض الكربون والاستثمار المجتمعي وممارسات الأعمال الأخلاقية.',
                ],
                'content' => [
                    'en' => '<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Ozerman Ltd has published its 2025 Sustainability Report, detailing the group\'s environmental, social, and governance (ESG) performance.</p><p>Key highlights include a 23% reduction in carbon emissions, £12 million invested in community programs, and achieving ISO 14001 certification across all major subsidiaries.</p>',
                    'tr' => '<p>Ozerman Ltd, grubun çevresel, sosyal ve yönetişim (ESG) performansını detaylandıran 2025 Sürdürülebilirlik Raporunu yayınladı.</p><p>Önemli başarılar arasında karbon emisyonlarında %23 azalma, topluluk programlarına 12 milyon £ yatırım ve tüm büyük iştiraklerde ISO 14001 sertifikası yer alıyor.</p>',
                    'ar' => '<p>نشرت أوزرمان المحدودة تقرير الاستدامة 2025، الذي يفصل أداء المجموعة البيئي والاجتماعي والحوكمي.</p><p>تشمل أبرز الإنجازات خفضًا بنسبة 23% في انبعاثات الكربون واستثمار 12 مليون جنيه إسترليني في برامج المجتمع والحصول على شهادة ISO 14001 في جميع الشركات التابعة الرئيسية.</p>',
                ],
                'image' => 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?w=800&q=80',
            ],
            [
                'slug' => 'new-partnership-logistics-expansion',
                'category' => ['en' => 'Partnerships', 'tr' => 'Ortaklıklar', 'ar' => 'شراكات'],
                'publish_date' => '2026-02-18',
                'is_featured' => false,
                'title' => [
                    'en' => 'Strategic Partnership Announced for Logistics Expansion',
                    'tr' => 'Lojistik Genişlemesi İçin Stratejik Ortaklık Duyuruldu',
                    'ar' => 'الإعلان عن شراكة استراتيجية لتوسيع اللوجستيات',
                ],
                'excerpt' => [
                    'en' => 'Duis aute irure dolor in reprehenderit. A new joint venture will strengthen Ozerman\'s logistics network across Central Asia and Eastern Europe.',
                    'tr' => 'Duis aute irure dolor. Yeni bir ortak girişim, Ozerman\'ın Orta Asya ve Doğu Avrupa genelindeki lojistik ağını güçlendirecek.',
                    'ar' => 'ستعزز مشروعًا مشتركًا جديدًا شبكة لوجستيات أوزرمان في وسط آسيا وأوروبا الشرقية.',
                ],
                'content' => [
                    'en' => '<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Ozerman Ltd has entered into a strategic partnership with a leading European logistics provider to expand its distribution network.</p><p>The partnership will establish new warehousing facilities in Poland, Kazakhstan, and Georgia, reducing delivery times by up to 40% for key markets.</p>',
                    'tr' => '<p>Ozerman Ltd, dağıtım ağını genişletmek için önde gelen bir Avrupa lojistik sağlayıcısıyla stratejik ortaklık kurdu.</p><p>Ortaklık, Polonya, Kazakistan ve Gürcistan\'da yeni depolama tesisleri kuracak ve önemli pazarlar için teslimat sürelerini %40\'a kadar azaltacak.</p>',
                    'ar' => '<p>دخلت أوزرمان المحدودة في شراكة استراتيجية مع مزود لوجستيات أوروبي رائد لتوسيع شبكة التوزيع الخاصة بها.</p><p>ستؤسس الشراكة مرافق تخزين جديدة في بولندا وكازاخستان وجورجيا، مما يقلل أوقات التسليم بنسبة تصل إلى 40% للأسواق الرئيسية.</p>',
                ],
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&q=80',
            ],
        ];
    }

    public static function team(): array
    {
        return [
            [
                'name' => ['en' => 'James Ozerman', 'tr' => 'James Ozerman', 'ar' => 'جيمس أوزرمان'],
                'position' => ['en' => 'Founder & Chairman', 'tr' => 'Kurucu ve Başkan', 'ar' => 'المؤسس والرئيس'],
                'bio' => [
                    'en' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. With over 35 years of experience in international business, James founded Ozerman Ltd in 1990 and has led its expansion into a global conglomerate.',
                    'tr' => 'Lorem ipsum dolor sit amet. Uluslararası iş dünyasında 35 yılı aşkın deneyime sahip James, 1990\'da Ozerman Ltd\'yi kurdu ve küresel bir holdinge genişlemesini yönetti.',
                    'ar' => 'مع أكثر من 35 عامًا من الخبرة في الأعمال الدولية، أسس جيمس أوزرمان المحدودة في عام 1990 وقاد توسعها لتصبح تكتلاً عالميًا.',
                ],
                'type' => 'founder',
                'image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&q=80',
            ],
            [
                'name' => ['en' => 'Sarah Mitchell', 'tr' => 'Sarah Mitchell', 'ar' => 'سارة ميتشيل'],
                'position' => ['en' => 'Chief Executive Officer', 'tr' => 'Genel Müdür', 'ar' => 'الرئيس التنفيذي'],
                'bio' => [
                    'en' => 'Sed do eiusmod tempor incididunt ut labore. Sarah brings 20 years of leadership experience in construction and real estate, driving operational excellence across all group subsidiaries.',
                    'tr' => 'Sed do eiusmod tempor. Sarah, inşaat ve gayrimenkulde 20 yıllık liderlik deneyimi getirerek tüm grup iştiraklerinde operasyonel mükemmelliği sağlıyor.',
                    'ar' => 'تجلب سارة 20 عامًا من الخبرة القيادية في البناء والعقارات، مما يدفع التميز التشغيلي عبر جميع الشركات التابعة للمجموعة.',
                ],
                'type' => 'management',
                'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&q=80',
            ],
            [
                'name' => ['en' => 'Ahmet Yılmaz', 'tr' => 'Ahmet Yılmaz', 'ar' => 'أحمد يلماز'],
                'position' => ['en' => 'Chief Financial Officer', 'tr' => 'Mali İşler Direktörü', 'ar' => 'المدير المالي'],
                'bio' => [
                    'en' => 'Ut enim ad minim veniam, quis nostrud exercitation. Ahmet oversees the group\'s financial strategy, treasury operations, and investor relations across 28 countries.',
                    'tr' => 'Ut enim ad minim veniam. Ahmet, 28 ülkede grubun finansal stratejisini, hazine operasyonlarını ve yatırımcı ilişkilerini yönetiyor.',
                    'ar' => 'يشرف أحمد على الاستراتيجية المالية للمجموعة وعمليات الخزينة وعلاقات المستثمرين في 28 دولة.',
                ],
                'type' => 'management',
                'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&q=80',
            ],
            [
                'name' => ['en' => 'Fatima Al-Rashid', 'tr' => 'Fatima Al-Rashid', 'ar' => 'فاطمة الراشد'],
                'position' => ['en' => 'Director of International Trade', 'tr' => 'Uluslararası Ticaret Direktörü', 'ar' => 'مديرة التجارة الدولية'],
                'bio' => [
                    'en' => 'Duis aute irure dolor in reprehenderit. Fatima leads the trading division, managing strategic partnerships and commodity operations across the Middle East, Africa, and Asia.',
                    'tr' => 'Duis aute irure dolor. Fatima, Orta Doğu, Afrika ve Asya genelinde stratejik ortaklıkları ve emtia operasyonlarını yöneten ticaret bölümüne liderlik ediyor.',
                    'ar' => 'تقود فاطمة قسم التجارة، وتدير الشراكات الاستراتيجية وعمليات السلع في الشرق الأوسط وأفريقيا وآسيا.',
                ],
                'type' => 'management',
                'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&q=80',
            ],
        ];
    }

    public static function values(): array
    {
        return [
            [
                'title' => ['en' => 'Integrity', 'tr' => 'Dürüstlük', 'ar' => 'النزاهة'],
                'description' => [
                    'en' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. We conduct business with transparency, honesty, and ethical standards in every market we serve.',
                    'tr' => 'Lorem ipsum dolor sit amet. Hizmet verdiğimiz her pazarda şeffaflık, dürüstlük ve etik standartlarla iş yapıyoruz.',
                    'ar' => 'نمارس الأعمال بشفافية وأمانة ومعايير أخلاقية في كل سوق نخدمه.',
                ],
                'icon' => 'shield',
            ],
            [
                'title' => ['en' => 'Innovation', 'tr' => 'Yenilik', 'ar' => 'الابتكار'],
                'description' => [
                    'en' => 'Sed do eiusmod tempor incididunt ut labore. We embrace new technologies and creative solutions to deliver exceptional value to our clients and communities.',
                    'tr' => 'Sed do eiusmod tempor. Müşterilerimize ve topluluklarımıza olağanüstü değer sunmak için yeni teknolojileri ve yaratıcı çözümleri benimsiyoruz.',
                    'ar' => 'نتبنى التقنيات الجديدة والحلول الإبداعية لتقديم قيمة استثنائية لعملائنا ومجتمعاتنا.',
                ],
                'icon' => 'lightbulb',
            ],
            [
                'title' => ['en' => 'Excellence', 'tr' => 'Mükemmellik', 'ar' => 'التميز'],
                'description' => [
                    'en' => 'Ut enim ad minim veniam, quis nostrud exercitation. We pursue the highest standards of quality in every project, product, and service we deliver.',
                    'tr' => 'Ut enim ad minim veniam. Sunduğumuz her proje, ürün ve hizmette en yüksek kalite standartlarını hedefliyoruz.',
                    'ar' => 'نسعى لتحقيق أعلى معايير الجودة في كل مشروع ومنتج وخدمة نقدمها.',
                ],
                'icon' => 'star',
            ],
            [
                'title' => ['en' => 'Sustainability', 'tr' => 'Sürdürülebilirlik', 'ar' => 'الاستدامة'],
                'description' => [
                    'en' => 'Duis aute irure dolor in reprehenderit. We are committed to environmentally responsible practices and creating lasting positive impact for future generations.',
                    'tr' => 'Duis aute irure dolor. Çevreye duyarlı uygulamalara ve gelecek nesiller için kalıcı olumlu etki yaratmaya kararlıyız.',
                    'ar' => 'نلتزم بممارسات مسؤولة بيئيًا وخلق تأثير إيجابي دائم للأجيال القادمة.',
                ],
                'icon' => 'leaf',
            ],
        ];
    }

    public static function gallery(): array
    {
        return [
            [
                'slug' => 'corporate-events',
                'title' => ['en' => 'Corporate Events', 'tr' => 'Kurumsal Etkinlikler', 'ar' => 'الفعاليات المؤسسية'],
                'description' => [
                    'en' => 'Lorem ipsum dolor sit amet — highlights from annual conferences, award ceremonies, and leadership summits.',
                    'tr' => 'Lorem ipsum dolor sit amet — yıllık konferanslar, ödül törenleri ve liderlik zirvelerinden öne çıkanlar.',
                    'ar' => 'أبرز لحظات المؤتمرات السنوية وحفلات التكريم وقمم القيادة.',
                ],
                'cover' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80',
                'items' => [
                    ['image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80', 'caption' => ['en' => 'Annual Leadership Summit 2025', 'tr' => 'Yıllık Liderlik Zirvesi 2025', 'ar' => 'قمة القيادة السنوية 2025']],
                    ['image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=600&q=80', 'caption' => ['en' => 'Excellence Awards Ceremony', 'tr' => 'Mükemmellik Ödülleri Töreni', 'ar' => 'حفل جوائز التميز']],
                    ['image' => 'https://images.unsplash.com/photo-1505373877841-8d25f39d4666?w=600&q=80', 'caption' => ['en' => 'International Partners Forum', 'tr' => 'Uluslararası Ortaklar Forumu', 'ar' => 'منتدى الشركاء الدوليين']],
                ],
            ],
            [
                'slug' => 'project-sites',
                'title' => ['en' => 'Project Sites', 'tr' => 'Proje Sahaları', 'ar' => 'مواقع المشاريع'],
                'description' => [
                    'en' => 'Sed do eiusmod tempor — construction progress and completed developments across our global portfolio.',
                    'tr' => 'Sed do eiusmod tempor — küresel portföyümüzdeki inşaat ilerlemesi ve tamamlanan gelişmeler.',
                    'ar' => 'تقدم البناء والتطويرات المكتملة عبر محفظتنا العالمية.',
                ],
                'cover' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&q=80',
                'items' => [
                    ['image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=600&q=80', 'caption' => ['en' => 'Marina Heights Construction', 'tr' => 'Marina Heights İnşaatı', 'ar' => 'بناء مارينا هايتس']],
                    ['image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&q=80', 'caption' => ['en' => 'Central Business Tower', 'tr' => 'Merkez İş Kulesi', 'ar' => 'برج الأعمال المركزي']],
                    ['image' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=600&q=80', 'caption' => ['en' => 'Seaside Villa Collection', 'tr' => 'Sahil Villaları', 'ar' => 'مجموعة فيلات الساحل']],
                ],
            ],
            [
                'slug' => 'team-culture',
                'title' => ['en' => 'Team & Culture', 'tr' => 'Ekip ve Kültür', 'ar' => 'الفريق والثقافة'],
                'description' => [
                    'en' => 'Ut enim ad minim veniam — our people, workplace culture, and community engagement initiatives.',
                    'tr' => 'Ut enim ad minim veniam — insanlarımız, işyeri kültürümüz ve topluluk katılım girişimlerimiz.',
                    'ar' => 'موظفونا وثقافة مكان العمل ومبادرات المشاركة المجتمعية.',
                ],
                'cover' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&q=80',
                'items' => [
                    ['image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&q=80', 'caption' => ['en' => 'Team Collaboration', 'tr' => 'Ekip İşbirliği', 'ar' => 'تعاون الفريق']],
                    ['image' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&q=80', 'caption' => ['en' => 'Office Culture', 'tr' => 'Ofis Kültürü', 'ar' => 'ثقافة المكتب']],
                    ['image' => 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=600&q=80', 'caption' => ['en' => 'Community Outreach', 'tr' => 'Topluluk Katılımı', 'ar' => 'التواصل المجتمعي']],
                ],
            ],
        ];
    }

    public static function offices(): array
    {
        return [
            [
                'label' => ['en' => 'Head Office', 'tr' => 'Genel Merkez', 'ar' => 'المقر الرئيسي'],
                'address' => ['en' => '25 Canary Wharf, London E14 5AB', 'tr' => '25 Canary Wharf, Londra E14 5AB', 'ar' => '25 كاناري وارف، لندن E14 5AB'],
                'city' => 'London',
                'country' => ['en' => 'United Kingdom', 'tr' => 'Birleşik Krallık', 'ar' => 'المملكة المتحدة'],
                'phone' => '+44 20 7946 0958',
                'email' => 'info@ozermanltd.com',
                'hours' => ['en' => 'Mon–Fri: 9:00 AM – 6:00 PM', 'tr' => 'Pzt–Cum: 09:00 – 18:00', 'ar' => 'الإثنين–الجمعة: 9:00 ص – 6:00 م'],
                'is_headquarters' => true,
            ],
            [
                'label' => ['en' => 'Middle East Office', 'tr' => 'Orta Doğu Ofisi', 'ar' => 'مكتب الشرق الأوسط'],
                'address' => ['en' => 'Dubai International Financial Centre, Gate Village 3', 'tr' => 'Dubai Uluslararası Finans Merkezi, Gate Village 3', 'ar' => 'مركز دبي المالي العالمي، بوابة القرية 3'],
                'city' => 'Dubai',
                'country' => ['en' => 'United Arab Emirates', 'tr' => 'Birleşik Arap Emirlikleri', 'ar' => 'الإمارات العربية المتحدة'],
                'phone' => '+971 4 123 4567',
                'email' => 'dubai@ozermanltd.com',
                'hours' => ['en' => 'Sun–Thu: 9:00 AM – 5:00 PM', 'tr' => 'Paz–Per: 09:00 – 17:00', 'ar' => 'الأحد–الخميس: 9:00 ص – 5:00 م'],
                'is_headquarters' => false,
            ],
            [
                'label' => ['en' => 'Turkey Office', 'tr' => 'Türkiye Ofisi', 'ar' => 'مكتب تركيا'],
                'address' => ['en' => 'Levent Business District, Büyükdere Cad. No: 185', 'tr' => 'Levent İş Merkezi, Büyükdere Cad. No: 185', 'ar' => 'منطقة ليفنت التجارية، شارع بيوكديري رقم 185'],
                'city' => 'Istanbul',
                'country' => ['en' => 'Turkey', 'tr' => 'Türkiye', 'ar' => 'تركيا'],
                'phone' => '+90 212 123 4567',
                'email' => 'istanbul@ozermanltd.com',
                'hours' => ['en' => 'Mon–Fri: 9:00 AM – 6:00 PM', 'tr' => 'Pzt–Cum: 09:00 – 18:00', 'ar' => 'الإثنين–الجمعة: 9:00 ص – 6:00 م'],
                'is_headquarters' => false,
            ],
        ];
    }

    public static function aboutContent(): array
    {
        return [
            'history' => [
                'en' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Founded in 1990 by James Ozerman, our company began as a modest trading firm in London and has grown into a diversified international business group with operations spanning 28 countries.</p><p>Over three decades, we have expanded into construction, real estate, manufacturing, logistics, and energy — always guided by our commitment to quality, integrity, and sustainable growth. Today, Ozerman Ltd employs over 8,500 professionals worldwide and has delivered more than 4,000 residential units and numerous commercial landmarks.</p>',
                'tr' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 1990 yılında James Ozerman tarafından kurulan şirketimiz, Londra\'da mütevazı bir ticaret firması olarak başladı ve 28 ülkede faaliyet gösteren çeşitlendirilmiş uluslararası bir iş grubuna dönüştü.</p><p>Üç yıldan fazla bir süredir inşaat, gayrimenkul, üretim, lojistik ve enerjiye genişledik — her zaman kalite, dürüstlük ve sürdürülebilir büyüme taahhüdümüzle yönlendirildik.</p>',
                'ar' => '<p>تأسست شركتنا في عام 1990 على يد جيمس أوزرمان، وبدأت كشركة تجارية متواضعة في لندن ونمت لتصبح مجموعة أعمال دولية متنوعة تعمل في 28 دولة.</p><p>على مدى أكثر من ثلاثة عقود، توسعنا في البناء والعقارات والتصنيع واللوجستيات والطاقة — مسترشدين دائمًا بالتزامنا بالجودة والنزاهة والنمو المستدام.</p>',
            ],
            'vision' => [
                'en' => 'To be the most trusted and innovative international business group, creating lasting value for our stakeholders and the communities we serve.',
                'tr' => 'Paydaşlarımız ve hizmet verdiğimiz topluluklar için kalıcı değer yaratan, en güvenilir ve yenilikçi uluslararası iş grubu olmak.',
                'ar' => 'أن نكون مجموعة الأعمال الدولية الأكثر ثقة وابتكارًا، ونخلق قيمة دائمة لأصحاب المصلحة والمجتمعات التي نخدمها.',
            ],
            'mission' => [
                'en' => 'To deliver excellence across every sector we operate in, through strategic investment, operational expertise, and a relentless commitment to sustainable business practices.',
                'tr' => 'Stratejik yatırım, operasyonel uzmanlık ve sürdürülebilir iş uygulamalarına kararlı bağlılık yoluyla faaliyet gösterdiğimiz her sektörde mükemmellik sunmak.',
                'ar' => 'تقديم التميز في كل قطاع نعمل فيه، من خلال الاستثمار الاستراتيجي والخبرة التشغيلية والالتزام الدؤوب بممارسات الأعمال المستدامة.',
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
}
