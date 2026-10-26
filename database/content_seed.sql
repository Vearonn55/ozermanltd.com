-- Supplemental content seed for database-driven site
-- Run after seed.sql against the SAME selected database (e.g. ozermanl_MAIN).

-- About page structured content (JSON in page_translations.content)
-- Multilingual payload matches DummyData::aboutContent() (TR frontend reference)
UPDATE page_translations
SET content = '{"history":{"en":"<p>Özerman Ticaret was founded in 2020 by Emin Özerman in Cyprus. The entrepreneurial journey that began with Bebecik Online and Daphne Baby, opened in Famagusta in 2018, forms the foundation of today’s retail and distribution structure. Today we operate our own brand Daphne Baby together with the Cyprus distributorships of Lajivert and Aymini.</p>","tr":"<p>Özerman Ticaret, 2020 yılında Emin Özerman tarafından Kıbrıs’ta kurulmuştur. Bebecik Online ile başlayan girişimcilik yolculuğu ve 2018 yılında Gazimağusa’da hayata geçirilen Daphne Baby, şirketin bugünkü perakende ve distribütörlük yapısının temelini oluşturur. Bugün kendi markamız Daphne Baby ile Lajivert ve Aymini markalarının Kıbrıs distribütörlüklerini yürütüyoruz.</p>","ar":"<p>تأسست أوزرمان للتجارة عام 2020 على يد أمين أوزرمان في قبرص. بدأت الرحلة الريادية مع بيبيسيك أونلاين ومع دافني بيبي التي افتُتحت في فاماغوستا عام 2018، وهما أساس هيكل التجزئة والتوزيع اليوم. ندير اليوم علامتنا دافني بيبي إلى جانب وكالات لاجيڤيرت وأيميني في قبرص.</p>"},"vision":{"en":"<p>To be one of the reference companies in retail and distribution in Cyprus, distinguished by a strong brand portfolio and corporate structure.</p><p>To carry the partnerships we build with national and international brands into different sectors and categories, support the growth of the brands we represent in the island market, and raise retail service standards in Cyprus.</p>","tr":"<p>Kıbrıs’ta perakende ve distribütörlük alanında referans gösterilen, güçlü marka portföyü ve kurumsal yapısıyla öne çıkan şirketlerden biri olmak.</p><p>Ulusal ve uluslararası markalarla geliştirdiğimiz iş birliklerini farklı sektör ve kategorilere taşıyarak, temsil ettiğimiz markaların ada pazarındaki gelişimine katkı sağlamak ve Kıbrıs’ta perakendeciliğin hizmet standartlarını ileriye taşımak.</p>","ar":"<p>أن نكون من الشركات المرجعية في التجزئة والتوزيع في قبرص، بفضل محفظة علامات قوية وهيكل مؤسسي متين.</p><p>نقل التعاونات التي نبنيها مع العلامات الوطنية والدولية إلى قطاعات وفئات مختلفة، ودعم نمو العلامات التي نمثلها في سوق الجزيرة، والارتقاء بمعايير خدمة التجزئة في قبرص.</p>"},"mission":{"en":"<p>To work with an approach that understands customer expectations, is careful in product and service selection, and takes responsibility at every stage of shopping.</p><p>To deliver product consultancy, free island-wide delivery and installation, warranty procedures, and spare-parts support in a coordinated way with our professional team.</p><p>To protect the values of the brands we represent and to build stable, transparent, and sustainable commercial relationships for our partners and customers.</p>","tr":"<p>Müşterilerimizin beklentilerini doğru anlayan, ürün ve hizmet seçiminde titizlik gösteren, alışverişin her aşamasında sorumluluk üstlenen bir çalışma anlayışı benimsemek.</p><p>Profesyonel ekibimizle ürün danışmanlığı, ada genelinde ücretsiz teslimat ve kurulum, garanti kapsamındaki işlemler ve yedek parça desteğini koordineli biçimde yürütmek.</p><p>Temsil ettiğimiz markaların değerlerini koruyarak, iş ortaklarımız ve müşterilerimiz için istikrarlı, şeffaf ve sürdürülebilir ticari ilişkiler geliştirmek.</p>","ar":"<p>تبنّي أسلوب عمل يفهم توقعات عملائنا بدقة، ويختار المنتجات والخدمات بعناية، ويتحمّل المسؤولية في كل مرحلة من مراحل التسوق.</p><p>تقديم استشارات المنتج والتوصيل والتركيب المجاني في الجزيرة وإجراءات الضمان ودعم قطع الغيار بتنسيق مع فريقنا المهني.</p><p>حماية قيم العلامات التي نمثلها وبناء علاقات تجارية مستقرة وشفافة ومستدامة لشركائنا وعملائنا.</p>"}}'
WHERE page_id = 2 AND language_id = 1;

