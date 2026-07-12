CREATE TABLE IF NOT EXISTS `donations` (
  `id`           INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `name`         VARCHAR(100)     NOT NULL DEFAULT 'Anonymous',
  `country_code` CHAR(2)          DEFAULT NULL,
  `amount`       DECIMAL(8,2)     UNSIGNED NOT NULL,
  `currency`     CHAR(3)          NOT NULL DEFAULT 'EUR',
  `donated_at`   DATE             NOT NULL,
  `public`       TINYINT(1)       UNSIGNED NOT NULL DEFAULT 1,
  `link`         VARCHAR(255)     DEFAULT NULL,
  `notes`        VARCHAR(500)     DEFAULT NULL,
  `created_at`   TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_donated_at` (`donated_at`),
  KEY `idx_public`     (`public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
