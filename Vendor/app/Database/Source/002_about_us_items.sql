-- About Us CMS blocks (MySQL / MariaDB).
-- Paired with driver-specific files for portable installs.

CREATE TABLE IF NOT EXISTS `about_us_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort_order` INT NOT NULL DEFAULT 0,
  `placement` VARCHAR(32) NOT NULL DEFAULT 'stack',
  `kind` VARCHAR(32) NOT NULL DEFAULT 'company_card',
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `description` TEXT NULL,
  `href` VARCHAR(512) NULL DEFAULT NULL,
  `icon_key` VARCHAR(64) NULL DEFAULT NULL,
  `bullets` TEXT NULL,
  `footnote` VARCHAR(512) NULL DEFAULT NULL,
  `card_style` VARCHAR(32) NOT NULL DEFAULT '',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `about_us_items_sort_idx` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `about_us_items` (`id`, `sort_order`, `placement`, `kind`, `title`, `description`, `href`, `icon_key`, `bullets`, `footnote`, `card_style`, `created_at`, `updated_at`) VALUES
(1, 0, 'page_header', 'page_heading', 'About us', 'We are a dynamic studio with a clear vision and a strong foundation', NULL, NULL, NULL, NULL, '', NOW(), NOW()),
(2, 10, 'intro', 'mission', 'Our mission', 'Visionary Digital Arts is a dynamic studio specializing in software for digital art and interactive entertainment.
As part of Visionary Interactive s.r.o., we focus on delivering high-quality solutions—including advanced Discord bots,
web applications, and hosting services for online platforms. We emphasize an innovative approach, precise execution, and a strong user experience.', NULL, NULL, NULL, NULL, '', NOW(), NOW()),
(3, 11, 'intro', 'badge', 'Innovation', NULL, NULL, 'innovation', NULL, NULL, '', NOW(), NOW()),
(4, 12, 'intro', 'badge', 'Expert team', NULL, NULL, 'team', NULL, NULL, '', NOW(), NOW()),
(5, 13, 'intro', 'badge', 'Strong foundation', NULL, NULL, 'foundation', NULL, NULL, '', NOW(), NOW()),
(6, 20, 'stack', 'company_card', 'Visionary Interactive s.r.o.', 'Owner and parent company providing strategic leadership', NULL, NULL, NULL, NULL, '', NOW(), NOW()),
(7, 21, 'stack', 'company_card', 'Visionary Digital Arts', 'Development studio focused on digital art and interactive entertainment', NULL, NULL, NULL, 'Develops products such as RaidSentinel', 'blue', NOW(), NOW()),
(8, 22, 'stack', 'company_card', 'StingerHost.eu', 'Hosting platform for game servers and Discord bots built on OVHcloud infrastructure with Proxmox virtualization and HostBill billing.', 'https://stingerhost.eu', NULL, 'AMD Ryzen 7 9700X, 64GB RAM, NVMe SSD\nProxmox VE virtualization\nHostBill automated billing\nGaming Anti-DDoS protection\nFocus on CZ/SK communities', 'Founded 2024 · stingerhost.eu', 'amber', NOW(), NOW());
