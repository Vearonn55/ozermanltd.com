-- ============================================================
-- SEED DATA — Ozerman Ltd (Dummy Content)
-- Run after schema.sql: mysql -u root -p ozermanltd < database/seed.sql
-- ============================================================

USE ozermanltd;

-- Languages
INSERT INTO languages (code, name, is_default, is_active, sort_order) VALUES
('en', 'English', 1, 1, 0),
('tr', 'Türkçe', 0, 1, 1),
('ar', 'العربية', 0, 1, 2);

-- Admin user (set password after install: php bin/reset-admin-password.php …)
INSERT INTO users (name, email, password_hash, role, status) VALUES
('Admin User', 'admin@ozermanltd.com', '$2y$12$hLRPxyBtjxCPa1taHBWsNei0kbttYIPjUMMOensB/vJwpDcSpkJtq', 'super_admin', 'active');

-- Pages
INSERT INTO pages (slug, template, status, show_in_nav, sort_order, published_at) VALUES
('home', 'home', 'published', 1, 0, NOW()),
('about-us', 'default', 'published', 1, 1, NOW()),
('sectors', 'sector', 'published', 1, 2, NOW()),
('projects', 'project', 'published', 1, 3, NOW()),
('news', 'default', 'published', 1, 4, NOW()),
('gallery', 'default', 'published', 1, 5, NOW()),
('contact', 'contact', 'published', 1, 6, NOW());

-- Page translations (English)
INSERT INTO page_translations (page_id, language_id, title, excerpt, meta_title, meta_description) VALUES
(1, 1, 'Home', 'Özerman Ticaret — Import. Partner. Deliver.', 'Özerman Ticaret | Importer Limited Company', 'An importer limited company connecting trusted brands with retail markets through disciplined trade and local presence.'),
(2, 1, 'About Us', 'Learn about our history, vision, and how we trade.', 'About Us | Özerman Ticaret', 'Özerman Ticaret is an importer limited company built on disciplined trade, brand partnerships, and retail presence.'),
(3, 1, 'Business Sectors', 'Explore our diversified portfolio of business sectors.', 'Business Sectors | Ozerman Ltd', 'Trading, construction, real estate, manufacturing, logistics, and energy.'),
(4, 1, 'Projects', 'Browse our project portfolio worldwide.', 'Projects | Ozerman Ltd', 'Residential, commercial, and industrial developments worldwide.'),
(5, 1, 'News', 'Latest news and announcements.', 'News | Özerman Ticaret', 'Stay informed with the latest from Özerman Ticaret.'),
(6, 1, 'Gallery', 'Corporate media gallery.', 'Gallery | Özerman Ticaret', 'Events, showrooms, and team culture.'),
(7, 1, 'Contact', 'Get in touch with our team.', 'Contact | Özerman Ticaret', 'Contact Özerman Ticaret — partnership and wholesale inquiries.');

-- Sectors
INSERT INTO sectors (icon, color, sort_order) VALUES
('globe', '#1e3a5f', 0),
('building', '#c9a227', 1),
('home', '#2d6a4f', 2),
('factory', '#6b4c9a', 3),
('truck', '#e76f51', 4),
('bolt', '#f4a261', 5);

INSERT INTO sector_translations (sector_id, language_id, name, slug, overview) VALUES
(1, 1, 'Trading', 'trading', 'Lorem ipsum dolor sit amet — international commodity trading and supply chain management.'),
(2, 1, 'Construction', 'construction', 'Lorem ipsum dolor sit amet — large-scale infrastructure and building projects.'),
(3, 1, 'Real Estate', 'real-estate', 'Lorem ipsum dolor sit amet — luxury residential and commercial property development.'),
(4, 1, 'Manufacturing', 'manufacturing', 'Lorem ipsum dolor sit amet — state-of-the-art manufacturing facilities.'),
(5, 1, 'Logistics', 'logistics', 'Lorem ipsum dolor sit amet — comprehensive logistics and distribution solutions.'),
(6, 1, 'Energy', 'energy', 'Lorem ipsum dolor sit amet — renewable energy and sustainable power solutions.');

-- Project categories
INSERT INTO project_categories (sort_order) VALUES (0), (1), (2);
INSERT INTO project_category_translations (category_id, language_id, name, slug) VALUES
(1, 1, 'Residential', 'residential'),
(2, 1, 'Commercial', 'commercial'),
(3, 1, 'Industrial', 'industrial');