-- Areas of work (hide leftover sample sectors)
UPDATE sectors SET is_active = 0 WHERE id > 3;
UPDATE sector_translations SET name = 'Distribution & Trade', slug = 'distribution-trade', overview = 'We connect the brands we select with the Cyprus market and coordinate product supply and commercial processes.', services_text = '• Brand distribution in Cyprus\n• Product supply coordination\n• Commercial process management' WHERE sector_id = 1 AND language_id = 1;
UPDATE sector_translations SET name = 'Brand Partnerships', slug = 'brand-partnerships', overview = 'We support brand positioning and local-market growth with a long-term partnership approach.', services_text = '• Long-term brand representation\n• Local positioning\n• Partner communication' WHERE sector_id = 2 AND language_id = 1;
UPDATE sector_translations SET name = 'Retail & After-Sales Service', slug = 'retail-after-sales', overview = 'We provide store retail, product consultancy, free island-wide delivery and installation, plus warranty and spare-parts support.', services_text = '• Stores in Nicosia and Famagusta\n• Free island-wide delivery and installation\n• Warranty and spare parts' WHERE sector_id = 3 AND language_id = 1;

-- Hide sample property projects from the public site
UPDATE projects SET is_active = 0, is_featured = 0;

-- Media assets (external URLs for development)
INSERT INTO media (file_name, original_name, file_path, file_type, mime_type, alt_text) VALUES
('hero-1.jpg', 'hero-1.jpg', 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1600&q=80', 'image', 'image/jpeg', 'Import logistics warehouse'),
('hero-2.jpg', 'hero-2.jpg', 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1600&q=80', 'image', 'image/jpeg', 'Furniture brand partnership'),
('hero-3.jpg', 'hero-3.jpg', 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&q=80', 'image', 'image/jpeg', 'Retail showroom'),
('project-1.jpg', 'project-1.jpg', 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&q=80', 'image', 'image/jpeg', 'Nicosia store'),
('project-2.jpg', 'project-2.jpg', 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80', 'image', 'image/jpeg', 'Famagusta store'),
('project-3.jpg', 'project-3.jpg', 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&q=80', 'image', 'image/jpeg', 'Lajivert collections'),
('project-4.jpg', 'project-4.jpg', 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&q=80', 'image', 'image/jpeg', 'Baby room collections'),
('news-1.jpg', 'news-1.jpg', 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&q=80', 'image', 'image/jpeg', 'Daphne Baby'),
('gallery-cover-1.jpg', 'gallery-cover-1.jpg', 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&q=80', 'image', 'image/jpeg', 'Our stores'),
('gallery-item-1.jpg', 'gallery-item-1.jpg', 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&q=80', 'image', 'image/jpeg', 'Nicosia store'),
('gallery-item-2.jpg', 'gallery-item-2.jpg', 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&q=80', 'image', 'image/jpeg', 'Famagusta store'),
('gallery-item-3.jpg', 'gallery-item-3.jpg', 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=600&q=80', 'image', 'image/jpeg', 'Lajivert collections');

UPDATE projects SET featured_image_id = 4 WHERE id = 1;
UPDATE projects SET featured_image_id = 5 WHERE id = 2;
UPDATE projects SET featured_image_id = 6 WHERE id = 3;
UPDATE projects SET featured_image_id = 7 WHERE id = 4;
UPDATE news SET featured_image_id = 8 WHERE id = 1;

-- Hero slides for all locales (matches DummyData::heroSlides — TR frontend reference)
INSERT INTO hero_slides (page_id, language_id, media_id, title, subtitle, cta_text, cta_url, sort_order) VALUES
-- English
(1, 1, 1, 'Distinguished brands. A strong local structure.', 'Özerman Ticaret is a distribution and trading company in Cyprus that connects brands with quality retail service.', 'Contact Us', '/en/contact', 0),
(1, 1, 2, 'Our Brands and Partnerships', 'We develop our own brand Daphne Baby together with our Lajivert and Aymini distributorships within the same corporate structure.', 'Discover Our Brands', '/en/partnerships', 1),
(1, 1, 3, 'We are with you in Cyprus', 'Visit our stores to see products in person and get support from our specialist team. We offer free delivery and installation across the island.', 'Our Stores', '/en/our-stores', 2),
-- Turkish
(1, 2, 1, 'Seçkin markalar. Güçlü bir yerel yapı.', 'Özerman Ticaret, Kıbrıs’ta markaları nitelikli perakende hizmetleriyle buluşturan bir distribütörlük ve ticaret şirketidir.', 'Bize Ulaşın', '/tr/contact', 0),
(1, 2, 2, 'Markalarımız ve İş Birliklerimiz', 'Kendi markamız Daphne Baby ile Lajivert ve Aymini distribütörlüklerimizi aynı kurumsal yapı içinde geliştiriyoruz.', 'Markalarımızı Keşfedin', '/tr/partnerships', 1),
(1, 2, 3, 'Kıbrıs’ta Yanınızdayız', 'Mağazalarımızda ürünleri yakından inceleyebilir, uzman ekibimizden destek alabilirsiniz. Ada genelinde ücretsiz teslimat ve kurulum sunuyoruz.', 'Mağazalarımız', '/tr/our-stores', 2),
-- Arabic
(1, 3, 1, 'علامات مميزة. بنية محلية قوية.', 'أوزرمان للتجارة شركة توزيع وتجارة في قبرص تجمع العلامات مع خدمات تجزئة نوعية.', 'تواصلوا معنا', '/ar/contact', 0),
(1, 3, 2, 'علاماتنا وتعاوناتنا', 'نطوّر علامتنا الخاصة دافني بيبي إلى جانب وكالات لاجيڤيرت وأيميني ضمن الهيكل المؤسسي نفسه.', 'اكتشفوا علاماتنا', '/ar/partnerships', 1),
(1, 3, 3, 'نحن معكم في قبرص', 'يمكنكم معاينة المنتجات في متاجرنا والحصول على دعم فريقنا المختص. نقدّم التوصيل والتركيب مجانًا في جميع أنحاء الجزيرة.', 'متاجرنا', '/ar/our-stores', 2);

INSERT INTO gallery_collections (cover_image_id, sort_order) VALUES
(9, 0), (9, 1), (9, 2);

INSERT INTO gallery_collection_translations (collection_id, language_id, title, slug, description) VALUES
(1, 1, 'Our Stores', 'our-stores', 'Moments from our Nicosia and Famagusta stores.'),
(2, 1, 'Our Brands', 'our-brands', 'Daphne Baby, Lajivert, and Aymini in our retail spaces.'),
(3, 1, 'Corporate', 'corporate', 'Our team and day-to-day work in Cyprus.');

INSERT INTO gallery_items (collection_id, media_id, sort_order, caption) VALUES
(1, 10, 0, 'Nicosia store'),
(1, 11, 1, 'Famagusta store'),
(1, 12, 2, 'Lajivert collections');

-- Production copy: update existing rows if content_seed is re-run after first insert
UPDATE hero_slides SET title = 'Distinguished brands. A strong local structure.', subtitle = 'Özerman Ticaret is a distribution and trading company in Cyprus that connects brands with quality retail service.', cta_text = 'Contact Us', cta_url = '/en/contact' WHERE page_id = 1 AND language_id = 1 AND sort_order = 0;
UPDATE hero_slides SET title = 'Our Brands and Partnerships', subtitle = 'We develop our own brand Daphne Baby together with our Lajivert and Aymini distributorships within the same corporate structure.', cta_text = 'Discover Our Brands' WHERE page_id = 1 AND language_id = 1 AND sort_order = 1;
UPDATE hero_slides SET title = 'We are with you in Cyprus', subtitle = 'Visit our stores to see products in person and get support from our specialist team. We offer free delivery and installation across the island.', cta_text = 'Our Stores' WHERE page_id = 1 AND language_id = 1 AND sort_order = 2;
UPDATE hero_slides SET title = 'Seçkin markalar. Güçlü bir yerel yapı.', subtitle = 'Özerman Ticaret, Kıbrıs’ta markaları nitelikli perakende hizmetleriyle buluşturan bir distribütörlük ve ticaret şirketidir.', cta_text = 'Bize Ulaşın', cta_url = '/tr/contact' WHERE page_id = 1 AND language_id = 2 AND sort_order = 0;
UPDATE hero_slides SET title = 'Markalarımız ve İş Birliklerimiz', subtitle = 'Kendi markamız Daphne Baby ile Lajivert ve Aymini distribütörlüklerimizi aynı kurumsal yapı içinde geliştiriyoruz.', cta_text = 'Markalarımızı Keşfedin' WHERE page_id = 1 AND language_id = 2 AND sort_order = 1;
UPDATE hero_slides SET title = 'Kıbrıs’ta Yanınızdayız', subtitle = 'Mağazalarımızda ürünleri yakından inceleyebilir, uzman ekibimizden destek alabilirsiniz. Ada genelinde ücretsiz teslimat ve kurulum sunuyoruz.', cta_text = 'Mağazalarımız' WHERE page_id = 1 AND language_id = 2 AND sort_order = 2;

UPDATE stat_counters SET label = 'Year Founded', value = '2020', suffix = '' WHERE page_id = 1 AND language_id = 1 AND sort_order = 0;
UPDATE stat_counters SET label = 'Customers Reached', value = '3.000+', suffix = '' WHERE page_id = 1 AND language_id = 1 AND sort_order = 1;
UPDATE stat_counters SET label = 'Store Count', value = '2', suffix = '' WHERE page_id = 1 AND language_id = 1 AND sort_order = 2;
UPDATE stat_counters SET label = 'Team Members', value = '10', suffix = '' WHERE page_id = 1 AND language_id = 1 AND sort_order = 3;
UPDATE stat_counters SET label = 'Kuruluş Yılı', value = '2020', suffix = '' WHERE page_id = 1 AND language_id = 2 AND sort_order = 0;
UPDATE stat_counters SET label = 'Ulaşılan Müşteri', value = '3.000+', suffix = '' WHERE page_id = 1 AND language_id = 2 AND sort_order = 1;
UPDATE stat_counters SET label = 'Mağaza Sayısı', value = '2', suffix = '' WHERE page_id = 1 AND language_id = 2 AND sort_order = 2;
UPDATE stat_counters SET label = 'عدد المتاجر', value = '2', suffix = '' WHERE page_id = 1 AND language_id = 3 AND sort_order = 2;
UPDATE stat_counters SET label = 'Ekip Üyesi', value = '10', suffix = '' WHERE page_id = 1 AND language_id = 2 AND sort_order = 3;

UPDATE content_block_translations SET title = 'Distribution & Trade', body = 'We connect the brands we select with the Cyprus market and coordinate product supply and commercial processes.' WHERE block_id = 1 AND language_id = 1;
UPDATE content_block_translations SET title = 'Distribütörlük ve Ticaret', body = 'Seçtiğimiz markaları Kıbrıs pazarıyla buluşturuyor, ürün tedariği ve ticari süreçleri koordineli biçimde yürütüyoruz.' WHERE block_id = 1 AND language_id = 2;
UPDATE content_block_translations SET title = 'Brand Partnerships', body = 'We support brand positioning and local-market growth with a long-term partnership approach.' WHERE block_id = 2 AND language_id = 1;
UPDATE content_block_translations SET title = 'Marka İş Birlikleri', body = 'Markaların konumlandırmasını ve yerel pazardaki gelişimini uzun vadeli iş ortaklığı anlayışıyla destekliyoruz.' WHERE block_id = 2 AND language_id = 2;
UPDATE content_block_translations SET title = 'Retail & After-Sales Service', body = 'We provide store retail, product consultancy, free island-wide delivery and installation, plus warranty and spare-parts support.' WHERE block_id = 3 AND language_id = 1;
UPDATE content_block_translations SET title = 'Perakende ve Satış Sonrası Hizmet', body = 'Mağazacılık, ürün danışmanlığı, ücretsiz ada geneli teslimat ve kurulum ile garanti ve yedek parça desteği sunuyoruz.' WHERE block_id = 3 AND language_id = 2;

UPDATE menu_item_translations SET label = 'Our Brands' WHERE item_id = 4 AND language_id = 1;
UPDATE menu_item_translations SET label = 'Markalarımız' WHERE item_id = 4 AND language_id = 2;
UPDATE menu_item_translations SET label = 'Our Brands' WHERE item_id = 10 AND language_id = 1;
UPDATE menu_item_translations SET label = 'Markalarımız' WHERE item_id = 10 AND language_id = 2;
UPDATE menu_item_translations SET label = 'Our Brand Portfolio' WHERE item_id = 13 AND language_id = 1;
UPDATE menu_item_translations SET label = 'Marka Portföyümüz' WHERE item_id = 13 AND language_id = 2;
UPDATE menu_item_translations SET label = 'Stores' WHERE item_id = 14 AND language_id = 1;
UPDATE menu_item_translations SET label = 'Mağazalar' WHERE item_id = 14 AND language_id = 2;
UPDATE menu_item_translations SET label = 'Partnership Inquiries' WHERE item_id = 15 AND language_id = 1;
UPDATE menu_item_translations SET label = 'İş Birliği Talepleri' WHERE item_id = 15 AND language_id = 2;
UPDATE menu_translations SET title = 'Keşfedin' WHERE menu_id = 3 AND language_id = 2;

UPDATE brand_translations SET tagline = 'Baby, kids and youth rooms', description = 'As Lajivert’s Cyprus distributor, we bring collections for children’s and youth living spaces to our customers.' WHERE brand_id = 1 AND language_id = 1;
UPDATE brand_translations SET tagline = 'Bebek, Çocuk ve Genç Odaları', description = 'Lajivert’in Kıbrıs distribütörlüğünü üstlenerek çocuk ve genç yaşam alanlarına yönelik koleksiyonları müşterilerimizle buluşturuyoruz.' WHERE brand_id = 1 AND language_id = 2;
UPDATE brand_translations SET tagline = 'Baby furniture', description = 'Through the Aymini Cyprus distributorship, we bring baby-room furniture to the local market.' WHERE brand_id = 2 AND language_id = 1;
UPDATE brand_translations SET tagline = 'Bebek Mobilyaları', description = 'Aymini markasının Kıbrıs distribütörlüğüyle bebek odası mobilyalarını yerel pazara sunuyoruz.' WHERE brand_id = 2 AND language_id = 2;

UPDATE stores SET phone = '', email = 'info@ozermanltd.com' WHERE id IN (1, 2);
UPDATE stores SET is_active = 0 WHERE id = 3;
UPDATE store_translations SET name = 'Nicosia Store', slug = 'lefkosa-store', city = 'Cyprus', address = 'Current address and visiting details will be added.', working_hours = 'Visiting hours will be added.' WHERE store_id = 1 AND language_id = 1;
UPDATE store_translations SET name = 'Lefkoşa Mağazası', slug = 'lefkosa-store', city = 'Kıbrıs', address = 'Güncel adres ve ziyaret bilgileri eklenecek.', working_hours = 'Ziyaret saatleri eklenecek.' WHERE store_id = 1 AND language_id = 2;
UPDATE store_translations SET name = 'Famagusta Store', slug = 'gazimagusa-store', city = 'Cyprus', address = 'Current address and visiting details will be added.', working_hours = 'Visiting hours will be added.' WHERE store_id = 2 AND language_id = 1;
UPDATE store_translations SET name = 'Gazimağusa Mağazası', slug = 'gazimagusa-store', city = 'Kıbrıs', address = 'Güncel adres ve ziyaret bilgileri eklenecek.', working_hours = 'Ziyaret saatleri eklenecek.' WHERE store_id = 2 AND language_id = 2;

UPDATE content_block_translations SET title = 'Vision', body = '<p>To be one of the reference companies in retail and distribution in Cyprus, distinguished by a strong brand portfolio and corporate structure.</p><p>To carry the partnerships we build with national and international brands into different sectors and categories, support the growth of the brands we represent in the island market, and raise retail service standards in Cyprus.</p>' WHERE block_id = 8 AND language_id = 1;
UPDATE content_block_translations SET title = 'Vizyon', body = '<p>Kıbrıs’ta perakende ve distribütörlük alanında referans gösterilen, güçlü marka portföyü ve kurumsal yapısıyla öne çıkan şirketlerden biri olmak.</p><p>Ulusal ve uluslararası markalarla geliştirdiğimiz iş birliklerini farklı sektör ve kategorilere taşıyarak, temsil ettiğimiz markaların ada pazarındaki gelişimine katkı sağlamak ve Kıbrıs’ta perakendeciliğin hizmet standartlarını ileriye taşımak.</p>' WHERE block_id = 8 AND language_id = 2;
UPDATE content_block_translations SET title = 'Mission', body = '<p>To work with an approach that understands customer expectations, is careful in product and service selection, and takes responsibility at every stage of shopping.</p><p>To deliver product consultancy, free island-wide delivery and installation, warranty procedures, and spare-parts support in a coordinated way with our professional team.</p><p>To protect the values of the brands we represent and to build stable, transparent, and sustainable commercial relationships for our partners and customers.</p>' WHERE block_id = 9 AND language_id = 1;
UPDATE content_block_translations SET title = 'Misyon', body = '<p>Müşterilerimizin beklentilerini doğru anlayan, ürün ve hizmet seçiminde titizlik gösteren, alışverişin her aşamasında sorumluluk üstlenen bir çalışma anlayışı benimsemek.</p><p>Profesyonel ekibimizle ürün danışmanlığı, ada genelinde ücretsiz teslimat ve kurulum, garanti kapsamındaki işlemler ve yedek parça desteğini koordineli biçimde yürütmek.</p><p>Temsil ettiğimiz markaların değerlerini koruyarak, iş ortaklarımız ve müşterilerimiz için istikrarlı, şeffaf ve sürdürülebilir ticari ilişkiler geliştirmek.</p>' WHERE block_id = 9 AND language_id = 2;

INSERT INTO brands (slug, logo, website_url, sort_order, is_active)
SELECT 'daphne-baby', 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&q=80', '', 2, 1
WHERE NOT EXISTS (SELECT 1 FROM brands WHERE slug = 'daphne-baby');

INSERT INTO brand_translations (brand_id, language_id, name, tagline, description)
SELECT b.id, 1, 'Daphne Baby', 'Özerman Ticaret’s own brand', 'The original brand of our retail journey in baby products.'
FROM brands b
WHERE b.slug = 'daphne-baby'
  AND NOT EXISTS (SELECT 1 FROM brand_translations bt WHERE bt.brand_id = b.id AND bt.language_id = 1);

INSERT INTO brand_translations (brand_id, language_id, name, tagline, description)
SELECT b.id, 2, 'Daphne Baby', 'Özerman Ticaret''in kendi markası', 'Bebek ürünleri alanında başlayan perakende deneyimimizin özgün markası.'
FROM brands b
WHERE b.slug = 'daphne-baby'
  AND NOT EXISTS (SELECT 1 FROM brand_translations bt WHERE bt.brand_id = b.id AND bt.language_id = 2);

INSERT INTO brand_translations (brand_id, language_id, name, tagline, description)
SELECT b.id, 3, 'Daphne Baby', 'العلامة الخاصة بأوزرمان للتجارة', 'العلامة الأصيلة لتجربتنا في تجزئة منتجات الرضع.'
FROM brands b
WHERE b.slug = 'daphne-baby'
  AND NOT EXISTS (SELECT 1 FROM brand_translations bt WHERE bt.brand_id = b.id AND bt.language_id = 3);

-- Correct an already-imported sample database (safe to re-run)
UPDATE page_translations SET title = 'Areas of Work', excerpt = 'Distribution, brand partnerships, and retail service in Cyprus.', meta_title = 'Areas of Work | Özerman Ticaret', meta_description = 'How Özerman Ticaret works: distribution, brand partnerships, and after-sales retail service.' WHERE page_id = 3 AND language_id = 1;
UPDATE page_translations SET title = 'Corporate', excerpt = 'Milestones of Özerman Ticaret.', meta_title = 'Corporate | Özerman Ticaret', meta_description = 'From Bebecik Online and Daphne Baby to Özerman Ticaret in 2020.' WHERE page_id = 5 AND language_id = 1;
UPDATE page_translations SET title = 'Gallery', excerpt = 'Stores and brands.', meta_title = 'Gallery | Özerman Ticaret', meta_description = 'Moments from our stores and the brands we represent in Cyprus.' WHERE page_id = 6 AND language_id = 1;

UPDATE news SET status = 'draft', is_featured = 0 WHERE id > 3;
UPDATE news_translations SET title = 'Bebecik Online', slug = 'bebecik-online', excerpt = 'The entrepreneurial journey that began with online sales in baby products.', content = '<p>Bebecik Online is the first step of the journey that later became Özerman Ticaret.</p>' WHERE news_id = 1 AND language_id = 1;
UPDATE news_translations SET title = 'Daphne Baby', slug = 'daphne-baby-2018', excerpt = 'Daphne Baby opened its store in Famagusta.', content = '<p>In 2018 Daphne Baby opened in Famagusta and became the company’s own brand in baby products.</p>' WHERE news_id = 2 AND language_id = 1;
UPDATE news_translations SET title = 'Özerman Ticaret', slug = 'ozerman-ticaret-2020', excerpt = 'Retail experience became a corporate trading and distribution structure.', content = '<p>Özerman Ticaret was founded in 2020 by Emin Özerman in Cyprus.</p>' WHERE news_id = 3 AND language_id = 1;
UPDATE news SET category_id = 1, status = 'published', is_featured = 1, publish_date = '2016-01-01' WHERE id = 1;
UPDATE news SET category_id = 2, status = 'published', is_featured = 1, publish_date = '2018-01-01' WHERE id = 2;
UPDATE news SET category_id = 3, status = 'published', is_featured = 1, publish_date = '2020-01-01' WHERE id = 3;

UPDATE team_members SET is_active = 0 WHERE id > 1;
UPDATE team_member_translations SET full_name = 'Emin Özerman', position = 'Founder', bio = 'Founded Özerman Ticaret in Cyprus in 2020, building on the Daphne Baby retail journey that began in Famagusta in 2018.' WHERE member_id = 1 AND language_id = 1;

UPDATE offices SET is_active = 0 WHERE id > 1;
UPDATE office_translations SET label = 'Head Office', address = 'Cyprus', city = 'Nicosia', country = 'Cyprus', working_hours = 'Visiting hours will be added.' WHERE office_id = 1 AND language_id = 1;

UPDATE content_block_translations SET title = 'Care', body = 'We are careful in the products and services we choose, and we take responsibility at every stage of the shopping experience.' WHERE block_id = 4 AND language_id = 1;
UPDATE content_block_translations SET title = 'Özen', body = 'Ürün ve hizmet seçiminde titizlik gösterir, alışverişin her aşamasında sorumluluk üstleniriz.' WHERE block_id = 4 AND language_id = 2;
UPDATE content_block_translations SET title = 'Partnership', body = 'We protect the values of the brands we represent and build stable, transparent relationships with partners and customers.' WHERE block_id = 5 AND language_id = 1;
UPDATE content_block_translations SET title = 'İş Birliği', body = 'Temsil ettiğimiz markaların değerlerini korur, iş ortaklarımız ve müşterilerimizle istikrarlı ve şeffaf ilişkiler kurarız.' WHERE block_id = 5 AND language_id = 2;
UPDATE content_block_translations SET title = 'Service', body = 'Product consultancy, free island-wide delivery and installation, warranty work, and spare parts are handled together.' WHERE block_id = 6 AND language_id = 1;
UPDATE content_block_translations SET title = 'Hizmet', body = 'Ürün danışmanlığı, ücretsiz ada geneli teslimat ve kurulum, garanti işlemleri ve yedek parça desteğini birlikte yürütürüz.' WHERE block_id = 6 AND language_id = 2;
UPDATE content_block_translations SET title = 'Local focus', body = 'We work in Cyprus with a long-term view, from our stores in Nicosia and Famagusta.' WHERE block_id = 7 AND language_id = 1;
UPDATE content_block_translations SET title = 'Yerel Odak', body = 'Lefkoşa ve Gazimağusa mağazalarımızla Kıbrıs’ta uzun vadeli bir bakışla çalışırız.' WHERE block_id = 7 AND language_id = 2;

UPDATE menu_item_translations mit
INNER JOIN menu_items mi ON mi.id = mit.item_id
SET mit.label = CASE mit.language_id
    WHEN 1 THEN 'Corporate'
    WHEN 2 THEN 'Kurumsal'
    WHEN 3 THEN 'المؤسسي'
    ELSE mit.label
END
WHERE mi.url IN ('/news', '/en/news', '/tr/news', '/ar/news');

UPDATE menu_items SET is_active = 0 WHERE url IN ('/gallery', '/en/gallery', '/tr/gallery', '/ar/gallery');

UPDATE gallery_collection_translations SET title = 'Our Stores', slug = 'our-stores', description = 'Moments from our Nicosia and Famagusta stores.' WHERE collection_id = 1 AND language_id = 1;
UPDATE gallery_collection_translations SET title = 'Our Brands', slug = 'our-brands', description = 'Daphne Baby, Lajivert, and Aymini in our retail spaces.' WHERE collection_id = 2 AND language_id = 1;
UPDATE gallery_collection_translations SET title = 'Corporate', slug = 'corporate-life', description = 'Our team and day-to-day work in Cyprus.' WHERE collection_id = 3 AND language_id = 1;

DELETE FROM settings WHERE `group` = 'social' AND `key` IN ('linkedin', 'twitter', 'instagram', 'youtube');
