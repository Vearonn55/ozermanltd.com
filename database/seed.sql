-- ============================================================
-- SEED DATA — Özerman Ticaret
-- Run after schema.sql against the SAME selected database.
-- cPanel: select ozermanl_MAIN in phpMyAdmin, then Import.
-- Local: mysql -u root -p YOUR_DB_NAME < database/seed.sql
-- ============================================================

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
(1, 1, 'Home', 'Özerman Ticaret — distinguished brands, a strong local structure.', 'Özerman Ticaret | Distribution and Trade', 'Özerman Ticaret is a distribution and trading company in Cyprus that connects brands with quality retail service.'),
(2, 1, 'About Us', 'Local expertise, a long-term approach.', 'About Us | Özerman Ticaret', 'Özerman Ticaret was founded in 2020 by Emin Özerman in Cyprus.'),
(3, 1, 'Areas of Work', 'Distribution, brand partnerships, and retail service in Cyprus.', 'Areas of Work | Özerman Ticaret', 'How Özerman Ticaret works: distribution, brand partnerships, and after-sales retail service.'),
(4, 1, 'Projects', 'Özerman Ticaret publishes its brands and stores rather than a property portfolio.', 'Projects | Özerman Ticaret', 'Özerman Ticaret focuses on brands, stores, and distribution in Cyprus.'),
(5, 1, 'Corporate', 'Milestones of Özerman Ticaret.', 'Corporate | Özerman Ticaret', 'From Bebecik Online and Daphne Baby to Özerman Ticaret in 2020.'),
(6, 1, 'Gallery', 'Stores and brands.', 'Gallery | Özerman Ticaret', 'Moments from our stores and the brands we represent in Cyprus.'),
(7, 1, 'Contact', 'Get in touch with our team.', 'Contact | Özerman Ticaret', 'Contact Özerman Ticaret — partnership and wholesale inquiries.');

-- Sectors
INSERT INTO sectors (icon, color, sort_order) VALUES
('globe', '#EA4C37', 0),
('handshake', '#3A3A3C', 1),
('store', '#EA4C37', 2);

INSERT INTO sector_translations (sector_id, language_id, name, slug, overview, services_text) VALUES
(1, 1, 'Distribution & Trade', 'distribution-trade', 'We connect the brands we select with the Cyprus market and coordinate product supply and commercial processes.', '• Brand distribution in Cyprus\n• Product supply coordination\n• Commercial process management'),
(1, 2, 'Distribütörlük ve Ticaret', 'distribution-trade-tr', 'Seçtiğimiz markaları Kıbrıs pazarıyla buluşturuyor, ürün tedariği ve ticari süreçleri koordineli biçimde yürütüyoruz.', '• Kıbrıs’ta marka distribütörlüğü\n• Ürün tedarik koordinasyonu\n• Ticari süreç yönetimi'),
(1, 3, 'التوزيع والتجارة', 'distribution-trade-ar', 'نجمع العلامات التي نختارها مع سوق قبرص وننسّق توريد المنتجات والعمليات التجارية.', '• توزيع العلامات في قبرص\n• تنسيق توريد المنتجات\n• إدارة العمليات التجارية'),
(2, 1, 'Brand Partnerships', 'brand-partnerships', 'We support brand positioning and local-market growth with a long-term partnership approach.', '• Long-term brand representation\n• Local positioning\n• Partner communication'),
(2, 2, 'Marka İş Birlikleri', 'brand-partnerships-tr', 'Markaların konumlandırmasını ve yerel pazardaki gelişimini uzun vadeli iş ortaklığı anlayışıyla destekliyoruz.', '• Uzun vadeli marka temsilciliği\n• Yerel konumlandırma\n• İş ortağı iletişimi'),
(2, 3, 'تعاونات العلامات', 'brand-partnerships-ar', 'ندعم تموضع العلامات ونموها في السوق المحلي بمنهج شراكة طويل الأمد.', '• تمثيل طويل الأمد للعلامات\n• التموضع المحلي\n• التواصل مع الشركاء'),
(3, 1, 'Retail & After-Sales Service', 'retail-after-sales', 'We provide store retail, product consultancy, free island-wide delivery and installation, plus warranty and spare-parts support.', '• Stores in Nicosia and Famagusta\n• Free island-wide delivery and installation\n• Warranty and spare parts'),
(3, 2, 'Perakende ve Satış Sonrası Hizmet', 'retail-after-sales-tr', 'Mağazacılık, ürün danışmanlığı, ücretsiz ada geneli teslimat ve kurulum ile garanti ve yedek parça desteği sunuyoruz.', '• Lefkoşa ve Gazimağusa mağazaları\n• Ücretsiz ada geneli teslimat ve kurulum\n• Garanti ve yedek parça'),
(3, 3, 'التجزئة وخدمة ما بعد البيع', 'retail-after-sales-ar', 'نقدّم البيع بالتجزئة واستشارات المنتج والتوصيل والتركيب المجاني في الجزيرة ودعم الضمان وقطع الغيار.', '• متاجر نيقوسيا وفاماغوستا\n• توصيل وتركيب مجاني في الجزيرة\n• الضمان وقطع الغيار');

