-- Per-page SEO (MySQL / MariaDB).
-- Meta copy matches App\Controllers\Home public SEO fallbacks (Visionary Digital Arts defaults).

CREATE TABLE IF NOT EXISTS `seo_pages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_key` VARCHAR(64) NOT NULL,
  `meta_title` VARCHAR(255) NOT NULL DEFAULT '',
  `meta_description` TEXT NULL,
  `meta_keywords` VARCHAR(512) NOT NULL DEFAULT '',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seo_pages_page_key_unique` (`page_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `seo_pages` (`id`, `page_key`, `meta_title`, `meta_description`, `meta_keywords`, `created_at`, `updated_at`) VALUES
(1, 'home', 'Visionary Digital Arts', 'Visionary Digital Arts — A dynamic studio specializing in software for digital art and interactive entertainment.', 'Visionary Digital Arts, VD-Arts, software development, digital arts, interactive entertainment, Discord bots', NOW(), NOW()),
(2, 'about-us', 'About us — Visionary Digital Arts', 'Visionary Digital Arts — mission, company structure, and hosting partner.', 'Visionary Digital Arts, VD-Arts, software development, digital arts, interactive entertainment, Discord bots', NOW(), NOW()),
(3, 'services', 'Services — Visionary Digital Arts', 'Discord bots, hosting, and web applications — Visionary Digital Arts.', 'Visionary Digital Arts, VD-Arts, Discord bots, hosting, web applications', NOW(), NOW()),
(4, 'products', 'Products & services — Visionary Digital Arts', 'RaidSentinel, JokerBot, Discord bots, and hosting — Visionary Digital Arts.', 'Visionary Digital Arts, VD-Arts, RaidSentinel, Discord bots, hosting', NOW(), NOW()),
(5, 'tech-stack', 'Technology stack — Visionary Digital Arts', 'Technology stack — OVHcloud, Proxmox, Kubernetes, GitHub, Docker, HostBill, Cloudflare, and more.', 'Visionary Digital Arts, technology stack, DevOps, hosting, Kubernetes, Cloudflare', NOW(), NOW()),
(6, 'values', 'Our values — Visionary Digital Arts', 'Innovation, precision, and user experience — Visionary Digital Arts.', 'Visionary Digital Arts, values, innovation, user experience', NOW(), NOW()),
(7, 'contact', 'Contact — Visionary Digital Arts', 'Contact Visionary Digital Arts — email, Discord, and company information.', 'Visionary Digital Arts, contact, email, Discord', NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `meta_title` = VALUES(`meta_title`),
  `meta_description` = VALUES(`meta_description`),
  `meta_keywords` = VALUES(`meta_keywords`),
  `updated_at` = VALUES(`updated_at`);