-- Projects
INSERT INTO projects (category_id, status, location, delivery_date, start_price, currency, is_featured, is_active) VALUES
(1, 'ongoing', 'Dubai, UAE', 'June 2027', 285000.00, 'GBP', 1, 1),
(2, 'completed', 'London, UK', 'March 2025', NULL, 'GBP', 1, 1),
(3, 'planning', 'Istanbul, Turkey', 'December 2028', NULL, 'GBP', 0, 1),
(1, 'ongoing', 'Antalya, Turkey', 'September 2026', 425000.00, 'GBP', 1, 1);

INSERT INTO project_translations (project_id, language_id, title, slug, description) VALUES
(1, 1, 'Marina Heights Residences', 'marina-heights-residences', 'Lorem ipsum dolor sit amet — premium waterfront residential development with 420 luxury apartments.'),
(2, 1, 'Central Business Tower', 'central-business-tower', 'Lorem ipsum dolor sit amet — 42-storey Grade A office tower in London financial district.'),
(3, 1, 'Greenfield Industrial Park', 'greenfield-industrial-park', 'Lorem ipsum dolor sit amet — next-generation industrial park for advanced manufacturing.'),
(4, 1, 'Seaside Villa Collection', 'seaside-villa-collection', 'Lorem ipsum dolor sit amet — exclusive collection of 68 Mediterranean-style villas.');

-- News categories
INSERT INTO news_categories (color, sort_order) VALUES
('#1e3a5f', 0), ('#c9a227', 1), ('#2d6a4f', 2), ('#6b4c9a', 3);

INSERT INTO news_category_translations (category_id, language_id, name, slug) VALUES
(1, 1, 'Corporate', 'corporate'),
(2, 1, 'Projects', 'projects'),
(3, 1, 'Sustainability', 'sustainability'),
(4, 1, 'Partnerships', 'partnerships');

-- News articles
INSERT INTO news (category_id, author_id, status, publish_date, is_featured) VALUES
(1, 1, 'published', '2026-05-15', 1),
(2, 1, 'published', '2026-04-22', 0),
(3, 1, 'published', '2026-03-10', 0),
(4, 1, 'published', '2026-02-18', 0);

INSERT INTO news_translations (news_id, language_id, title, slug, excerpt, content) VALUES
(1, 1, 'Ozerman Ltd Expands into Renewable Energy Sector', 'ozerman-expands-into-renewable-energy',
 'Lorem ipsum dolor sit amet — strategic investment in solar and wind energy projects.',
 '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. The group announces a strategic investment in solar and wind energy projects across the Middle East and Europe.</p>'),
(2, 1, 'Groundbreaking Ceremony Held for Marina Heights Residences', 'marina-heights-groundbreaking-ceremony',
 'Lorem ipsum dolor sit amet — ceremony marking the start of construction on this landmark Dubai development.',
 '<p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Over 200 guests attended the groundbreaking ceremony.</p>'),
(3, 1, 'Ozerman Publishes 2025 Sustainability Report', 'sustainability-report-2025',
 'Lorem ipsum dolor sit amet — annual report highlights carbon reduction and community investment.',
 '<p>Ut enim ad minim veniam. Key highlights include a 23% reduction in carbon emissions.</p>'),
(4, 1, 'Strategic Partnership Announced for Logistics Expansion', 'new-partnership-logistics-expansion',
 'Lorem ipsum dolor sit amet — joint venture to strengthen logistics network across Central Asia.',
 '<p>Duis aute irure dolor in reprehenderit. New warehousing facilities in Poland, Kazakhstan, and Georgia.</p>');

-- Offices
INSERT INTO offices (phone, email, is_headquarters, is_active) VALUES
('+44 20 7946 0958', 'info@ozermanltd.com', 1, 1),
('+971 4 123 4567', 'dubai@ozermanltd.com', 0, 1),
('+90 212 123 4567', 'istanbul@ozermanltd.com', 0, 1);

INSERT INTO office_translations (office_id, language_id, label, address, city, country, working_hours) VALUES
(1, 1, 'Head Office', '25 Canary Wharf, London E14 5AB', 'London', 'United Kingdom', 'Mon–Fri: 9:00 AM – 6:00 PM'),
(2, 1, 'Middle East Office', 'Dubai International Financial Centre, Gate Village 3', 'Dubai', 'United Arab Emirates', 'Sun–Thu: 9:00 AM – 5:00 PM'),
(3, 1, 'Turkey Office', 'Levent Business District, Büyükdere Cad. No: 185', 'Istanbul', 'Turkey', 'Mon–Fri: 9:00 AM – 6:00 PM');