-- Project categories
INSERT INTO project_categories (sort_order) VALUES (0), (1), (2);
INSERT INTO project_category_translations (category_id, language_id, name, slug) VALUES
(1, 1, 'Residential', 'residential'),
(2, 1, 'Commercial', 'commercial'),
(3, 1, 'Industrial', 'industrial');

-- Projects
-- No property projects. Özerman Ticaret publishes brands, stores, and milestones.

-- News categories
INSERT INTO news_categories (color, sort_order) VALUES
('#EA4C37', 0), ('#3A3A3C', 1), ('#EA4C37', 2);

INSERT INTO news_category_translations (category_id, language_id, name, slug) VALUES
(1, 1, 'First step', 'first-step'),
(1, 2, 'İlk Adım', 'ilk-adim'),
(1, 3, 'الخطوة الأولى', 'first-step-ar'),
(2, 1, '2018', 'year-2018'),
(2, 2, '2018', 'year-2018-tr'),
(2, 3, '2018', 'year-2018-ar'),
(3, 1, '2020', 'year-2020'),
(3, 2, '2020', 'year-2020-tr'),
(3, 3, '2020', 'year-2020-ar');

-- Corporate milestones
INSERT INTO news (category_id, author_id, status, publish_date, is_featured) VALUES
(1, 1, 'published', '2016-01-01', 1),
(2, 1, 'published', '2018-01-01', 1),
(3, 1, 'published', '2020-01-01', 1);

INSERT INTO news_translations (news_id, language_id, title, slug, excerpt, content) VALUES
(1, 1, 'Bebecik Online', 'bebecik-online', 'The entrepreneurial journey that began with online sales in baby products.', '<p>Bebecik Online is the first step of the journey that later became Özerman Ticaret.</p>'),
(1, 2, 'Bebecik Online', 'bebecik-online-tr', 'Bebek ürünleri alanında çevrim içi satışla başlayan girişimcilik yolculuğu.', '<p>Bebecik Online, bugün Özerman Ticaret’e dönüşen yolculuğun ilk adımıdır.</p>'),
(1, 3, 'بيبيسيك أونلاين', 'bebecik-online-ar', 'رحلة ريادية بدأت بالبيع عبر الإنترنت في مجال منتجات الرضع.', '<p>بيبيسيك أونلاين هي الخطوة الأولى في الرحلة التي أصبحت أوزرمان للتجارة.</p>'),
(2, 1, 'Daphne Baby', 'daphne-baby-2018', 'Daphne Baby opened its store in Famagusta.', '<p>In 2018 Daphne Baby opened in Famagusta and became the company’s own brand in baby products.</p>'),
(2, 2, 'Daphne Baby', 'daphne-baby-2018-tr', 'Gazimağusa’da Daphne Baby mağazasının açılması.', '<p>2018 yılında Daphne Baby, Gazimağusa’da açıldı.</p>'),
(2, 3, 'دافني بيبي', 'daphne-baby-2018-ar', 'افتتاح متجر دافني بيبي في فاماغوستا.', '<p>في عام 2018 افتُتحت دافني بيبي في فاماغوستا.</p>'),
(3, 1, 'Özerman Ticaret', 'ozerman-ticaret-2020', 'Retail experience became a corporate trading and distribution structure.', '<p>Özerman Ticaret was founded in 2020 by Emin Özerman in Cyprus.</p>'),
(3, 2, 'Özerman Ticaret', 'ozerman-ticaret-2020-tr', 'Perakende deneyiminin kurumsal ticaret ve distribütörlük yapısına dönüşmesi.', '<p>Özerman Ticaret, 2020 yılında Emin Özerman tarafından Kıbrıs’ta kuruldu.</p>'),
(3, 3, 'أوزرمان للتجارة', 'ozerman-ticaret-2020-ar', 'تحوّل تجربة التجزئة إلى هيكل تجارة وتوزيع مؤسسي.', '<p>تأسست أوزرمان للتجارة عام 2020 على يد أمين أوزرمان في قبرص.</p>');

