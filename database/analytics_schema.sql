-- Analytics & consent tables for Ozerman Ltd
-- Run after schema.sql: mysql -u root -p ozermanltd < database/analytics_schema.sql

USE ozermanltd;

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
