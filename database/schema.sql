-- ============================================================
-- CORPORATE WEBSITE DATABASE SCHEMA — MySQL 8.0+
-- Converted from DBML for: ozermanltd.com
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS ozermanltd
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE ozermanltd;

-- ============================================================
-- GROUP 1 — USERS & AUTHENTICATION
-- ============================================================

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

-- ============================================================
-- GROUP 2 — LANGUAGES
-- ============================================================

CREATE TABLE languages (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code        VARCHAR(5)   NOT NULL UNIQUE COMMENT 'en | tr | ar',
  name        VARCHAR(60)  NOT NULL,
  flag_icon   VARCHAR(100) NULL,
  is_default  TINYINT(1)   NOT NULL DEFAULT 0,
  is_active   TINYINT(1)   NOT NULL DEFAULT 1,
  sort_order  INT          NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- GROUP 3 — PAGES & CONTENT
-- ============================================================

CREATE TABLE pages (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug           VARCHAR(160) NOT NULL UNIQUE,
  template       VARCHAR(80)  NOT NULL DEFAULT 'default',
  status         VARCHAR(20)  NOT NULL DEFAULT 'draft',
  show_in_nav    TINYINT(1)   NOT NULL DEFAULT 1,
  sort_order     INT          NOT NULL DEFAULT 0,
  parent_id      INT UNSIGNED NULL,
  published_at   TIMESTAMP    NULL,
  created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_pages_parent FOREIGN KEY (parent_id) REFERENCES pages(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE page_translations (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page_id           INT UNSIGNED NOT NULL,
  language_id       INT UNSIGNED NOT NULL,
  title             VARCHAR(255) NOT NULL,
  content           LONGTEXT     NULL,
  excerpt           TEXT         NULL,
  meta_title        VARCHAR(160) NULL,
  meta_description  VARCHAR(320) NULL,
  UNIQUE KEY uq_page_lang (page_id, language_id),
  CONSTRAINT fk_page_translations_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
  CONSTRAINT fk_page_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- GROUP 5 — MEDIA (created before hero_slides FK)
-- ============================================================

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

-- ============================================================
-- GROUP 4 — MENUS
-- ============================================================

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

CREATE TABLE menu_item_translations (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  item_id     INT UNSIGNED NOT NULL,
  language_id INT UNSIGNED NOT NULL,
  label       VARCHAR(120) NOT NULL,
  UNIQUE KEY uq_menu_item_lang (item_id, language_id),
  CONSTRAINT fk_menu_item_translations_item FOREIGN KEY (item_id) REFERENCES menu_items(id) ON DELETE CASCADE,
  CONSTRAINT fk_menu_item_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- GROUP 6 — BUSINESS STRUCTURE
-- ============================================================

CREATE TABLE sectors (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  icon       VARCHAR(80) NULL,
  color      VARCHAR(20) NULL,
  sort_order INT         NOT NULL DEFAULT 0,
  is_active  TINYINT(1)  NOT NULL DEFAULT 1,
  created_at TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
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
  subsidiary_id  INT UNSIGNED NOT NULL,
  logo           VARCHAR(255) NULL,
  website_url    VARCHAR(255) NULL,
  sort_order     INT          NOT NULL DEFAULT 0,
  is_active      TINYINT(1)   NOT NULL DEFAULT 1,
  CONSTRAINT fk_brands_subsidiary FOREIGN KEY (subsidiary_id) REFERENCES subsidiaries(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE brand_translations (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  brand_id     INT UNSIGNED NOT NULL,
  language_id  INT UNSIGNED NOT NULL,
  name         VARCHAR(120) NOT NULL,
  description  TEXT         NULL,
  UNIQUE KEY uq_brand_lang (brand_id, language_id),
  CONSTRAINT fk_brand_translations_brand FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE,
  CONSTRAINT fk_brand_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
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

-- ============================================================
-- GROUP 7 — PROJECTS
-- ============================================================

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

-- ============================================================
-- GROUP 8 — NEWS & BLOG
-- ============================================================

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

-- ============================================================
-- GROUP 9 — GALLERY COLLECTIONS
-- ============================================================

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

-- ============================================================
-- GROUP 10 — OFFICES & CONTACT
-- ============================================================

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

-- ============================================================
-- GROUP 11 — CAREERS
-- ============================================================

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

-- ============================================================
-- GROUP 12 — SEO
-- ============================================================

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

-- ============================================================
-- GROUP 13 — SETTINGS
-- ============================================================

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

SET FOREIGN_KEY_CHECKS = 1;