-- Offices
INSERT INTO offices (phone, email, is_headquarters, is_active) VALUES
('', 'info@ozermanltd.com', 1, 1);

INSERT INTO office_translations (office_id, language_id, label, address, city, country, working_hours) VALUES
(1, 1, 'Head Office', 'Cyprus', 'Nicosia', 'Cyprus', 'Visiting hours will be added.'),
(1, 2, 'Genel Merkez', 'Kıbrıs', 'Lefkoşa', 'Kıbrıs', 'Ziyaret saatleri eklenecek.'),
(1, 3, 'المقر الرئيسي', 'قبرص', 'نيقوسيا', 'قبرص', 'ستُضاف ساعات الزيارة.');

-- Settings
INSERT INTO settings (`group`, `key`, value, type, label) VALUES
('general', 'site_name', 'Özerman Ticaret', 'text', 'Site Name'),
('general', 'site_tagline', 'Distinguished brands. A strong local structure.', 'text', 'Tagline'),
('analytics', 'plausible_domain', 'ozermanltd.com', 'text', 'Analytics domain'),
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
(4, 1, 'Our Brands'), (4, 2, 'Markalarımız'), (4, 3, 'علاماتنا'),
(5, 1, 'Corporate'), (5, 2, 'Kurumsal'), (5, 3, 'المؤسسي'),
(6, 1, 'Gallery'), (6, 2, 'Galeri'), (6, 3, 'المعرض'),
(7, 1, 'Contact'), (7, 2, 'İletişim'), (7, 3, 'اتصل بنا');

-- Footer column titles
INSERT INTO menu_translations (menu_id, language_id, title) VALUES
(2, 1, 'Quick Links'), (2, 2, 'Hızlı Bağlantılar'), (2, 3, 'روابط سريعة'),
(3, 1, 'Explore'), (3, 2, 'Keşfedin'), (3, 3, 'استكشف'),
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
(10, 1, 'Our Brands'), (10, 2, 'Markalarımız'), (10, 3, 'علاماتنا'),
(11, 1, 'Corporate'), (11, 2, 'Kurumsal'), (11, 3, 'المؤسسي'),
(12, 1, 'Gallery'), (12, 2, 'Galeri'), (12, 3, 'المعرض');

-- Footer column 2: Explore (items 13-16)
INSERT INTO menu_items (menu_id, url, sort_order) VALUES
(3, '/partnerships', 0),
(3, '/our-stores', 1),
(3, '/contact', 2);

INSERT INTO menu_item_translations (item_id, language_id, label) VALUES
(13, 1, 'Our Brand Portfolio'), (13, 2, 'Marka Portföyümüz'), (13, 3, 'محفظة العلامات'),
(14, 1, 'Stores'), (14, 2, 'Mağazalar'), (14, 3, 'المتاجر'),
(15, 1, 'Partnership Inquiries'), (15, 2, 'İş Birliği Talepleri'), (15, 3, 'طلبات التعاون');

-- Footer column 3: Legal (items 16-18). Gallery links stay in the menu but hidden.
INSERT INTO menu_items (menu_id, url, sort_order) VALUES
(4, '/privacy-policy', 0),
(4, '/cookie-policy', 1),
(4, '/cookie-settings', 2);

INSERT INTO menu_item_translations (item_id, language_id, label) VALUES
(16, 1, 'Privacy Policy'), (16, 2, 'Gizlilik Politikası'), (16, 3, 'سياسة الخصوصية'),
(17, 1, 'Cookie Policy'), (17, 2, 'Çerez Politikası'), (17, 3, 'سياسة ملفات تعريف الارتباط'),
(18, 1, 'Cookie Settings'), (18, 2, 'Çerez Ayarları'), (18, 3, 'إعدادات ملفات تعريف الارتباط');