-- Settings
INSERT INTO settings (`group`, `key`, value, type, label) VALUES
('general', 'site_name', 'Özerman Ticaret', 'text', 'Site Name'),
('general', 'site_tagline', 'Import. Partner. Deliver.', 'text', 'Tagline'),
('social', 'linkedin', 'https://linkedin.com/company/ozermanltd', 'text', 'LinkedIn URL'),
('social', 'twitter', 'https://twitter.com/ozermanltd', 'text', 'Twitter URL'),
('analytics', 'plausible_domain', 'ozermanltd.com', 'text', 'Plausible Analytics Domain'),
('system', 'maintenance_mode', '0', 'boolean', 'Maintenance Mode');

-- Menus (aligned with DummyData / TR frontend reference)
INSERT INTO menus (location) VALUES ('header'), ('footer_col1'), ('footer_col2'), ('footer_col3');

INSERT INTO menu_items (menu_id, url, sort_order) VALUES
(1, '/en', 0),
(1, '/en/about-us', 1),
(1, '/en/our-stores', 2),
(1, '/en/partnerships', 3),
(1, '/en/news', 4),
(1, '/en/gallery', 5),
(1, '/en/contact', 6);

INSERT INTO menu_item_translations (item_id, language_id, label) VALUES
(1, 1, 'Home'), (1, 2, 'Ana Sayfa'), (1, 3, 'الرئيسية'),
(2, 1, 'About Us'), (2, 2, 'Hakkımızda'), (2, 3, 'من نحن'),
(3, 1, 'Our Stores'), (3, 2, 'Mağazalarımız'), (3, 3, 'متاجرنا'),
(4, 1, 'Partnerships'), (4, 2, 'İş Ortaklıkları'), (4, 3, 'الشراكات'),
(5, 1, 'News'), (5, 2, 'Haberler'), (5, 3, 'الأخبار'),
(6, 1, 'Gallery'), (6, 2, 'Galeri'), (6, 3, 'المعرض'),
(7, 1, 'Contact'), (7, 2, 'İletişim'), (7, 3, 'اتصل بنا');

-- Footer column titles
INSERT INTO menu_translations (menu_id, language_id, title) VALUES
(2, 1, 'Quick Links'), (2, 2, 'Hızlı Bağlantılar'), (2, 3, 'روابط سريعة'),
(3, 1, 'Explore'), (3, 2, 'Keşfet'), (3, 3, 'استكشف'),
(4, 1, 'Legal'), (4, 2, 'Yasal'), (4, 3, 'قانوني');

-- Footer column 1: Quick Links (items 8-12)
INSERT INTO menu_items (menu_id, url, sort_order) VALUES
(2, '/about-us', 0),
(2, '/our-stores', 1),
(2, '/partnerships', 2),
(2, '/news', 3),
(2, '/gallery', 4);

INSERT INTO menu_item_translations (item_id, language_id, label) VALUES
(8, 1, 'About Us'), (8, 2, 'Hakkımızda'), (8, 3, 'من نحن'),
(9, 1, 'Our Stores'), (9, 2, 'Mağazalarımız'), (9, 3, 'متاجرنا'),
(10, 1, 'Partnerships'), (10, 2, 'İş Ortaklıkları'), (10, 3, 'الشراكات'),
(11, 1, 'News'), (11, 2, 'Haberler'), (11, 3, 'الأخبار'),
(12, 1, 'Gallery'), (12, 2, 'Galeri'), (12, 3, 'المعرض');

-- Footer column 2: Explore (items 13-16)
INSERT INTO menu_items (menu_id, url, sort_order) VALUES
(3, '/partnerships', 0),
(3, '/our-stores', 1),
(3, '/contact', 2),
(3, '/gallery', 3);

INSERT INTO menu_item_translations (item_id, language_id, label) VALUES
(13, 1, 'Represented Brands'), (13, 2, 'Temsil Edilen Markalar'), (13, 3, 'العلامات الممثلة'),
(14, 1, 'Showrooms'), (14, 2, 'Showroomlar'), (14, 3, 'صالات العرض'),
(15, 1, 'Partnership Inquiries'), (15, 2, 'Ortaklık Talepleri'), (15, 3, 'استفسارات الشراكة'),
(16, 1, 'Media Gallery'), (16, 2, 'Medya Galerisi'), (16, 3, 'معرض الوسائط');

