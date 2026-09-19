-- Supplemental content seed for database-driven site
-- Run after seed.sql against the SAME selected database (e.g. ozermanl_MAIN).

-- About page structured content (JSON in page_translations.content)
-- Multilingual payload matches DummyData::aboutContent() (TR frontend reference)
UPDATE page_translations
SET content = '{"history":{"en":"<p>Özerman Ticaret is an importer limited company built on disciplined trade, reliable partnerships, and a growing retail footprint. Lorem ipsum dolor sit amet — placeholder history text that will be replaced with the official company story.</p><p>Today we focus on importing and representing selected brands — including furniture lines such as Lajivert and Aymini — while remaining open to broader product categories that fit our wholesale and retail model.</p>","tr":"<p>Özerman Ticaret; disiplinli ticaret, güvenilir ortaklıklar ve büyüyen bir perakende ağı üzerine kurulu bir ithalatçı limited şirkettir. Lorem ipsum dolor sit amet — resmi şirket hikâyesiyle değiştirilecek geçici metin.</p><p>Bugün Lajivert ve Aymini gibi seçili mobilya markalarını ithal edip temsil ederken, toptan ve perakende modelimize uyan daha geniş kategorilere de açığız.</p>","ar":"<p>أوزرمان للتجارة شركة استيراد محدودة مبنية على تجارة منضبطة وشراكات موثوقة وحضور تجزئة متنامٍ. نص مؤقت سيُستبدل بالقصة الرسمية.</p><p>نركّز اليوم على استيراد وتمثيل علامات مختارة — بما في ذلك خطوط أثاث مثل لاجيڤيرت وأيميني — مع الانفتاح على فئات أوسع تناسب نموذج الجملة والتجزئة.</p>"},"vision":{"en":"To be a trusted importer and retail partner — connecting quality brands with customers through integrity and local service.","tr":"Kaliteli markaları dürüstlük ve yerel hizmetle müşterilere bağlayan, güvenilir bir ithalatçı ve perakende ortağı olmak.","ar":"أن نكون مستوردًا وشريك تجزئة موثوقًا — نربط العلامات الجيدة بالعملاء عبر النزاهة والخدمة المحلية."},"mission":{"en":"To import with care, represent brands responsibly, and deliver a clear retail experience across our stores and partner channels.","tr":"Özenle ithal etmek, markaları sorumlu şekilde temsil etmek ve mağazalarımız ile iş ortaklığı kanallarımızda net bir perakende deneyimi sunmak.","ar":"الاستيراد بعناية، وتمثيل العلامات بمسؤولية، وتقديم تجربة تجزئة واضحة عبر متاجرنا وقنوات الشركاء."}}'
WHERE page_id = 2 AND language_id = 1;

-- Sector services
UPDATE sector_translations SET services_text = '• International commodity trading\n• Import & export logistics\n• Supply chain optimization\n• Market analysis & advisory' WHERE sector_id = 1 AND language_id = 1;
UPDATE sector_translations SET services_text = '• Commercial & residential construction\n• Infrastructure development\n• Project management\n• Design-build solutions' WHERE sector_id = 2 AND language_id = 1;
UPDATE sector_translations SET services_text = '• Property development\n• Investment advisory\n• Property management\n• Sales & leasing' WHERE sector_id = 3 AND language_id = 1;
UPDATE sector_translations SET services_text = '• Industrial manufacturing\n• Quality assurance\n• Custom fabrication\n• Export packaging' WHERE sector_id = 4 AND language_id = 1;
UPDATE sector_translations SET services_text = '• Freight forwarding\n• Warehousing & distribution\n• Customs clearance\n• Last-mile delivery' WHERE sector_id = 5 AND language_id = 1;
UPDATE sector_translations SET services_text = '• Renewable energy projects\n• Solar & wind installations\n• Energy consulting\n• Power distribution' WHERE sector_id = 6 AND language_id = 1;

-- Project features
UPDATE project_translations SET features = '• Infinity pool & spa\n• 24/7 concierge service\n• Smart home technology\n• Underground parking\n• Landscaped gardens' WHERE project_id = 1 AND language_id = 1;
UPDATE project_translations SET features = '• LEED Platinum certified\n• 85,000 sqm office space\n• Retail & dining podium\n• Sky lounge & terrace\n• EV charging stations' WHERE project_id = 2 AND language_id = 1;
UPDATE project_translations SET features = '• 250-hectare development\n• Rail & highway access\n• Renewable energy grid\n• Worker housing complex\n• R&D innovation center' WHERE project_id = 3 AND language_id = 1;
UPDATE project_translations SET features = '• Private beach access\n• Infinity-edge pools\n• Mediterranean architecture\n• 24/7 security\n• Clubhouse & wellness center' WHERE project_id = 4 AND language_id = 1;

