-- Özerman Ticaret — single phpMyAdmin import
-- In phpMyAdmin, select the existing database (ozermanl_MAIN), then Import this file.
-- Do not create a database from this file. Re-import replaces every table.
-- Login: admin@ozermanltd.com
-- Includes the public website content: pages, menus, hero, brands, stores, and corporate milestones.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_sessions`;
DROP TABLE IF EXISTS `user_activity_log`;
DROP TABLE IF EXISTS `languages`;
DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `page_translations`;
DROP TABLE IF EXISTS `media_folders`;
DROP TABLE IF EXISTS `media`;
DROP TABLE IF EXISTS `site_banners`;
DROP TABLE IF EXISTS `hero_slides`;
DROP TABLE IF EXISTS `stat_counters`;
DROP TABLE IF EXISTS `menus`;
DROP TABLE IF EXISTS `menu_items`;
DROP TABLE IF EXISTS `menu_translations`;
DROP TABLE IF EXISTS `menu_item_translations`;
DROP TABLE IF EXISTS `sectors`;
DROP TABLE IF EXISTS `sector_translations`;
DROP TABLE IF EXISTS `subsidiaries`;
DROP TABLE IF EXISTS `subsidiary_translations`;
DROP TABLE IF EXISTS `brands`;
DROP TABLE IF EXISTS `brand_translations`;
DROP TABLE IF EXISTS `stores`;
DROP TABLE IF EXISTS `store_translations`;
DROP TABLE IF EXISTS `content_blocks`;
DROP TABLE IF EXISTS `content_block_translations`;
DROP TABLE IF EXISTS `team_members`;
DROP TABLE IF EXISTS `team_member_translations`;
DROP TABLE IF EXISTS `awards`;
DROP TABLE IF EXISTS `award_translations`;
DROP TABLE IF EXISTS `project_categories`;
DROP TABLE IF EXISTS `project_category_translations`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `project_translations`;
DROP TABLE IF EXISTS `project_galleries`;
DROP TABLE IF EXISTS `project_virtual_tours`;
DROP TABLE IF EXISTS `project_unit_types`;
DROP TABLE IF EXISTS `news_categories`;
DROP TABLE IF EXISTS `news_category_translations`;
DROP TABLE IF EXISTS `news_tags`;
DROP TABLE IF EXISTS `news_tag_translations`;
DROP TABLE IF EXISTS `news`;
DROP TABLE IF EXISTS `news_translations`;
DROP TABLE IF EXISTS `news_tag_pivot`;
DROP TABLE IF EXISTS `news_comments`;
DROP TABLE IF EXISTS `gallery_collections`;
DROP TABLE IF EXISTS `gallery_collection_translations`;
DROP TABLE IF EXISTS `gallery_items`;
DROP TABLE IF EXISTS `offices`;
DROP TABLE IF EXISTS `office_translations`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `job_departments`;
DROP TABLE IF EXISTS `job_department_translations`;
DROP TABLE IF EXISTS `job_listings`;
DROP TABLE IF EXISTS `job_listing_translations`;
DROP TABLE IF EXISTS `job_applications`;
DROP TABLE IF EXISTS `seo_meta`;
DROP TABLE IF EXISTS `redirects`;
DROP TABLE IF EXISTS `sitemaps`;
DROP TABLE IF EXISTS `content_revisions`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `visitor_profiles`;
DROP TABLE IF EXISTS `consent_records`;
DROP TABLE IF EXISTS `visitor_events`;
DROP TABLE IF EXISTS `analytics_sync_queue`;

CREATE TABLE users (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name            VARCHAR(120)  NOT NULL,
  email           VARCHAR(180)  NOT NULL UNIQUE,
  password_hash   VARCHAR(255)  NOT NULL,
  role            VARCHAR(30)   NOT NULL DEFAULT 'editor' COMMENT 'super_admin | content_manager | editor',
  status          VARCHAR(20)   NOT NULL DEFAULT 'active' COMMENT 'active | inactive | suspended',
  avatar          VARCHAR(255)  NULL,
  reset_token     VARCHAR(100)  NULL,
  reset_expires   TIMESTAMP     NULL,
  last_login_at   TIMESTAMP     NULL,
  created_at      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE user_sessions (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id        INT UNSIGNED NOT NULL,
  session_token  VARCHAR(255) NOT NULL UNIQUE,
  ip_address     VARCHAR(45)  NULL,
  user_agent     TEXT         NULL,
  expires_at     TIMESTAMP    NOT NULL,
  created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_user_sessions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE user_activity_log (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id      INT UNSIGNED NOT NULL,
  action       VARCHAR(80)  NOT NULL,
  entity_type  VARCHAR(80)  NULL,
  entity_id    INT UNSIGNED NULL,
  payload      JSON         NULL,
  ip_address   VARCHAR(45)  NULL,
  created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_user_activity_log_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE languages (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code        VARCHAR(5)   NOT NULL UNIQUE COMMENT 'en | tr | ar',
  name        VARCHAR(60)  NOT NULL,
  flag_icon   VARCHAR(100) NULL,
  is_default  TINYINT(1)   NOT NULL DEFAULT 0,
  is_active   TINYINT(1)   NOT NULL DEFAULT 1,
  sort_order  INT          NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE pages (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug           VARCHAR(160) NOT NULL UNIQUE,
  custom_path    VARCHAR(255) NULL,
  template       VARCHAR(80)  NOT NULL DEFAULT 'default',
  embed_mode     VARCHAR(20)  NOT NULL DEFAULT 'site',
  status         VARCHAR(20)  NOT NULL DEFAULT 'draft',
  show_in_nav    TINYINT(1)   NOT NULL DEFAULT 1,
  sort_order     INT          NOT NULL DEFAULT 0,
  parent_id      INT UNSIGNED NULL,
  banner_media_id INT UNSIGNED NULL,
  published_at   TIMESTAMP    NULL,
  created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_pages_custom_path (custom_path),
  CONSTRAINT fk_pages_parent FOREIGN KEY (parent_id) REFERENCES pages(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE page_translations (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page_id           INT UNSIGNED NOT NULL,
  language_id       INT UNSIGNED NOT NULL,
  title             VARCHAR(255) NOT NULL,
  content           LONGTEXT     NULL,
  excerpt           TEXT         NULL,
  html_embed        LONGTEXT     NULL,
  css_embed         LONGTEXT     NULL,
  js_embed          LONGTEXT     NULL,
  php_embed         LONGTEXT     NULL,
  meta_title        VARCHAR(160) NULL,
  meta_description  VARCHAR(320) NULL,
  UNIQUE KEY uq_page_lang (page_id, language_id),
  CONSTRAINT fk_page_translations_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
  CONSTRAINT fk_page_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE media_folders (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  parent_id  INT UNSIGNED NULL,
  name       VARCHAR(120) NOT NULL,
  path       VARCHAR(500) NOT NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_media_folders_parent FOREIGN KEY (parent_id) REFERENCES media_folders(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE media (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  folder_id       INT UNSIGNED NULL,
  uploaded_by     INT UNSIGNED NULL,
  file_name       VARCHAR(255) NOT NULL,
  original_name   VARCHAR(255) NOT NULL,
  file_path       VARCHAR(500) NOT NULL,
  webp_path       VARCHAR(500) NULL,
  avif_path       VARCHAR(500) NULL,
  thumbnail_path  VARCHAR(500) NULL,
  file_type       VARCHAR(20)  NOT NULL,
  mime_type       VARCHAR(100) NULL,
  file_size       INT UNSIGNED NULL,
  width           INT UNSIGNED NULL,
  height          INT UNSIGNED NULL,
  duration_sec    INT UNSIGNED NULL,
  alt_text        VARCHAR(255) NULL,
  caption         TEXT         NULL,
  is_active       TINYINT(1)   NOT NULL DEFAULT 1,
  created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_media_folder FOREIGN KEY (folder_id) REFERENCES media_folders(id) ON DELETE SET NULL,
  CONSTRAINT fk_media_uploaded_by FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE pages
  ADD CONSTRAINT fk_pages_banner_media FOREIGN KEY (banner_media_id) REFERENCES media(id) ON DELETE SET NULL;

CREATE TABLE site_banners (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  location     VARCHAR(60)  NOT NULL UNIQUE,
  media_id     INT UNSIGNED NULL,
  fallback_url VARCHAR(500) NULL,
  updated_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_site_banners_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE hero_slides (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page_id     INT UNSIGNED NOT NULL,
  language_id INT UNSIGNED NOT NULL,
  media_id    INT UNSIGNED NULL,
  title       VARCHAR(255) NULL,
  subtitle    VARCHAR(255) NULL,
  cta_text    VARCHAR(80)  NULL,
  cta_url     VARCHAR(255) NULL,
  sort_order  INT          NOT NULL DEFAULT 0,
  is_active   TINYINT(1)   NOT NULL DEFAULT 1,
  CONSTRAINT fk_hero_slides_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
  CONSTRAINT fk_hero_slides_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE,
  CONSTRAINT fk_hero_slides_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE stat_counters (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page_id     INT UNSIGNED NOT NULL,
  language_id INT UNSIGNED NOT NULL,
  label       VARCHAR(120) NOT NULL,
  value       VARCHAR(60)  NOT NULL,
  suffix      VARCHAR(40)  NULL,
  icon        VARCHAR(80)  NULL,
  sort_order  INT          NOT NULL DEFAULT 0,
  CONSTRAINT fk_stat_counters_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
  CONSTRAINT fk_stat_counters_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE menus (
  id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  location  VARCHAR(60) NOT NULL UNIQUE,
  is_active TINYINT(1)  NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE menu_items (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  menu_id    INT UNSIGNED NOT NULL,
  parent_id  INT UNSIGNED NULL,
  url        VARCHAR(255) NULL,
  target     VARCHAR(10)  NOT NULL DEFAULT '_self',
  icon       VARCHAR(80)  NULL,
  sort_order INT          NOT NULL DEFAULT 0,
  is_active  TINYINT(1)   NOT NULL DEFAULT 1,
  CONSTRAINT fk_menu_items_menu FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE,
  CONSTRAINT fk_menu_items_parent FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE menu_translations (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  menu_id      INT UNSIGNED NOT NULL,
  language_id  INT UNSIGNED NOT NULL,
  title        VARCHAR(120) NULL,
  UNIQUE KEY uq_menu_lang (menu_id, language_id),
  CONSTRAINT fk_menu_translations_menu FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE,
  CONSTRAINT fk_menu_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE menu_item_translations (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  item_id     INT UNSIGNED NOT NULL,
  language_id INT UNSIGNED NOT NULL,
  label       VARCHAR(120) NOT NULL,
  UNIQUE KEY uq_menu_item_lang (item_id, language_id),
  CONSTRAINT fk_menu_item_translations_item FOREIGN KEY (item_id) REFERENCES menu_items(id) ON DELETE CASCADE,
  CONSTRAINT fk_menu_item_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE sectors (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  icon       VARCHAR(80) NULL,
  color      VARCHAR(20) NULL,
  media_id   INT UNSIGNED NULL,
  sort_order INT         NOT NULL DEFAULT 0,
  is_active  TINYINT(1)  NOT NULL DEFAULT 1,
  created_at TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_sectors_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sector_translations (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sector_id         INT UNSIGNED NOT NULL,
  language_id       INT UNSIGNED NOT NULL,
  name              VARCHAR(120) NOT NULL,
  slug              VARCHAR(160) NOT NULL,
  overview          TEXT         NULL,
  services_text     TEXT         NULL,
  meta_title        VARCHAR(160) NULL,
  meta_description  VARCHAR(320) NULL,
  UNIQUE KEY uq_sector_lang (sector_id, language_id),
  UNIQUE KEY uq_sector_slug (slug),
  CONSTRAINT fk_sector_translations_sector FOREIGN KEY (sector_id) REFERENCES sectors(id) ON DELETE CASCADE,
  CONSTRAINT fk_sector_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE subsidiaries (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sector_id       INT UNSIGNED NULL,
  logo            VARCHAR(255) NULL,
  website_url     VARCHAR(255) NULL,
  sort_order      INT          NOT NULL DEFAULT 0,
  is_active       TINYINT(1)   NOT NULL DEFAULT 1,
  founded_at      DATE         NULL,
  created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_subsidiaries_sector FOREIGN KEY (sector_id) REFERENCES sectors(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE subsidiary_translations (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  subsidiary_id     INT UNSIGNED NOT NULL,
  language_id       INT UNSIGNED NOT NULL,
  name              VARCHAR(120) NOT NULL,
  slug              VARCHAR(160) NOT NULL,
  description       TEXT         NULL,
  services          TEXT         NULL,
  meta_title        VARCHAR(160) NULL,
  meta_description  VARCHAR(320) NULL,
  UNIQUE KEY uq_subsidiary_lang (subsidiary_id, language_id),
  UNIQUE KEY uq_subsidiary_slug (slug),
  CONSTRAINT fk_subsidiary_translations_subsidiary FOREIGN KEY (subsidiary_id) REFERENCES subsidiaries(id) ON DELETE CASCADE,
  CONSTRAINT fk_subsidiary_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE brands (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  subsidiary_id  INT UNSIGNED NULL,
  slug           VARCHAR(160) NULL UNIQUE,
  media_id       INT UNSIGNED NULL,
  logo           VARCHAR(255) NULL,
  website_url    VARCHAR(255) NULL,
  sort_order     INT          NOT NULL DEFAULT 0,
  is_active      TINYINT(1)   NOT NULL DEFAULT 1,
  CONSTRAINT fk_brands_subsidiary FOREIGN KEY (subsidiary_id) REFERENCES subsidiaries(id) ON DELETE CASCADE,
  CONSTRAINT fk_brands_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE brand_translations (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  brand_id     INT UNSIGNED NOT NULL,
  language_id  INT UNSIGNED NOT NULL,
  name         VARCHAR(120) NOT NULL,
  tagline      VARCHAR(255) NULL,
  description  TEXT         NULL,
  UNIQUE KEY uq_brand_lang (brand_id, language_id),
  CONSTRAINT fk_brand_translations_brand FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE,
  CONSTRAINT fk_brand_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE stores (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  media_id    INT UNSIGNED NULL,
  phone       VARCHAR(40)  NULL,
  email       VARCHAR(180) NULL,
  sort_order  INT          NOT NULL DEFAULT 0,
  is_active   TINYINT(1)   NOT NULL DEFAULT 1,
  created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_stores_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE store_translations (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  store_id       INT UNSIGNED NOT NULL,
  language_id    INT UNSIGNED NOT NULL,
  name           VARCHAR(160) NOT NULL,
  slug           VARCHAR(180) NOT NULL,
  city           VARCHAR(80)  NULL,
  address        TEXT         NULL,
  working_hours  VARCHAR(255) NULL,
  UNIQUE KEY uq_store_lang (store_id, language_id),
  CONSTRAINT fk_store_translations_store FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE,
  CONSTRAINT fk_store_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE content_blocks (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  area        VARCHAR(60)  NOT NULL,
  icon        VARCHAR(80)  NULL,
  sort_order  INT          NOT NULL DEFAULT 0,
  is_active   TINYINT(1)   NOT NULL DEFAULT 1,
  KEY idx_content_blocks_area (area, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE content_block_translations (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  block_id     INT UNSIGNED NOT NULL,
  language_id  INT UNSIGNED NOT NULL,
  title        VARCHAR(200) NULL,
  body         TEXT         NULL,
  UNIQUE KEY uq_content_block_lang (block_id, language_id),
  CONSTRAINT fk_content_block_translations_block FOREIGN KEY (block_id) REFERENCES content_blocks(id) ON DELETE CASCADE,
  CONSTRAINT fk_content_block_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE team_members (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  subsidiary_id  INT UNSIGNED NULL,
  media_id       INT UNSIGNED NULL,
  email          VARCHAR(180) NULL,
  phone          VARCHAR(40)  NULL,
  type           VARCHAR(30)  NOT NULL,
  sort_order     INT          NOT NULL DEFAULT 0,
  is_active      TINYINT(1)   NOT NULL DEFAULT 1,
  created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_team_members_subsidiary FOREIGN KEY (subsidiary_id) REFERENCES subsidiaries(id) ON DELETE SET NULL,
  CONSTRAINT fk_team_members_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE team_member_translations (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  member_id    INT UNSIGNED NOT NULL,
  language_id  INT UNSIGNED NOT NULL,
  full_name    VARCHAR(120) NOT NULL,
  position     VARCHAR(120) NULL,
  bio          TEXT         NULL,
  UNIQUE KEY uq_team_member_lang (member_id, language_id),
  CONSTRAINT fk_team_member_translations_member FOREIGN KEY (member_id) REFERENCES team_members(id) ON DELETE CASCADE,
  CONSTRAINT fk_team_member_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE awards (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  subsidiary_id  INT UNSIGNED NULL,
  media_id       INT UNSIGNED NULL,
  year           SMALLINT     NOT NULL,
  organizer      VARCHAR(120) NULL,
  sort_order     INT          NOT NULL DEFAULT 0,
  created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_awards_subsidiary FOREIGN KEY (subsidiary_id) REFERENCES subsidiaries(id) ON DELETE SET NULL,
  CONSTRAINT fk_awards_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE award_translations (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  award_id     INT UNSIGNED NOT NULL,
  language_id  INT UNSIGNED NOT NULL,
  title        VARCHAR(200) NOT NULL,
  category     VARCHAR(120) NULL,
  description  TEXT         NULL,
  UNIQUE KEY uq_award_lang (award_id, language_id),
  CONSTRAINT fk_award_translations_award FOREIGN KEY (award_id) REFERENCES awards(id) ON DELETE CASCADE,
  CONSTRAINT fk_award_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE project_categories (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  icon       VARCHAR(80) NULL,
  sort_order INT         NOT NULL DEFAULT 0,
  is_active  TINYINT(1)  NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE project_category_translations (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id  INT UNSIGNED NOT NULL,
  language_id  INT UNSIGNED NOT NULL,
  name         VARCHAR(120) NOT NULL,
  slug         VARCHAR(160) NOT NULL,
  UNIQUE KEY uq_project_category_lang (category_id, language_id),
  CONSTRAINT fk_project_category_translations_category FOREIGN KEY (category_id) REFERENCES project_categories(id) ON DELETE CASCADE,
  CONSTRAINT fk_project_category_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE projects (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id       INT UNSIGNED NULL,
  subsidiary_id     INT UNSIGNED NULL,
  featured_image_id INT UNSIGNED NULL,
  status            VARCHAR(20)    NOT NULL DEFAULT 'planning',
  location          VARCHAR(255)   NULL,
  latitude          DECIMAL(10,7)  NULL,
  longitude         DECIMAL(10,7)  NULL,
  total_units       INT UNSIGNED   NULL,
  delivery_date     VARCHAR(40)    NULL,
  start_price       DECIMAL(15,2)  NULL,
  currency          VARCHAR(5)     NULL DEFAULT 'TRY',
  is_featured       TINYINT(1)     NOT NULL DEFAULT 0,
  is_active         TINYINT(1)     NOT NULL DEFAULT 1,
  created_at        TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_projects_category FOREIGN KEY (category_id) REFERENCES project_categories(id) ON DELETE SET NULL,
  CONSTRAINT fk_projects_subsidiary FOREIGN KEY (subsidiary_id) REFERENCES subsidiaries(id) ON DELETE SET NULL,
  CONSTRAINT fk_projects_featured_image FOREIGN KEY (featured_image_id) REFERENCES media(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE project_translations (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id        INT UNSIGNED NOT NULL,
  language_id       INT UNSIGNED NOT NULL,
  title             VARCHAR(255) NOT NULL,
  slug              VARCHAR(280) NOT NULL,
  description       LONGTEXT     NULL,
  features          TEXT         NULL,
  payment_plan      TEXT         NULL,
  meta_title        VARCHAR(160) NULL,
  meta_description  VARCHAR(320) NULL,
  UNIQUE KEY uq_project_lang (project_id, language_id),
  UNIQUE KEY uq_project_slug (slug),
  CONSTRAINT fk_project_translations_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  CONSTRAINT fk_project_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE project_galleries (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id  INT UNSIGNED NOT NULL,
  media_id    INT UNSIGNED NOT NULL,
  caption     VARCHAR(255) NULL,
  type        VARCHAR(20)  NOT NULL DEFAULT 'exterior',
  sort_order  INT          NOT NULL DEFAULT 0,
  CONSTRAINT fk_project_galleries_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  CONSTRAINT fk_project_galleries_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE project_virtual_tours (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id     INT UNSIGNED NOT NULL,
  tour_url       VARCHAR(500) NOT NULL,
  thumbnail_url  VARCHAR(500) NULL,
  label          VARCHAR(120) NULL,
  vr_compatible  TINYINT(1)   NOT NULL DEFAULT 0,
  sort_order     INT          NOT NULL DEFAULT 0,
  CONSTRAINT fk_project_virtual_tours_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE project_unit_types (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id       INT UNSIGNED NOT NULL,
  label            VARCHAR(80)    NOT NULL,
  bedrooms         SMALLINT       NULL,
  size_sqm         DECIMAL(8,2)   NULL,
  price_from       DECIMAL(15,2)  NULL,
  currency         VARCHAR(5)     NULL DEFAULT 'GBP',
  available_units  INT UNSIGNED   NULL,
  total_units      INT UNSIGNED   NULL,
  CONSTRAINT fk_project_unit_types_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE news_categories (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  color      VARCHAR(20) NULL,
  icon       VARCHAR(80) NULL,
  sort_order INT         NOT NULL DEFAULT 0,
  is_active  TINYINT(1)  NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE news_category_translations (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id  INT UNSIGNED NOT NULL,
  language_id  INT UNSIGNED NOT NULL,
  name         VARCHAR(120) NOT NULL,
  slug         VARCHAR(160) NOT NULL,
  UNIQUE KEY uq_news_category_lang (category_id, language_id),
  CONSTRAINT fk_news_category_translations_category FOREIGN KEY (category_id) REFERENCES news_categories(id) ON DELETE CASCADE,
  CONSTRAINT fk_news_category_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE news_tags (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE news_tag_translations (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tag_id       INT UNSIGNED NOT NULL,
  language_id  INT UNSIGNED NOT NULL,
  name         VARCHAR(80)  NOT NULL,
  slug         VARCHAR(100) NOT NULL,
  UNIQUE KEY uq_news_tag_lang (tag_id, language_id),
  CONSTRAINT fk_news_tag_translations_tag FOREIGN KEY (tag_id) REFERENCES news_tags(id) ON DELETE CASCADE,
  CONSTRAINT fk_news_tag_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE news (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id       INT UNSIGNED NULL,
  subsidiary_id     INT UNSIGNED NULL,
  featured_image_id INT UNSIGNED NULL,
  author_id         INT UNSIGNED NULL,
  status            VARCHAR(20) NOT NULL DEFAULT 'draft',
  publish_date      TIMESTAMP   NULL,
  is_featured       TINYINT(1)  NOT NULL DEFAULT 0,
  is_pinned         TINYINT(1)  NOT NULL DEFAULT 0,
  view_count        INT UNSIGNED NOT NULL DEFAULT 0,
  created_at        TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_news_category FOREIGN KEY (category_id) REFERENCES news_categories(id) ON DELETE SET NULL,
  CONSTRAINT fk_news_subsidiary FOREIGN KEY (subsidiary_id) REFERENCES subsidiaries(id) ON DELETE SET NULL,
  CONSTRAINT fk_news_featured_image FOREIGN KEY (featured_image_id) REFERENCES media(id) ON DELETE SET NULL,
  CONSTRAINT fk_news_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE news_translations (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  news_id           INT UNSIGNED NOT NULL,
  language_id       INT UNSIGNED NOT NULL,
  title             VARCHAR(255) NOT NULL,
  slug              VARCHAR(280) NOT NULL,
  content           LONGTEXT     NULL,
  excerpt           TEXT         NULL,
  meta_title        VARCHAR(160) NULL,
  meta_description  VARCHAR(320) NULL,
  UNIQUE KEY uq_news_lang (news_id, language_id),
  UNIQUE KEY uq_news_slug (slug),
  CONSTRAINT fk_news_translations_news FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE,
  CONSTRAINT fk_news_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE news_tag_pivot (
  news_id  INT UNSIGNED NOT NULL,
  tag_id   INT UNSIGNED NOT NULL,
  PRIMARY KEY (news_id, tag_id),
  CONSTRAINT fk_news_tag_pivot_news FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE,
  CONSTRAINT fk_news_tag_pivot_tag FOREIGN KEY (tag_id) REFERENCES news_tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE news_comments (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  news_id      INT UNSIGNED NOT NULL,
  author_name  VARCHAR(100) NOT NULL,
  author_email VARCHAR(180) NOT NULL,
  content      TEXT         NOT NULL,
  status       VARCHAR(20)  NOT NULL DEFAULT 'pending',
  created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_news_comments_news FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE gallery_collections (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  subsidiary_id   INT UNSIGNED NULL,
  cover_image_id  INT UNSIGNED NULL,
  sort_order      INT          NOT NULL DEFAULT 0,
  is_active       TINYINT(1)   NOT NULL DEFAULT 1,
  created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_gallery_collections_subsidiary FOREIGN KEY (subsidiary_id) REFERENCES subsidiaries(id) ON DELETE SET NULL,
  CONSTRAINT fk_gallery_collections_cover FOREIGN KEY (cover_image_id) REFERENCES media(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gallery_collection_translations (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  collection_id INT UNSIGNED NOT NULL,
  language_id   INT UNSIGNED NOT NULL,
  title         VARCHAR(200) NOT NULL,
  slug          VARCHAR(240) NOT NULL,
  description   TEXT         NULL,
  UNIQUE KEY uq_gallery_collection_lang (collection_id, language_id),
  CONSTRAINT fk_gallery_collection_translations_collection FOREIGN KEY (collection_id) REFERENCES gallery_collections(id) ON DELETE CASCADE,
  CONSTRAINT fk_gallery_collection_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gallery_items (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  collection_id  INT UNSIGNED NOT NULL,
  media_id       INT UNSIGNED NOT NULL,
  sort_order     INT          NOT NULL DEFAULT 0,
  caption        VARCHAR(255) NULL,
  CONSTRAINT fk_gallery_items_collection FOREIGN KEY (collection_id) REFERENCES gallery_collections(id) ON DELETE CASCADE,
  CONSTRAINT fk_gallery_items_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE offices (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  subsidiary_id    INT UNSIGNED NULL,
  phone            VARCHAR(30)   NULL,
  email            VARCHAR(180)  NULL,
  whatsapp         VARCHAR(30)   NULL,
  latitude         DECIMAL(10,7) NULL,
  longitude        DECIMAL(10,7) NULL,
  is_headquarters  TINYINT(1)    NOT NULL DEFAULT 0,
  is_active        TINYINT(1)    NOT NULL DEFAULT 1,
  CONSTRAINT fk_offices_subsidiary FOREIGN KEY (subsidiary_id) REFERENCES subsidiaries(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE office_translations (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  office_id     INT UNSIGNED NOT NULL,
  language_id   INT UNSIGNED NOT NULL,
  label         VARCHAR(120) NULL,
  address       TEXT         NULL,
  city          VARCHAR(80)  NULL,
  country       VARCHAR(80)  NULL,
  working_hours VARCHAR(255) NULL,
  UNIQUE KEY uq_office_lang (office_id, language_id),
  CONSTRAINT fk_office_translations_office FOREIGN KEY (office_id) REFERENCES offices(id) ON DELETE CASCADE,
  CONSTRAINT fk_office_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contact_messages (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  office_id     INT UNSIGNED NULL,
  project_id    INT UNSIGNED NULL,
  name          VARCHAR(120) NOT NULL,
  email         VARCHAR(180) NOT NULL,
  phone         VARCHAR(40)  NULL,
  country       VARCHAR(80)  NULL,
  subject       VARCHAR(255) NULL,
  message       TEXT         NOT NULL,
  status        VARCHAR(20)  NOT NULL DEFAULT 'new',
  ip_address    VARCHAR(45)  NULL,
  utm_source    VARCHAR(100) NULL,
  utm_medium    VARCHAR(100) NULL,
  utm_campaign  VARCHAR(100) NULL,
  read_at       TIMESTAMP    NULL,
  replied_at    TIMESTAMP    NULL,
  created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_contact_messages_office FOREIGN KEY (office_id) REFERENCES offices(id) ON DELETE SET NULL,
  CONSTRAINT fk_contact_messages_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE job_departments (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  subsidiary_id  INT UNSIGNED NULL,
  sort_order     INT          NOT NULL DEFAULT 0,
  created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_job_departments_subsidiary FOREIGN KEY (subsidiary_id) REFERENCES subsidiaries(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE job_department_translations (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  department_id INT UNSIGNED NOT NULL,
  language_id   INT UNSIGNED NOT NULL,
  name          VARCHAR(120) NOT NULL,
  UNIQUE KEY uq_job_department_lang (department_id, language_id),
  CONSTRAINT fk_job_department_translations_department FOREIGN KEY (department_id) REFERENCES job_departments(id) ON DELETE CASCADE,
  CONSTRAINT fk_job_department_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE job_listings (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  department_id  INT UNSIGNED NULL,
  subsidiary_id  INT UNSIGNED NULL,
  type           VARCHAR(20)  NOT NULL DEFAULT 'full_time',
  location       VARCHAR(120) NULL,
  status         VARCHAR(20)  NOT NULL DEFAULT 'open',
  expires_at     TIMESTAMP    NULL,
  created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_job_listings_department FOREIGN KEY (department_id) REFERENCES job_departments(id) ON DELETE SET NULL,
  CONSTRAINT fk_job_listings_subsidiary FOREIGN KEY (subsidiary_id) REFERENCES subsidiaries(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE job_listing_translations (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  job_id            INT UNSIGNED NOT NULL,
  language_id       INT UNSIGNED NOT NULL,
  title             VARCHAR(200) NOT NULL,
  slug              VARCHAR(240) NOT NULL,
  description       LONGTEXT     NULL,
  requirements      TEXT         NULL,
  benefits          TEXT         NULL,
  meta_title        VARCHAR(160) NULL,
  meta_description  VARCHAR(320) NULL,
  UNIQUE KEY uq_job_listing_lang (job_id, language_id),
  UNIQUE KEY uq_job_slug (slug),
  CONSTRAINT fk_job_listing_translations_job FOREIGN KEY (job_id) REFERENCES job_listings(id) ON DELETE CASCADE,
  CONSTRAINT fk_job_listing_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE job_applications (
  id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  job_id              INT UNSIGNED NOT NULL,
  full_name           VARCHAR(120) NOT NULL,
  email               VARCHAR(180) NOT NULL,
  phone               VARCHAR(40)  NULL,
  linkedin_url        VARCHAR(255) NULL,
  resume_path         VARCHAR(500) NULL,
  cover_letter_path   VARCHAR(500) NULL,
  cover_letter_text   TEXT         NULL,
  status              VARCHAR(20)  NOT NULL DEFAULT 'received',
  notes               TEXT         NULL,
  created_at          TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_job_applications_job FOREIGN KEY (job_id) REFERENCES job_listings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE seo_meta (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  entity_type       VARCHAR(60)  NOT NULL,
  entity_id         INT UNSIGNED NOT NULL,
  language_id       INT UNSIGNED NOT NULL,
  meta_title        VARCHAR(160) NULL,
  meta_description  VARCHAR(320) NULL,
  og_title          VARCHAR(160) NULL,
  og_description    VARCHAR(320) NULL,
  og_image_id       INT UNSIGNED NULL,
  canonical_url     VARCHAR(500) NULL,
  robots            VARCHAR(80)  NULL DEFAULT 'index, follow',
  structured_data   JSON         NULL,
  updated_at        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_seo_entity_lang (entity_type, entity_id, language_id),
  CONSTRAINT fk_seo_meta_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE,
  CONSTRAINT fk_seo_meta_og_image FOREIGN KEY (og_image_id) REFERENCES media(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE redirects (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  from_path   VARCHAR(500) NOT NULL UNIQUE,
  to_path     VARCHAR(500) NOT NULL,
  type        VARCHAR(5)   NOT NULL DEFAULT '301',
  is_active   TINYINT(1)   NOT NULL DEFAULT 1,
  hit_count   INT UNSIGNED NOT NULL DEFAULT 0,
  created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sitemaps (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  url         VARCHAR(500) NOT NULL UNIQUE,
  changefreq  VARCHAR(20)  NULL DEFAULT 'weekly',
  priority    DECIMAL(2,1) NULL DEFAULT 0.5,
  lastmod     TIMESTAMP    NULL,
  is_active   TINYINT(1)   NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE content_revisions (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  entity_type  VARCHAR(60)  NOT NULL,
  entity_id    INT UNSIGNED NOT NULL,
  user_id      INT UNSIGNED NULL,
  is_autosave  TINYINT(1)   NOT NULL DEFAULT 0,
  payload      JSON         NOT NULL,
  created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_content_revisions_entity (entity_type, entity_id, id),
  CONSTRAINT fk_content_revisions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE settings (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `group`     VARCHAR(60)  NOT NULL,
  `key`       VARCHAR(120) NOT NULL,
  value       TEXT         NULL,
  type        VARCHAR(20)  NOT NULL DEFAULT 'text',
  label       VARCHAR(200) NULL,
  updated_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  updated_by  INT UNSIGNED NULL,
  UNIQUE KEY uq_settings_group_key (`group`, `key`),
  CONSTRAINT fk_settings_updated_by FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS visitor_profiles (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  visitor_uuid      CHAR(36)     NOT NULL UNIQUE,
  name              VARCHAR(200) NULL,
  email             VARCHAR(255) NULL,
  phone             VARCHAR(60)  NULL,
  locale            VARCHAR(10)  NULL,
  marketing_opt_in  TINYINT(1)   NOT NULL DEFAULT 0,
  analytics_opt_in  TINYINT(1)   NOT NULL DEFAULT 0,
  essential_opt_in  TINYINT(1)   NOT NULL DEFAULT 1,
  policy_version    VARCHAR(20)  NULL,
  first_seen_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_seen_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  last_ip_hash      VARCHAR(64)  NULL,
  user_agent        VARCHAR(500) NULL,
  synced_at         TIMESTAMP    NULL,
  INDEX idx_visitor_email (email),
  INDEX idx_visitor_last_seen (last_seen_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS consent_records (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  visitor_uuid      CHAR(36)     NOT NULL,
  policy_version    VARCHAR(20)  NOT NULL,
  essential         TINYINT(1)   NOT NULL DEFAULT 1,
  analytics         TINYINT(1)   NOT NULL DEFAULT 0,
  marketing         TINYINT(1)   NOT NULL DEFAULT 0,
  source            VARCHAR(40)  NOT NULL DEFAULT 'banner',
  ip_hash           VARCHAR(64)  NULL,
  user_agent        VARCHAR(500) NULL,
  locale            VARCHAR(10)  NULL,
  created_at        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  synced_at         TIMESTAMP    NULL,
  INDEX idx_consent_visitor (visitor_uuid),
  INDEX idx_consent_created (created_at),
  CONSTRAINT fk_consent_visitor FOREIGN KEY (visitor_uuid) REFERENCES visitor_profiles(visitor_uuid) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS visitor_events (
  id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  visitor_uuid      CHAR(36)     NOT NULL,
  event_name        VARCHAR(80)  NOT NULL,
  page_path         VARCHAR(500) NULL,
  page_url          VARCHAR(1000) NULL,
  referrer          VARCHAR(1000) NULL,
  locale            VARCHAR(10)  NULL,
  properties        JSON         NULL,
  created_at        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  synced_at         TIMESTAMP    NULL,
  INDEX idx_events_visitor (visitor_uuid),
  INDEX idx_events_name (event_name),
  INDEX idx_events_created (created_at),
  CONSTRAINT fk_events_visitor FOREIGN KEY (visitor_uuid) REFERENCES visitor_profiles(visitor_uuid) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS analytics_sync_queue (
  id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  payload_type      VARCHAR(40)  NOT NULL,
  payload_json      JSON         NOT NULL,
  status            VARCHAR(20)  NOT NULL DEFAULT 'pending',
  attempts          INT UNSIGNED NOT NULL DEFAULT 0,
  last_error        TEXT         NULL,
  created_at        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  synced_at         TIMESTAMP    NULL,
  INDEX idx_sync_status (status),
  INDEX idx_sync_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO languages (code, name, is_default, is_active, sort_order) VALUES
('en', 'English', 1, 1, 0),
('tr', 'Türkçe', 0, 1, 1),
('ar', 'العربية', 0, 1, 2);

INSERT INTO users (name, email, password_hash, role, status) VALUES
('Admin', 'admin@ozermanltd.com', '$2y$12$MDloDizn.QxjgmyMO4Kp6.KA0ZHnSzDdXEgS.cq1d7bN1hvDoDLkS', 'super_admin', 'active');

INSERT INTO pages (slug, template, status, show_in_nav, sort_order, published_at) VALUES
('home', 'home', 'published', 1, 0, NOW()),
('about-us', 'default', 'published', 1, 1, NOW()),
('sectors', 'sector', 'published', 1, 2, NOW()),
('projects', 'project', 'published', 1, 3, NOW()),
('news', 'default', 'published', 1, 4, NOW()),
('gallery', 'default', 'published', 1, 5, NOW()),
('contact', 'contact', 'published', 1, 6, NOW());

INSERT INTO page_translations (page_id, language_id, title, excerpt, meta_title, meta_description) VALUES
(1, 1, 'Home', 'Özerman Ticaret — distinguished brands, a strong local structure.', 'Özerman Ticaret | Distribution and Trade', 'Özerman Ticaret is a distribution and trading company in Cyprus that connects brands with quality retail service.'),
(2, 1, 'About Us', 'Local expertise, a long-term approach.', 'About Us | Özerman Ticaret', 'Özerman Ticaret was founded in 2020 by Emin Özerman in Cyprus.'),
(3, 1, 'Areas of Work', 'Distribution, brand partnerships, and retail service in Cyprus.', 'Areas of Work | Özerman Ticaret', 'How Özerman Ticaret works: distribution, brand partnerships, and after-sales retail service.'),
(4, 1, 'Projects', 'Özerman Ticaret publishes its brands and stores rather than a property portfolio.', 'Projects | Özerman Ticaret', 'Özerman Ticaret focuses on brands, stores, and distribution in Cyprus.'),
(5, 1, 'Corporate', 'Milestones of Özerman Ticaret.', 'Corporate | Özerman Ticaret', 'From Bebecik Online and Daphne Baby to Özerman Ticaret in 2020.'),
(6, 1, 'Gallery', 'Stores and brands.', 'Gallery | Özerman Ticaret', 'Moments from our stores and the brands we represent in Cyprus.'),
(7, 1, 'Contact', 'Get in touch with our team.', 'Contact | Özerman Ticaret', 'Contact Özerman Ticaret — partnership and wholesale inquiries.');

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

INSERT INTO project_categories (sort_order) VALUES (0), (1), (2);
INSERT INTO project_category_translations (category_id, language_id, name, slug) VALUES
(1, 1, 'Residential', 'residential'),
(2, 1, 'Commercial', 'commercial'),
(3, 1, 'Industrial', 'industrial');


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

INSERT INTO offices (phone, email, is_headquarters, is_active) VALUES
('', 'info@ozermanltd.com', 1, 1);

INSERT INTO office_translations (office_id, language_id, label, address, city, country, working_hours) VALUES
(1, 1, 'Head Office', 'Cyprus', 'Nicosia', 'Cyprus', 'Visiting hours will be added.'),
(1, 2, 'Genel Merkez', 'Kıbrıs', 'Lefkoşa', 'Kıbrıs', 'Ziyaret saatleri eklenecek.'),
(1, 3, 'المقر الرئيسي', 'قبرص', 'نيقوسيا', 'قبرص', 'ستُضاف ساعات الزيارة.');

INSERT INTO settings (`group`, `key`, value, type, label) VALUES
('general', 'site_name', 'Özerman Ticaret', 'text', 'Site Name'),
('general', 'site_tagline', 'Distinguished brands. A strong local structure.', 'text', 'Tagline'),
('analytics', 'plausible_domain', 'ozermanltd.com', 'text', 'Analytics domain'),
('system', 'maintenance_mode', '0', 'boolean', 'Maintenance Mode');

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

INSERT INTO menu_translations (menu_id, language_id, title) VALUES
(2, 1, 'Quick Links'), (2, 2, 'Hızlı Bağlantılar'), (2, 3, 'روابط سريعة'),
(3, 1, 'Explore'), (3, 2, 'Keşfedin'), (3, 3, 'استكشف'),
(4, 1, 'Legal'), (4, 2, 'Yasal'), (4, 3, 'قانوني');

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

INSERT INTO menu_items (menu_id, url, sort_order) VALUES
(3, '/partnerships', 0),
(3, '/our-stores', 1),
(3, '/contact', 2);

INSERT INTO menu_item_translations (item_id, language_id, label) VALUES
(13, 1, 'Our Brand Portfolio'), (13, 2, 'Marka Portföyümüz'), (13, 3, 'محفظة العلامات'),
(14, 1, 'Stores'), (14, 2, 'Mağazalar'), (14, 3, 'المتاجر'),
(15, 1, 'Partnership Inquiries'), (15, 2, 'İş Birliği Talepleri'), (15, 3, 'طلبات التعاون');

INSERT INTO menu_items (menu_id, url, sort_order) VALUES
(4, '/privacy-policy', 0),
(4, '/cookie-policy', 1),
(4, '/cookie-settings', 2);

INSERT INTO menu_item_translations (item_id, language_id, label) VALUES
(16, 1, 'Privacy Policy'), (16, 2, 'Gizlilik Politikası'), (16, 3, 'سياسة الخصوصية'),
(17, 1, 'Cookie Policy'), (17, 2, 'Çerez Politikası'), (17, 3, 'سياسة ملفات تعريف الارتباط'),
(18, 1, 'Cookie Settings'), (18, 2, 'Çerez Ayarları'), (18, 3, 'إعدادات ملفات تعريف الارتباط');

UPDATE menu_items SET is_active = 0 WHERE id IN (6, 12);

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

INSERT INTO team_members (type, sort_order, is_active) VALUES
('founder', 0, 1);

INSERT INTO team_member_translations (member_id, language_id, full_name, position, bio) VALUES
(1, 1, 'Emin Özerman', 'Founder', 'Founded Özerman Ticaret in Cyprus in 2020, building on the Daphne Baby retail journey that began in Famagusta in 2018.'),
(1, 2, 'Emin Özerman', 'Kurucu', 'Özerman Ticaret’i 2020 yılında Kıbrıs’ta kurdu. 2018’de Gazimağusa’da başlayan Daphne Baby perakende yolculuğunu kurumsal yapıya taşıdı.'),
(1, 3, 'أمين أوزرمان', 'المؤسس', 'أسس أوزرمان للتجارة في قبرص عام 2020، انطلاقًا من تجربة دافني بيبي التي بدأت في فاماغوستا عام 2018.');

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

UPDATE page_translations
SET content = '{"history":{"en":"<p>Özerman Ticaret was founded in 2020 by Emin Özerman in Cyprus. The entrepreneurial journey that began with Bebecik Online and Daphne Baby, opened in Famagusta in 2018, forms the foundation of today’s retail and distribution structure. Today we operate our own brand Daphne Baby together with the Cyprus distributorships of Lajivert and Aymini.</p>","tr":"<p>Özerman Ticaret, 2020 yılında Emin Özerman tarafından Kıbrıs’ta kurulmuştur. Bebecik Online ile başlayan girişimcilik yolculuğu ve 2018 yılında Gazimağusa’da hayata geçirilen Daphne Baby, şirketin bugünkü perakende ve distribütörlük yapısının temelini oluşturur. Bugün kendi markamız Daphne Baby ile Lajivert ve Aymini markalarının Kıbrıs distribütörlüklerini yürütüyoruz.</p>","ar":"<p>تأسست أوزرمان للتجارة عام 2020 على يد أمين أوزرمان في قبرص. بدأت الرحلة الريادية مع بيبيسيك أونلاين ومع دافني بيبي التي افتُتحت في فاماغوستا عام 2018، وهما أساس هيكل التجزئة والتوزيع اليوم. ندير اليوم علامتنا دافني بيبي إلى جانب وكالات لاجيڤيرت وأيميني في قبرص.</p>"},"vision":{"en":"<p>To be one of the reference companies in retail and distribution in Cyprus, distinguished by a strong brand portfolio and corporate structure.</p><p>To carry the partnerships we build with national and international brands into different sectors and categories, support the growth of the brands we represent in the island market, and raise retail service standards in Cyprus.</p>","tr":"<p>Kıbrıs’ta perakende ve distribütörlük alanında referans gösterilen, güçlü marka portföyü ve kurumsal yapısıyla öne çıkan şirketlerden biri olmak.</p><p>Ulusal ve uluslararası markalarla geliştirdiğimiz iş birliklerini farklı sektör ve kategorilere taşıyarak, temsil ettiğimiz markaların ada pazarındaki gelişimine katkı sağlamak ve Kıbrıs’ta perakendeciliğin hizmet standartlarını ileriye taşımak.</p>","ar":"<p>أن نكون من الشركات المرجعية في التجزئة والتوزيع في قبرص، بفضل محفظة علامات قوية وهيكل مؤسسي متين.</p><p>نقل التعاونات التي نبنيها مع العلامات الوطنية والدولية إلى قطاعات وفئات مختلفة، ودعم نمو العلامات التي نمثلها في سوق الجزيرة، والارتقاء بمعايير خدمة التجزئة في قبرص.</p>"},"mission":{"en":"<p>To work with an approach that understands customer expectations, is careful in product and service selection, and takes responsibility at every stage of shopping.</p><p>To deliver product consultancy, free island-wide delivery and installation, warranty procedures, and spare-parts support in a coordinated way with our professional team.</p><p>To protect the values of the brands we represent and to build stable, transparent, and sustainable commercial relationships for our partners and customers.</p>","tr":"<p>Müşterilerimizin beklentilerini doğru anlayan, ürün ve hizmet seçiminde titizlik gösteren, alışverişin her aşamasında sorumluluk üstlenen bir çalışma anlayışı benimsemek.</p><p>Profesyonel ekibimizle ürün danışmanlığı, ada genelinde ücretsiz teslimat ve kurulum, garanti kapsamındaki işlemler ve yedek parça desteğini koordineli biçimde yürütmek.</p><p>Temsil ettiğimiz markaların değerlerini koruyarak, iş ortaklarımız ve müşterilerimiz için istikrarlı, şeffaf ve sürdürülebilir ticari ilişkiler geliştirmek.</p>","ar":"<p>تبنّي أسلوب عمل يفهم توقعات عملائنا بدقة، ويختار المنتجات والخدمات بعناية، ويتحمّل المسؤولية في كل مرحلة من مراحل التسوق.</p><p>تقديم استشارات المنتج والتوصيل والتركيب المجاني في الجزيرة وإجراءات الضمان ودعم قطع الغيار بتنسيق مع فريقنا المهني.</p><p>حماية قيم العلامات التي نمثلها وبناء علاقات تجارية مستقرة وشفافة ومستدامة لشركائنا وعملائنا.</p>"}}'
WHERE page_id = 2 AND language_id = 1;

UPDATE sectors SET is_active = 0 WHERE id > 3;
UPDATE sector_translations SET name = 'Distribution & Trade', slug = 'distribution-trade', overview = 'We connect the brands we select with the Cyprus market and coordinate product supply and commercial processes.', services_text = '• Brand distribution in Cyprus\n• Product supply coordination\n• Commercial process management' WHERE sector_id = 1 AND language_id = 1;
UPDATE sector_translations SET name = 'Brand Partnerships', slug = 'brand-partnerships', overview = 'We support brand positioning and local-market growth with a long-term partnership approach.', services_text = '• Long-term brand representation\n• Local positioning\n• Partner communication' WHERE sector_id = 2 AND language_id = 1;
UPDATE sector_translations SET name = 'Retail & After-Sales Service', slug = 'retail-after-sales', overview = 'We provide store retail, product consultancy, free island-wide delivery and installation, plus warranty and spare-parts support.', services_text = '• Stores in Nicosia and Famagusta\n• Free island-wide delivery and installation\n• Warranty and spare parts' WHERE sector_id = 3 AND language_id = 1;

UPDATE projects SET is_active = 0, is_featured = 0;

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

INSERT INTO hero_slides (page_id, language_id, media_id, title, subtitle, cta_text, cta_url, sort_order) VALUES
(1, 1, 1, 'Distinguished brands. A strong local structure.', 'Özerman Ticaret is a distribution and trading company in Cyprus that connects brands with quality retail service.', 'Contact Us', '/en/contact', 0),
(1, 1, 2, 'Our Brands and Partnerships', 'We develop our own brand Daphne Baby together with our Lajivert and Aymini distributorships within the same corporate structure.', 'Discover Our Brands', '/en/partnerships', 1),
(1, 1, 3, 'We are with you in Cyprus', 'Visit our stores to see products in person and get support from our specialist team. We offer free delivery and installation across the island.', 'Our Stores', '/en/our-stores', 2),
(1, 2, 1, 'Seçkin markalar. Güçlü bir yerel yapı.', 'Özerman Ticaret, Kıbrıs’ta markaları nitelikli perakende hizmetleriyle buluşturan bir distribütörlük ve ticaret şirketidir.', 'Bize Ulaşın', '/tr/contact', 0),
(1, 2, 2, 'Markalarımız ve İş Birliklerimiz', 'Kendi markamız Daphne Baby ile Lajivert ve Aymini distribütörlüklerimizi aynı kurumsal yapı içinde geliştiriyoruz.', 'Markalarımızı Keşfedin', '/tr/partnerships', 1),
(1, 2, 3, 'Kıbrıs’ta Yanınızdayız', 'Mağazalarımızda ürünleri yakından inceleyebilir, uzman ekibimizden destek alabilirsiniz. Ada genelinde ücretsiz teslimat ve kurulum sunuyoruz.', 'Mağazalarımız', '/tr/our-stores', 2),
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

UPDATE news SET featured_image_id = 8 WHERE id = 2;
UPDATE news SET featured_image_id = 3 WHERE id = 3;
UPDATE stores SET media_id = 4 WHERE id = 1;
UPDATE stores SET media_id = 5 WHERE id = 2;

SET FOREIGN_KEY_CHECKS = 1;
