-- Our values page CMS (MySQL / MariaDB).

CREATE TABLE IF NOT EXISTS `values_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort_order` INT NOT NULL DEFAULT 0,
  `kind` VARCHAR(32) NOT NULL DEFAULT 'value_item',
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `description` TEXT NULL,
  `emoji` VARCHAR(64) NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `values_items_sort_idx` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `values_items` (`id`, `sort_order`, `kind`, `title`, `description`, `emoji`, `created_at`, `updated_at`) VALUES
(1, 0, 'page_heading', 'Our values', NULL, NULL, NOW(), NOW()),
(2, 10, 'value_item', 'Innovative approach', 'We explore new paths and technologies to achieve the best results.', '🎯', NOW(), NOW()),
(3, 20, 'value_item', 'Precise execution', 'Every detail matters, and we give it our full attention.', '⚙️', NOW(), NOW()),
(4, 30, 'value_item', 'User experience', 'Our priority is building a strong, memorable experience for users.', '💫', NOW(), NOW());
