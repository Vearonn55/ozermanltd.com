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

-- Admin user (password: admin123 — change in production!)
INSERT INTO users (name, email, password_hash, role, status) VALUES
('Admin User', 'admin@ozermanltd.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'active');

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
(1, 1, 'Home', 'Ozerman Ltd — Building Tomorrow, Delivering Excellence', 'Ozerman Ltd | International Business Group', 'A diversified international business group operating across trading, construction, real estate, and energy.'),
(2, 1, 'About Us', 'Learn about our history, vision, and leadership team.', 'About Us | Ozerman Ltd', 'Discover Ozerman Ltd — 36+ years of excellence across global markets.'),
(3, 1, 'Business Sectors', 'Explore our diversified portfolio of business sectors.', 'Business Sectors | Ozerman Ltd', 'Trading, construction, real estate, manufacturing, logistics, and energy.'),
(4, 1, 'Projects', 'Browse our project portfolio worldwide.', 'Projects | Ozerman Ltd', 'Residential, commercial, and industrial developments worldwide.'),
(5, 1, 'News', 'Latest news and announcements.', 'News | Ozerman Ltd', 'Stay informed with the latest from Ozerman Ltd.'),
(6, 1, 'Gallery', 'Corporate media gallery.', 'Gallery | Ozerman Ltd', 'Events, project sites, and team culture.'),
(7, 1, 'Contact', 'Get in touch with our team.', 'Contact | Ozerman Ltd', 'Contact Ozerman Ltd — offices in London, Dubai, and Istanbul.');

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
('general', 'site_name', 'Ozerman Ltd', 'text', 'Site Name'),
('general', 'site_tagline', 'Building Tomorrow, Delivering Excellence', 'text', 'Tagline'),
('social', 'linkedin', 'https://linkedin.com/company/ozermanltd', 'text', 'LinkedIn URL'),
('social', 'twitter', 'https://twitter.com/ozermanltd', 'text', 'Twitter URL'),
('analytics', 'plausible_domain', 'ozermanltd.com', 'text', 'Plausible Analytics Domain');

-- Menus
INSERT INTO menus (location) VALUES ('header'), ('footer_col1'), ('footer_col2'), ('footer_col3');

INSERT INTO menu_items (menu_id, url, sort_order) VALUES
(1, '/en', 0), (1, '/en/about-us', 1), (1, '/en/sectors', 2),
(1, '/en/projects', 3), (1, '/en/news', 4), (1, '/en/gallery', 5), (1, '/en/contact', 6);

INSERT INTO menu_item_translations (item_id, language_id, label) VALUES
(1, 1, 'Home'), (2, 1, 'About Us'), (3, 1, 'Sectors'),
(4, 1, 'Projects'), (5, 1, 'News'), (6, 1, 'Gallery'), (7, 1, 'Contact');

-- Stat counters (homepage)
INSERT INTO stat_counters (page_id, language_id, label, value, suffix, sort_order) VALUES
(1, 1, 'Years of Experience', '36+', 'Years', 0),
(1, 1, 'Business Sectors', '12', 'Sectors', 1),
(1, 1, 'Homes Delivered', '4,000+', 'Homes', 2),
(1, 1, 'Countries Worldwide', '28', 'Countries', 3);

-- Team members
INSERT INTO team_members (type, sort_order, is_active) VALUES
('founder', 0, 1), ('management', 1, 1), ('management', 2, 1), ('management', 3, 1);

INSERT INTO team_member_translations (member_id, language_id, full_name, position, bio) VALUES
(1, 1, 'James Ozerman', 'Founder & Chairman', 'Lorem ipsum dolor sit amet — 35+ years of international business experience.'),
(2, 1, 'Sarah Mitchell', 'Chief Executive Officer', 'Lorem ipsum dolor sit amet — 20 years in construction and real estate leadership.'),
(3, 1, 'Ahmet Yılmaz', 'Chief Financial Officer', 'Lorem ipsum dolor sit amet — oversees financial strategy across 28 countries.'),
(4, 1, 'Fatima Al-Rashid', 'Director of International Trade', 'Lorem ipsum dolor sit amet — leads trading division across Middle East, Africa, and Asia.');
