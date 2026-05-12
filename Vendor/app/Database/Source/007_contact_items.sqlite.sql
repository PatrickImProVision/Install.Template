-- Contact page & site footer CMS (SQLite 3).

CREATE TABLE IF NOT EXISTS contact_items (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  sort_order INTEGER NOT NULL DEFAULT 0,
  kind TEXT NOT NULL DEFAULT 'contact_entry',
  column_group TEXT NOT NULL DEFAULT 'contact',
  title TEXT NOT NULL DEFAULT '',
  description TEXT NULL,
  meta_label TEXT NULL,
  href TEXT NULL,
  created_at TEXT NULL,
  updated_at TEXT NULL
);

CREATE INDEX IF NOT EXISTS contact_items_sort_idx ON contact_items (sort_order);

INSERT OR IGNORE INTO contact_items (id, sort_order, kind, column_group, title, description, meta_label, href, created_at, updated_at) VALUES
(1, 0, 'page_heading', 'page', 'Contact', 'Get in touch for collaborations, partnerships, and support.', NULL, NULL, datetime('now'), datetime('now')),
(2, 10, 'brand', 'intro', 'Visionary Digital Arts', 'A studio specializing in software for digital art and interactive entertainment.', NULL, NULL, datetime('now'), datetime('now')),
(3, 20, 'column_heading', 'company', 'Company structure', NULL, NULL, NULL, datetime('now'), datetime('now')),
(4, 30, 'company_entry', 'company', 'Visionary Interactive s.r.o.', NULL, 'Owner', NULL, datetime('now'), datetime('now')),
(5, 40, 'company_entry', 'company', 'Visionary Digital Arts', NULL, 'Development studio', NULL, datetime('now'), datetime('now')),
(6, 50, 'company_entry', 'company', 'StingerHost.eu', NULL, 'Game & bot hosting', 'https://stingerhost.eu', datetime('now'), datetime('now')),
(7, 60, 'column_heading', 'contact', 'Contact', NULL, NULL, NULL, datetime('now'), datetime('now')),
(8, 70, 'contact_entry', 'contact', 'info@visionarydigitalarts.cz', NULL, NULL, 'mailto:info@visionarydigitalarts.cz', datetime('now'), datetime('now')),
(9, 80, 'contact_entry', 'contact', 'Czech Republic', NULL, 'meta', NULL, datetime('now'), datetime('now')),
(10, 90, 'contact_entry', 'contact', 'Discord community', NULL, NULL, 'https://discord.gg/3PeXKZJdBs', datetime('now'), datetime('now')),
(11, 100, 'contact_entry', 'contact', 'https://vd-arts.studio', NULL, NULL, 'https://vd-arts.studio', datetime('now'), datetime('now')),
(12, 110, 'legal', 'legal', '', '© {year} Visionary Digital Arts. All rights reserved.', NULL, NULL, datetime('now'), datetime('now')),
(13, 120, 'legal', 'legal', '', 'Part of Visionary Interactive s.r.o.', NULL, NULL, datetime('now'), datetime('now'));
