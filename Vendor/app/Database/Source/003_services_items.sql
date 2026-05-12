-- Services page CMS (MySQL / MariaDB).

CREATE TABLE IF NOT EXISTS `services_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort_order` INT NOT NULL DEFAULT 0,
  `kind` VARCHAR(32) NOT NULL DEFAULT 'service_card',
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `description` TEXT NULL,
  `bullets` TEXT NULL,
  `image_url` VARCHAR(1024) NULL DEFAULT NULL,
  `image_alt` VARCHAR(255) NULL DEFAULT NULL,
  `icon_svg` TEXT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `services_items_sort_idx` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `services_items` (`id`, `sort_order`, `kind`, `title`, `description`, `bullets`, `image_url`, `image_alt`, `icon_svg`, `created_at`, `updated_at`) VALUES
(1, 0, 'page_heading', 'Our services', 'We deliver high-quality solutions in digital art and interactive entertainment', NULL, NULL, NULL, NULL, NOW(), NOW()),
(2, 10, 'service_card', 'Discord bots', 'Development of advanced Discord bots with a focus on security, automation, and user experience.', 'Security and monitoring systems\nAutomation and moderation\nServer backups\nCustom functionality', 'https://images.unsplash.com/photo-1649451844931-57e22fc82de3?w=1080&q=80', 'Discord development', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>', NOW(), NOW()),
(3, 20, 'service_card', 'Game & bot hosting', 'High-performance hosting for game servers and Discord bots on top-tier infrastructure.', 'AMD Ryzen 7 9700X + 64GB RAM\nProxmox VE virtualization\nHostBill billing system\nGaming Anti-DDoS protection\nFocus on CZ/SK communities', 'https://images.unsplash.com/photo-1667984390553-7f439e6ae401?w=1080&q=80', 'Cloud hosting', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>', NOW(), NOW()),
(4, 30, 'service_card', 'Web applications & tools', 'End-to-end solutions for online platforms including web apps, dashboards, and API services.', 'Modern web applications\nRESTful APIs\nAdmin dashboards\nDatabase systems', 'https://images.unsplash.com/photo-1526242767279-2ad8d8271177?w=1080&q=80', 'Web workspace', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>', NOW(), NOW());