UPDATE menu_items SET is_active = 0 WHERE id IN (6, 12);

-- Stat counters (homepage) — all locales
INSERT INTO stat_counters (page_id, language_id, label, value, suffix, sort_order) VALUES
(1, 1, 'Year Founded', '2020', '', 0),
(1, 1, 'Customers Reached', '3.000+', '', 1),
(1, 1, 'Store Count', '2', '', 2),
(1, 1, 'Team Members', '10', '', 3),
(1, 2, 'Kuruluş Yılı', '2020', '', 0),
(1, 2, 'Ulaşılan Müşteri', '3.000+', '', 1),
(1, 2, 'Mağaza Sayısı', '2', '', 2),
(1, 2, 'Ekip Üyesi', '10', '', 3),
(1, 3, 'سنة التأسيس', '2020', '', 0),
(1, 3, 'عملاء تم الوصول إليهم', '3.000+', '', 1),
(1, 3, 'عدد المتاجر', '2', '', 2),
(1, 3, 'أعضاء الفريق', '10', '', 3);

-- Team members
INSERT INTO team_members (type, sort_order, is_active) VALUES
('founder', 0, 1);

INSERT INTO team_member_translations (member_id, language_id, full_name, position, bio) VALUES
(1, 1, 'Emin Özerman', 'Founder', 'Founded Özerman Ticaret in Cyprus in 2020, building on the Daphne Baby retail journey that began in Famagusta in 2018.'),
(1, 2, 'Emin Özerman', 'Kurucu', 'Özerman Ticaret’i 2020 yılında Kıbrıs’ta kurdu. 2018’de Gazimağusa’da başlayan Daphne Baby perakende yolculuğunu kurumsal yapıya taşıdı.'),
(1, 3, 'أمين أوزرمان', 'المؤسس', 'أسس أوزرمان للتجارة في قبرص عام 2020، انطلاقًا من تجربة دافني بيبي التي بدأت في فاماغوستا عام 2018.');

