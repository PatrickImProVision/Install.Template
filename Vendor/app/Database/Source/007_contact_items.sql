-- Contact page & site footer CMS (MySQL / MariaDB).

CREATE TABLE IF NOT EXISTS `contact_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort_order` INT NOT NULL DEFAULT 0,
  `kind` VARCHAR(32) NOT NULL DEFAULT 'contact_entry',
  `column_group` VARCHAR(32) NOT NULL DEFAULT 'contact',
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `description` TEXT NULL,
  `meta_label` VARCHAR(255) NULL DEFAULT NULL,
  `href` VARCHAR(1024) NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_items_sort_idx` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `contact_items` (`id`, `sort_order`, `kind`, `column_group`, `title`, `description`, `meta_label`, `href`, `created_at`, `updated_at`) VALUES
(1, 0, 'page_heading', 'page', 'Contact', 'Get in touch for collaborations, partnerships, and support.', NULL, NULL, NOW(), NOW()),
(2, 10, 'brand', 'intro', 'Visionary Digital Arts', 'A studio specializing in software for digital art and interactive entertainment.', NULL, NULL, NOW(), NOW()),
(3, 20, 'column_heading', 'company', 'Company structure', NULL, NULL, NULL, NOW(), NOW()),
(4, 30, 'company_entry', 'company', 'Visionary Interactive s.r.o.', NULL, 'Owner', NULL, NOW(), NOW()),
(5, 40, 'company_entry', 'company', 'Visionary Digital Arts', NULL, 'Development studio', NULL, NOW(), NOW()),
(6, 50, 'company_entry', 'company', 'StingerHost.eu', NULL, 'Game & bot hosting', 'https://stingerhost.eu', NOW(), NOW()),
(7, 60, 'column_heading', 'contact', 'Contact', NULL, NULL, NULL, NOW(), NOW()),
(8, 70, 'contact_entry', 'contact', 'info@visionarydigitalarts.cz', NULL, NULL, 'mailto:info@visionarydigitalarts.cz', NOW(), NOW()),
(9, 80, 'contact_entry', 'contact', 'Czech Republic', NULL, 'meta', NULL, NOW(), NOW()),
(10, 90, 'contact_entry', 'contact', 'Discord community', NULL, NULL, 'https://discord.gg/3PeXKZJdBs', NOW(), NOW()),
(11, 100, 'contact_entry', 'contact', 'https://vd-arts.studio', NULL, NULL, 'https://vd-arts.studio', NOW(), NOW()),
(12, 110, 'legal', 'legal', '', '© {year} Visionary Digital Arts. All rights reserved.', NULL, NULL, NOW(), NOW()),
(13, 120, 'legal', 'legal', '', 'Part of Visionary Interactive s.r.o.', NULL, NULL, NOW(), NOW());
