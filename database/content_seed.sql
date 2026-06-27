-- Supplemental content seed for database-driven site
-- Run after seed.sql: mysql -u root -p ozermanltd < database/content_seed.sql

USE ozermanltd;

-- About page structured content (JSON in page_translations.content)
UPDATE page_translations
SET content = '{"history":"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Founded in 1990 by James Ozerman, our company began as a modest trading firm in London and has grown into a diversified international business group with operations spanning 28 countries.</p><p>Over three decades, we have expanded into construction, real estate, manufacturing, logistics, and energy.</p>","vision":"To be the most trusted and innovative international business group, creating lasting value for our stakeholders and the communities we serve.","mission":"To deliver excellence across every sector we operate in, through strategic investment, operational expertise, and a relentless commitment to sustainable business practices."}'
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
('hero-1.jpg', 'hero-1.jpg', 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1600&q=80', 'image', 'image/jpeg', 'Ozerman corporate skyline'),
('hero-2.jpg', 'hero-2.jpg', 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1600&q=80', 'image', 'image/jpeg', 'Ozerman project development'),
('hero-3.jpg', 'hero-3.jpg', 'https://images.unsplash.com/photo-1578575437130-527eed3abbcd?w=1600&q=80', 'image', 'image/jpeg', 'Ozerman global trade'),
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

INSERT INTO hero_slides (page_id, language_id, media_id, title, subtitle, cta_text, cta_url, sort_order) VALUES
(1, 1, 1, 'Building a Global Legacy of Excellence', 'Ozerman Ltd is a diversified international business group operating across trading, construction, real estate, and energy sectors.', 'Explore Our Sectors', '/en/sectors', 0),
(1, 1, 2, 'Delivering Landmark Projects Worldwide', 'From residential developments to commercial complexes, we create spaces that inspire communities and drive economic growth.', 'View Projects', '/en/projects', 1),
(1, 1, 3, 'Trusted Partner in International Trade', 'Connecting markets across continents with integrity, efficiency, and a commitment to sustainable business practices.', 'Get in Touch', '/en/contact', 2);

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