-- Footer column 3: Legal (items 17-19)
INSERT INTO menu_items (menu_id, url, sort_order) VALUES
(4, '/privacy-policy', 0),
(4, '/cookie-policy', 1),
(4, '/cookie-settings', 2);

INSERT INTO menu_item_translations (item_id, language_id, label) VALUES
(17, 1, 'Privacy Policy'), (17, 2, 'Gizlilik Politikası'), (17, 3, 'سياسة الخصوصية'),
(18, 1, 'Cookie Policy'), (18, 2, 'Çerez Politikası'), (18, 3, 'سياسة ملفات تعريف الارتباط'),
(19, 1, 'Cookie Settings'), (19, 2, 'Çerez Ayarları'), (19, 3, 'إعدادات ملفات تعريف الارتباط');

-- Stat counters (homepage) — all locales
INSERT INTO stat_counters (page_id, language_id, label, value, suffix, sort_order) VALUES
(1, 1, 'Years in Trade', '20+', 'Years', 0),
(1, 1, 'Brand Partners', '12+', 'Partners', 1),
(1, 1, 'Retail Points', '8', 'Stores', 2),
(1, 1, 'SKU Portfolio', '1,200+', 'SKUs', 3),
(1, 2, 'Yıllık Ticaret', '20+', 'Yıl', 0),
(1, 2, 'Marka Ortağı', '12+', 'Ortak', 1),
(1, 2, 'Satış Noktası', '8', 'Mağaza', 2),
(1, 2, 'Ürün Çeşidi', '1,200+', 'SKU', 3),
(1, 3, 'سنوات في التجارة', '20+', '', 0),
(1, 3, 'شركاء علامات', '12+', '', 1),
(1, 3, 'نقاط بيع', '8', '', 2),
(1, 3, 'تنوع المنتجات', '1,200+', '', 3);

-- Team members
INSERT INTO team_members (type, sort_order, is_active) VALUES
('founder', 0, 1), ('management', 1, 1), ('management', 2, 1), ('management', 3, 1);

INSERT INTO team_member_translations (member_id, language_id, full_name, position, bio) VALUES
(1, 1, 'James Ozerman', 'Founder & Chairman', 'Lorem ipsum dolor sit amet — 35+ years of international business experience.'),
(2, 1, 'Sarah Mitchell', 'Chief Executive Officer', 'Lorem ipsum dolor sit amet — 20 years in construction and real estate leadership.'),
(3, 1, 'Ahmet Yılmaz', 'Chief Financial Officer', 'Lorem ipsum dolor sit amet — oversees financial strategy across 28 countries.'),
(4, 1, 'Fatima Al-Rashid', 'Director of International Trade', 'Lorem ipsum dolor sit amet — leads trading division across Middle East, Africa, and Asia.');