-- Media assets (external URLs for development)
INSERT INTO media (file_name, original_name, file_path, file_type, mime_type, alt_text) VALUES
('hero-1.jpg', 'hero-1.jpg', 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1600&q=80', 'image', 'image/jpeg', 'Import logistics warehouse'),
('hero-2.jpg', 'hero-2.jpg', 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1600&q=80', 'image', 'image/jpeg', 'Furniture brand partnership'),
('hero-3.jpg', 'hero-3.jpg', 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&q=80', 'image', 'image/jpeg', 'Retail showroom'),
('project-1.jpg', 'project-1.jpg', 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&q=80', 'image', 'image/jpeg', 'Marina Heights Residences'),
('project-2.jpg', 'project-2.jpg', 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80', 'image', 'image/jpeg', 'Central Business Tower'),
('project-3.jpg', 'project-3.jpg', 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&q=80', 'image', 'image/jpeg', 'Greenfield Industrial Park'),
('project-4.jpg', 'project-4.jpg', 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&q=80', 'image', 'image/jpeg', 'Seaside Villa Collection'),
('news-1.jpg', 'news-1.jpg', 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=800&q=80', 'image', 'image/jpeg', 'Renewable energy'),
('gallery-cover-1.jpg', 'gallery-cover-1.jpg', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80', 'image', 'image/jpeg', 'Corporate events'),
('gallery-item-1.jpg', 'gallery-item-1.jpg', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80', 'image', 'image/jpeg', 'Leadership summit'),
('gallery-item-2.jpg', 'gallery-item-2.jpg', 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=600&q=80', 'image', 'image/jpeg', 'Awards ceremony'),
('gallery-item-3.jpg', 'gallery-item-3.jpg', 'https://images.unsplash.com/photo-1505373877841-8d25f39d4666?w=600&q=80', 'image', 'image/jpeg', 'Partners forum');

UPDATE projects SET featured_image_id = 4 WHERE id = 1;
UPDATE projects SET featured_image_id = 5 WHERE id = 2;
UPDATE projects SET featured_image_id = 6 WHERE id = 3;
UPDATE projects SET featured_image_id = 7 WHERE id = 4;
UPDATE news SET featured_image_id = 8 WHERE id = 1;

-- Hero slides for all locales (matches DummyData::heroSlides — TR frontend reference)
INSERT INTO hero_slides (page_id, language_id, media_id, title, subtitle, cta_text, cta_url, sort_order) VALUES
-- English
(1, 1, 1, 'Import. Partner. Deliver.', 'Özerman Ticaret is an importer limited company connecting trusted brands with retail markets through disciplined trade and local presence.', 'About Us', '/en/about-us', 0),
(1, 1, 2, 'Brands We Represent', 'We build long-term import partnerships — currently bringing selected furniture brands such as Lajivert and Aymini to retail customers.', 'Our Partnerships', '/en/partnerships', 1),
(1, 1, 3, 'Visit Our Stores', 'Experience our imported collections in carefully curated showrooms — placeholders for store details will be updated soon.', 'Find a Store', '/en/our-stores', 2),
-- Turkish
(1, 2, 1, 'İthalat. Ortaklık. Dağıtım.', 'Özerman Ticaret, güvenilir markaları disiplinli ticaret ve yerel varlıkla perakende pazarlara bağlayan bir ithalatçı limited şirketidir.', 'Hakkımızda', '/tr/about-us', 0),
(1, 2, 2, 'Temsil Ettiğimiz Markalar', 'Uzun soluklu ithalat ortaklıkları kuruyoruz — şu anda Lajivert ve Aymini gibi seçili mobilya markalarını perakende müşterilere sunuyoruz.', 'İş Ortaklıklarımız', '/tr/partnerships', 1),
(1, 2, 3, 'Mağazalarımızı Ziyaret Edin', 'İthal koleksiyonlarımızı özenle düzenlenmiş showroomlarda deneyimleyin — mağaza detayları yakında güncellenecektir.', 'Mağaza Bul', '/tr/our-stores', 2),
-- Arabic
(1, 3, 1, 'استيراد. شراكة. توصيل.', 'أوزرمان للتجارة شركة استيراد محدودة تربط العلامات الموثوقة بأسواق التجزئة عبر تجارة منضبطة وحضور محلي.', 'من نحن', '/ar/about-us', 0),
(1, 3, 2, 'العلامات التي نمثلها', 'نبني شراكات استيراد طويلة الأمد — ونقدم حاليًا علامات أثاث مختارة مثل لاجيڤيرت وأيميني لعملاء التجزئة.', 'شراكاتنا', '/ar/partnerships', 1),
(1, 3, 3, 'زوروا متاجرنا', 'اختبروا مجموعاتنا المستوردة في صالات عرض منسقة بعناية — سيتم تحديث تفاصيل المتاجر قريبًا.', 'اعثر على متجر', '/ar/our-stores', 2);

INSERT INTO gallery_collections (cover_image_id, sort_order) VALUES
(9, 0), (9, 1), (9, 2);

INSERT INTO gallery_collection_translations (collection_id, language_id, title, slug, description) VALUES
(1, 1, 'Corporate Events', 'corporate-events', 'Highlights from annual conferences, award ceremonies, and leadership summits.'),
(2, 1, 'Project Sites', 'project-sites', 'Construction progress and completed developments across our global portfolio.'),
(3, 1, 'Team & Culture', 'team-culture', 'Our people, workplace culture, and community engagement initiatives.');

INSERT INTO gallery_items (collection_id, media_id, sort_order, caption) VALUES
(1, 10, 0, 'Annual Leadership Summit 2025'),
(1, 11, 1, 'Excellence Awards Ceremony'),
(1, 12, 2, 'International Partners Forum');
