-- Contact page & site footer CMS (PostgreSQL).

CREATE TABLE IF NOT EXISTS contact_items (
  id SERIAL PRIMARY KEY,
  sort_order INTEGER NOT NULL DEFAULT 0,
  kind VARCHAR(32) NOT NULL DEFAULT 'contact_entry',
  column_group VARCHAR(32) NOT NULL DEFAULT 'contact',
  title VARCHAR(255) NOT NULL DEFAULT '',
  description TEXT NULL,
  meta_label VARCHAR(255) NULL,
  href VARCHAR(1024) NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE INDEX IF NOT EXISTS contact_items_sort_idx ON contact_items (sort_order);

INSERT INTO contact_items (id, sort_order, kind, column_group, title, description, meta_label, href, created_at, updated_at) VALUES
(1, 0, 'page_heading', 'page', 'Contact', 'Get in touch for collaborations, partnerships, and support.', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(2, 10, 'brand', 'intro', 'Visionary Digital Arts', 'A studio specializing in software for digital art and interactive entertainment.', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(3, 20, 'column_heading', 'company', 'Company structure', NULL, NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(4, 30, 'company_entry', 'company', 'Visionary Interactive s.r.o.', NULL, 'Owner', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(5, 40, 'company_entry', 'company', 'Visionary Digital Arts', NULL, 'Development studio', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(6, 50, 'company_entry', 'company', 'StingerHost.eu', NULL, 'Game & bot hosting', 'https://stingerhost.eu', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(7, 60, 'column_heading', 'contact', 'Contact', NULL, NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(8, 70, 'contact_entry', 'contact', 'info@visionarydigitalarts.cz', NULL, NULL, 'mailto:info@visionarydigitalarts.cz', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(9, 80, 'contact_entry', 'contact', 'Czech Republic', NULL, 'meta', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(10, 90, 'contact_entry', 'contact', 'Discord community', NULL, NULL, 'https://discord.gg/3PeXKZJdBs', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(11, 100, 'contact_entry', 'contact', 'https://vd-arts.studio', NULL, NULL, 'https://vd-arts.studio', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(12, 110, 'legal', 'legal', '', '© {year} Visionary Digital Arts. All rights reserved.', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(13, 120, 'legal', 'legal', '', 'Part of Visionary Interactive s.r.o.', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON CONFLICT (id) DO NOTHING;

SELECT setval(
    pg_get_serial_sequence('contact_items', 'id'),
    COALESCE((SELECT MAX(id) FROM contact_items), 1)
);