-- Brands (partnerships)
INSERT INTO brands (slug, logo, website_url, sort_order, is_active) VALUES
('lajivert', 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&q=80', 'https://www.lajivert.com.tr', 0, 1),
('aymini', 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&q=80', 'https://www.aymini.com', 1, 1);

INSERT INTO brand_translations (brand_id, language_id, name, tagline, description) VALUES
(1, 1, 'Lajivert', 'Youth & kids room collections', 'Lajivert is among the brands we import and present through our retail network — contemporary youth and children’s furniture for growing households.'),
(1, 2, 'Lajivert', 'Genç ve çocuk odası koleksiyonları', 'Lajivert, ithal ettiğimiz ve perakende ağımızda sunduğumuz markalardandır — büyüyen aileler için çağdaş genç ve çocuk mobilyaları.'),
(1, 3, 'Lajivert', 'مجموعات غرف الشباب والأطفال', 'لاجيڤيرت من العلامات التي نستوردها ونعرضها عبر شبكة التجزئة — أثاث معاصر للشباب والأطفال.'),
(2, 1, 'Aymini', 'Baby furniture designed with care', 'Aymini baby furniture is part of our current import portfolio, combining design quality with materials chosen for safer nursery environments.'),
(2, 2, 'Aymini', 'Özenle tasarlanmış bebek mobilyaları', 'Aymini bebek mobilyaları mevcut ithalat portföyümüzün bir parçasıdır; tasarım kalitesini daha güvenli bebek odası malzemeleriyle birleştirir.'),
(2, 3, 'Aymini', 'أثاث أطفال مصمم بعناية', 'أثاث أيميني للرضع جزء من محفظة الاستيراد الحالية، يجمع جودة التصميم ومواد مختارة لبيئات أكثر أمانًا.');

-- Stores
INSERT INTO stores (phone, email, sort_order, is_active) VALUES
('+90 212 000 00 01', 'istanbul@ozermanltd.com', 0, 1),
('+90 312 000 00 02', 'ankara@ozermanltd.com', 1, 1),
('+90 232 000 00 03', 'izmir@ozermanltd.com', 2, 1);

INSERT INTO store_translations (store_id, language_id, name, slug, city, address, working_hours) VALUES
(1, 1, 'Istanbul Flagship Showroom', 'istanbul-showroom', 'Istanbul', 'Lorem Cad. No:42, Dummy Mah., Kadıköy', 'Mon–Sat: 10:00 – 20:00'),
(1, 2, 'İstanbul Merkez Showroom', 'istanbul-showroom', 'İstanbul', 'Lorem Cad. No:42, Dummy Mah., Kadıköy', 'Pzt–Cmt: 10:00 – 20:00'),
(1, 3, 'صالة عرض إسطنبول الرئيسية', 'istanbul-showroom', 'إسطنبول', 'شارع لوريم رقم 42، حي دامّي، كاديكوي', 'الإثنين–السبت: 10:00 – 20:00'),
(2, 1, 'Ankara Retail Store', 'ankara-store', 'Ankara', 'Ipsum Bulvarı 18/B, Placeholder Plaza', 'Mon–Sat: 10:00 – 19:00'),
(2, 2, 'Ankara Perakende Mağaza', 'ankara-store', 'Ankara', 'Ipsum Bulvarı 18/B, Placeholder Plaza', 'Pzt–Cmt: 10:00 – 19:00'),
(2, 3, 'متجر أنقرة للتجزئة', 'ankara-store', 'أنقرة', 'شارع إيبسوم 18/ب، بلازا مؤقتة', 'الإثنين–السبت: 10:00 – 19:00'),
(3, 1, 'Izmir Concept Corner', 'izmir-corner', 'Izmir', 'Dolor Sok. 7, Gibberish AVM Kat:2', 'Daily: 11:00 – 21:00'),
(3, 2, 'İzmir Konsept Köşe', 'izmir-corner', 'İzmir', 'Dolor Sok. 7, Gibberish AVM Kat:2', 'Her gün: 11:00 – 21:00'),
(3, 3, 'ركن إزمير المفاهيمي', 'izmir-corner', 'إزمير', 'شارع دولور 7، مجمع جيبريش الطابق 2', 'يوميًا: 11:00 – 21:00');

-- Content blocks: home operations, about values, vision, mission
INSERT INTO content_blocks (area, icon, sort_order, is_active) VALUES
('home_operations', 'globe', 0, 1),
('home_operations', 'handshake', 1, 1),
('home_operations', 'store', 2, 1),
('about_values', 'shield', 0, 1),
('about_values', 'lightbulb', 1, 1),
('about_values', 'star', 2, 1),
('about_values', 'leaf', 3, 1),
('about_vision', NULL, 0, 1),
('about_mission', NULL, 0, 1);

INSERT INTO content_block_translations (block_id, language_id, title, body) VALUES
(1, 1, 'Import & Trade', 'We source and import selected goods with disciplined procurement, logistics coordination, and reliable wholesale pathways.'),
(1, 2, 'İthalat ve Ticaret', 'Seçili ürünleri disiplinli tedarik, lojistik koordinasyon ve güvenilir toptan satış kanallarıyla ithal ederiz.'),
(1, 3, 'الاستيراد والتجارة', 'نستورد سلعًا مختارة عبر مشتريات منضبطة وتنسيق لوجستي ومسارات جملة موثوقة.'),
(2, 1, 'Brand Partnerships', 'We represent and grow brand relationships across categories — furniture today, with room to expand the portfolio tomorrow.'),
(2, 2, 'Marka Ortaklıkları', 'Kategoriler arasında marka ilişkilerini temsil eder ve büyütürüz — bugün mobilya, yarın genişleyen bir portföy.'),
(2, 3, 'شراكات العلامات', 'نمثل وننمي علاقات العلامات عبر الفئات — الأثاث اليوم، ومحفظة قابلة للتوسع غدًا.'),
(3, 1, 'Retail & Stores', 'Through our showrooms and retail points, customers meet imported collections with local service and guidance.'),
(3, 2, 'Perakende ve Mağazalar', 'Showroom ve satış noktalarımızda müşteriler ithal koleksiyonlarla yerel hizmet ve rehberlik bulur.'),
(3, 3, 'التجزئة والمتاجر', 'عبر صالات العرض ونقاط البيع يلتقي العملاء بالمجموعات المستوردة مع خدمة محلية وإرشاد.'),
(4, 1, 'Integrity', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. We conduct business with transparency, honesty, and ethical standards in every market we serve.'),
(4, 2, 'Dürüstlük', 'Lorem ipsum dolor sit amet. Hizmet verdiğimiz her pazarda şeffaflık, dürüstlük ve etik standartlarla iş yapıyoruz.'),
(4, 3, 'النزاهة', 'نمارس الأعمال بشفافية وأمانة ومعايير أخلاقية في كل سوق نخدمه.'),
(5, 1, 'Innovation', 'Sed do eiusmod tempor incididunt ut labore. We embrace new technologies and creative solutions to deliver exceptional value to our clients and communities.'),
(5, 2, 'Yenilik', 'Sed do eiusmod tempor. Müşterilerimize ve topluluklarımıza olağanüstü değer sunmak için yeni teknolojileri ve yaratıcı çözümleri benimsiyoruz.'),
(5, 3, 'الابتكار', 'نتبنى التقنيات الجديدة والحلول الإبداعية لتقديم قيمة استثنائية لعملائنا ومجتمعاتنا.'),
(6, 1, 'Excellence', 'Ut enim ad minim veniam, quis nostrud exercitation. We pursue the highest standards of quality in every project, product, and service we deliver.'),
(6, 2, 'Mükemmellik', 'Ut enim ad minim veniam. Sunduğumuz her proje, ürün ve hizmette en yüksek kalite standartlarını hedefliyoruz.'),
(6, 3, 'التميز', 'نسعى لتحقيق أعلى معايير الجودة في كل مشروع ومنتج وخدمة نقدمها.'),
(7, 1, 'Sustainability', 'Duis aute irure dolor in reprehenderit. We are committed to environmentally responsible practices and creating lasting positive impact for future generations.'),
(7, 2, 'Sürdürülebilirlik', 'Duis aute irure dolor. Çevreye duyarlı uygulamalara ve gelecek nesiller için kalıcı olumlu etki yaratmaya kararlıyız.'),
(7, 3, 'الاستدامة', 'نلتزم بممارسات مسؤولة بيئيًا وخلق تأثير إيجابي دائم للأجيال القادمة.'),
(8, 1, 'Vision', 'To be a trusted importer and retail partner — connecting quality brands with customers through integrity and local service.'),
(8, 2, 'Vizyon', 'Kaliteli markaları dürüstlük ve yerel hizmetle müşterilere bağlayan, güvenilir bir ithalatçı ve perakende ortağı olmak.'),
(8, 3, 'الرؤية', 'أن نكون مستوردًا وشريك تجزئة موثوقًا — نربط العلامات الجيدة بالعملاء عبر النزاهة والخدمة المحلية.'),
(9, 1, 'Mission', 'To import with care, represent brands responsibly, and deliver a clear retail experience across our stores and partner channels.'),
(9, 2, 'Misyon', 'Özenle ithal etmek, markaları sorumlu şekilde temsil etmek ve mağazalarımız ile iş ortaklığı kanallarımızda net bir perakende deneyimi sunmak.'),
(9, 3, 'المهمة', 'الاستيراد بعناية، وتمثيل العلامات بمسؤولية، وتقديم تجربة تجزئة واضحة عبر متاجرنا وقنوات الشركاء.');

-- Site banners (page headers + home extras)
INSERT INTO site_banners (location, fallback_url) VALUES
('about', 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1600&q=80'),
('contact', 'https://images.unsplash.com/photo-1423666639041-f56000c27a9e?w=1600&q=80'),
('stores', 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&q=80'),
('partnerships', 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1600&q=80'),
('gallery', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1600&q=80'),
('news', 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1600&q=80'),
('projects', 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1600&q=80'),
('sectors', 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1600&q=80'),
('home_mid', 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&q=80'),
('home_cta', 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1600&q=80');
