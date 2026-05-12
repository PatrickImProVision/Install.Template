-- Products & services page CMS (MySQL / MariaDB).

CREATE TABLE IF NOT EXISTS `products_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort_order` INT NOT NULL DEFAULT 0,
  `kind` VARCHAR(32) NOT NULL DEFAULT 'product_card',
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `description` TEXT NULL,
  `bullets` TEXT NULL,
  `sub_line` VARCHAR(255) NULL DEFAULT NULL,
  `href` VARCHAR(1024) NULL DEFAULT NULL,
  `card_style` VARCHAR(64) NULL DEFAULT NULL,
  `icon_svg` TEXT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_items_sort_idx` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `products_items` (`id`, `sort_order`, `kind`, `title`, `description`, `bullets`, `sub_line`, `href`, `card_style`, `icon_svg`, `created_at`, `updated_at`) VALUES
(1, 0, 'page_heading', 'Products & services', 'We build innovative products and deliver hosting at a high standard', NULL, NULL, NULL, NULL, NULL, NOW(), NOW()),
(2, 10, 'product_card', 'RaidSentinel →', 'An advanced monitoring and security Discord bot offering comprehensive anti-raid protection and moderation automation.', NULL, 'raidsentinel.app', 'https://raidsentinel.app', 'grad-blue', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>', NOW(), NOW()),
(3, 20, 'product_card', 'RaidSentinel Backup →', 'A full backup system for Discord servers with scheduled backups and fast server restoration.', NULL, 'raidsentinel.app', 'https://raidsentinel.app', 'grad-cyan', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>', NOW(), NOW()),
(4, 30, 'product_card', 'JokerBot →', 'A multi-purpose Discord bot with advanced features for moderation, fun, and community server management.', NULL, 'github.com/Polda18/JokerBot', 'https://github.com/Polda18/JokerBot/blob/master/README.md', 'grad-emerald', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>', NOW(), NOW()),
(5, 40, 'product_card', 'Tic-tac-toe (Gomoku-style) →', 'An interactive Discord bot for tic-tac-toe style play with single-player and multi-player modes and a custom language pack.', 'Single & multi-player\nCustom language pack', 'tictactoe-bot.xyz', 'https://tictactoe-bot.xyz/docs', 'grad-purple', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/></svg>', NOW(), NOW());