-- Brands (partnerships)
INSERT INTO brands (slug, logo, website_url, sort_order, is_active) VALUES
('lajivert', 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&q=80', 'https://www.lajivert.com.tr', 0, 1),
('aymini', 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&q=80', 'https://www.aymini.com', 1, 1),
('daphne-baby', 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&q=80', '', 2, 1);

INSERT INTO brand_translations (brand_id, language_id, name, tagline, description) VALUES
(1, 1, 'Lajivert', 'Baby, kids and youth rooms', 'As Lajivert’s Cyprus distributor, we bring collections for children’s and youth living spaces to our customers.'),
(1, 2, 'Lajivert', 'Bebek, Çocuk ve Genç Odaları', 'Lajivert’in Kıbrıs distribütörlüğünü üstlenerek çocuk ve genç yaşam alanlarına yönelik koleksiyonları müşterilerimizle buluşturuyoruz.'),
(1, 3, 'Lajivert', 'غرف الرضع والأطفال والشباب', 'بصفتنا موزّع لاجيڤيرت في قبرص، نقدّم مجموعات مساحات معيشة الأطفال والشباب لعملائنا.'),
(2, 1, 'Aymini', 'Baby furniture', 'Through the Aymini Cyprus distributorship, we bring baby-room furniture to the local market.'),
(2, 2, 'Aymini', 'Bebek Mobilyaları', 'Aymini markasının Kıbrıs distribütörlüğüyle bebek odası mobilyalarını yerel pazara sunuyoruz.'),
(2, 3, 'Aymini', 'أثاث الرضع', 'من خلال وكالة أيميني في قبرص نقدّم أثاث غرف الرضع للسوق المحلي.'),
(3, 1, 'Daphne Baby', 'Özerman Ticaret’s own brand', 'The original brand of our retail journey in baby products.'),
(3, 2, 'Daphne Baby', 'Özerman Ticaret''in kendi markası', 'Bebek ürünleri alanında başlayan perakende deneyimimizin özgün markası.'),
(3, 3, 'Daphne Baby', 'العلامة الخاصة بأوزرمان للتجارة', 'العلامة الأصيلة لتجربتنا في تجزئة منتجات الرضع.');

-- Stores
INSERT INTO stores (phone, email, sort_order, is_active) VALUES
('', 'info@ozermanltd.com', 0, 1),
('', 'info@ozermanltd.com', 1, 1);

INSERT INTO store_translations (store_id, language_id, name, slug, city, address, working_hours) VALUES
(1, 1, 'Nicosia Store', 'lefkosa-store', 'Cyprus', 'Current address and visiting details will be added.', 'Visiting hours will be added.'),
(1, 2, 'Lefkoşa Mağazası', 'lefkosa-store', 'Kıbrıs', 'Güncel adres ve ziyaret bilgileri eklenecek.', 'Ziyaret saatleri eklenecek.'),
(1, 3, 'متجر نيقوسيا', 'lefkosa-store', 'قبرص', 'سيتم إضافة العنوان الحالي وتفاصيل الزيارة.', 'ستُضاف ساعات الزيارة.'),
(2, 1, 'Famagusta Store', 'gazimagusa-store', 'Cyprus', 'Current address and visiting details will be added.', 'Visiting hours will be added.'),
(2, 2, 'Gazimağusa Mağazası', 'gazimagusa-store', 'Kıbrıs', 'Güncel adres ve ziyaret bilgileri eklenecek.', 'Ziyaret saatleri eklenecek.'),
(2, 3, 'متجر فاماغوستا', 'gazimagusa-store', 'قبرص', 'سيتم إضافة العنوان الحالي وتفاصيل الزيارة.', 'ستُضاف ساعات الزيارة.');

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
(1, 1, 'Distribution & Trade', 'We connect the brands we select with the Cyprus market and coordinate product supply and commercial processes.'),
(1, 2, 'Distribütörlük ve Ticaret', 'Seçtiğimiz markaları Kıbrıs pazarıyla buluşturuyor, ürün tedariği ve ticari süreçleri koordineli biçimde yürütüyoruz.'),
(1, 3, 'التوزيع والتجارة', 'نجمع العلامات التي نختارها مع سوق قبرص وننسّق توريد المنتجات والعمليات التجارية.'),
(2, 1, 'Brand Partnerships', 'We support brand positioning and local-market growth with a long-term partnership approach.'),
(2, 2, 'Marka İş Birlikleri', 'Markaların konumlandırmasını ve yerel pazardaki gelişimini uzun vadeli iş ortaklığı anlayışıyla destekliyoruz.'),
(2, 3, 'تعاونات العلامات', 'ندعم تموضع العلامات ونموها في السوق المحلي بمنهج شراكة طويل الأمد.'),
(3, 1, 'Retail & After-Sales Service', 'We provide store retail, product consultancy, free island-wide delivery and installation, plus warranty and spare-parts support.'),
(3, 2, 'Perakende ve Satış Sonrası Hizmet', 'Mağazacılık, ürün danışmanlığı, ücretsiz ada geneli teslimat ve kurulum ile garanti ve yedek parça desteği sunuyoruz.'),
(3, 3, 'التجزئة وخدمة ما بعد البيع', 'نقدّم البيع بالتجزئة واستشارات المنتج والتوصيل والتركيب المجاني في الجزيرة ودعم الضمان وقطع الغيار.'),
(4, 1, 'Care', 'We are careful in the products and services we choose, and we take responsibility at every stage of the shopping experience.'),
(4, 2, 'Özen', 'Ürün ve hizmet seçiminde titizlik gösterir, alışverişin her aşamasında sorumluluk üstleniriz.'),
(4, 3, 'العناية', 'نختار المنتجات والخدمات بعناية ونتحمّل المسؤولية في كل مرحلة من تجربة التسوق.'),
(5, 1, 'Partnership', 'We protect the values of the brands we represent and build stable, transparent relationships with partners and customers.'),
(5, 2, 'İş Birliği', 'Temsil ettiğimiz markaların değerlerini korur, iş ortaklarımız ve müşterilerimizle istikrarlı ve şeffaf ilişkiler kurarız.'),
(5, 3, 'الشراكة', 'نحمي قيم العلامات التي نمثلها ونبني علاقات مستقرة وشفافة مع الشركاء والعملاء.'),
(6, 1, 'Service', 'Product consultancy, free island-wide delivery and installation, warranty work, and spare parts are handled together.'),
(6, 2, 'Hizmet', 'Ürün danışmanlığı, ücretsiz ada geneli teslimat ve kurulum, garanti işlemleri ve yedek parça desteğini birlikte yürütürüz.'),
(6, 3, 'الخدمة', 'نقدّم استشارات المنتج والتوصيل والتركيب المجاني في الجزيرة وإجراءات الضمان وقطع الغيار معًا.'),
(7, 1, 'Local focus', 'We work in Cyprus with a long-term view, from our stores in Nicosia and Famagusta.'),
(7, 2, 'Yerel Odak', 'Lefkoşa ve Gazimağusa mağazalarımızla Kıbrıs’ta uzun vadeli bir bakışla çalışırız.'),
(7, 3, 'التركيز المحلي', 'نعمل في قبرص بنظرة طويلة الأمد، من متاجرنا في نيقوسيا وفاماغوستا.'),
(8, 1, 'Vision', '<p>To be one of the reference companies in retail and distribution in Cyprus, distinguished by a strong brand portfolio and corporate structure.</p><p>To carry the partnerships we build with national and international brands into different sectors and categories, support the growth of the brands we represent in the island market, and raise retail service standards in Cyprus.</p>'),
(8, 2, 'Vizyon', '<p>Kıbrıs’ta perakende ve distribütörlük alanında referans gösterilen, güçlü marka portföyü ve kurumsal yapısıyla öne çıkan şirketlerden biri olmak.</p><p>Ulusal ve uluslararası markalarla geliştirdiğimiz iş birliklerini farklı sektör ve kategorilere taşıyarak, temsil ettiğimiz markaların ada pazarındaki gelişimine katkı sağlamak ve Kıbrıs’ta perakendeciliğin hizmet standartlarını ileriye taşımak.</p>'),
(8, 3, 'الرؤية', '<p>أن نكون من الشركات المرجعية في التجزئة والتوزيع في قبرص، بفضل محفظة علامات قوية وهيكل مؤسسي متين.</p><p>نقل التعاونات التي نبنيها مع العلامات الوطنية والدولية إلى قطاعات وفئات مختلفة، ودعم نمو العلامات التي نمثلها في سوق الجزيرة، والارتقاء بمعايير خدمة التجزئة في قبرص.</p>'),
(9, 1, 'Mission', '<p>To work with an approach that understands customer expectations, is careful in product and service selection, and takes responsibility at every stage of shopping.</p><p>To deliver product consultancy, free island-wide delivery and installation, warranty procedures, and spare-parts support in a coordinated way with our professional team.</p><p>To protect the values of the brands we represent and to build stable, transparent, and sustainable commercial relationships for our partners and customers.</p>'),
(9, 2, 'Misyon', '<p>Müşterilerimizin beklentilerini doğru anlayan, ürün ve hizmet seçiminde titizlik gösteren, alışverişin her aşamasında sorumluluk üstlenen bir çalışma anlayışı benimsemek.</p><p>Profesyonel ekibimizle ürün danışmanlığı, ada genelinde ücretsiz teslimat ve kurulum, garanti kapsamındaki işlemler ve yedek parça desteğini koordineli biçimde yürütmek.</p><p>Temsil ettiğimiz markaların değerlerini koruyarak, iş ortaklarımız ve müşterilerimiz için istikrarlı, şeffaf ve sürdürülebilir ticari ilişkiler geliştirmek.</p>'),
(9, 3, 'المهمة', '<p>تبنّي أسلوب عمل يفهم توقعات عملائنا بدقة، ويختار المنتجات والخدمات بعناية، ويتحمّل المسؤولية في كل مرحلة من مراحل التسوق.</p><p>تقديم استشارات المنتج والتوصيل والتركيب المجاني في الجزيرة وإجراءات الضمان ودعم قطع الغيار بتنسيق مع فريقنا المهني.</p><p>حماية قيم العلامات التي نمثلها وبناء علاقات تجارية مستقرة وشفافة ومستدامة لشركائنا وعملائنا.</p>');

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
