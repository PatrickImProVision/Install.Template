-- About Us CMS blocks (SQLite 3).

CREATE TABLE IF NOT EXISTS about_us_items (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  sort_order INTEGER NOT NULL DEFAULT 0,
  placement TEXT NOT NULL DEFAULT 'stack',
  kind TEXT NOT NULL DEFAULT 'company_card',
  title TEXT NOT NULL DEFAULT '',
  description TEXT NULL,
  href TEXT NULL,
  icon_key TEXT NULL,
  bullets TEXT NULL,
  footnote TEXT NULL,
  card_style TEXT NOT NULL DEFAULT '',
  created_at TEXT NULL,
  updated_at TEXT NULL
);

CREATE INDEX IF NOT EXISTS about_us_items_sort_idx ON about_us_items (sort_order);

INSERT OR IGNORE INTO about_us_items (id, sort_order, placement, kind, title, description, href, icon_key, bullets, footnote, card_style, created_at, updated_at) VALUES
(1, 0, 'page_header', 'page_heading', 'About us', 'We are a dynamic studio with a clear vision and a strong foundation', NULL, NULL, NULL, NULL, '', datetime('now'), datetime('now')),
(2, 10, 'intro', 'mission', 'Our mission', 'Visionary Digital Arts is a dynamic studio specializing in software for digital art and interactive entertainment.
As part of Visionary Interactive s.r.o., we focus on delivering high-quality solutions—including advanced Discord bots,
web applications, and hosting services for online platforms. We emphasize an innovative approach, precise execution, and a strong user experience.', NULL, NULL, NULL, NULL, '', datetime('now'), datetime('now')),
(3, 11, 'intro', 'badge', 'Innovation', NULL, NULL, 'innovation', NULL, NULL, '', datetime('now'), datetime('now')),
(4, 12, 'intro', 'badge', 'Expert team', NULL, NULL, 'team', NULL, NULL, '', datetime('now'), datetime('now')),
(5, 13, 'intro', 'badge', 'Strong foundation', NULL, NULL, 'foundation', NULL, NULL, '', datetime('now'), datetime('now')),
(6, 20, 'stack', 'company_card', 'Visionary Interactive s.r.o.', 'Owner and parent company providing strategic leadership', NULL, NULL, NULL, NULL, '', datetime('now'), datetime('now')),
(7, 21, 'stack', 'company_card', 'Visionary Digital Arts', 'Development studio focused on digital art and interactive entertainment', NULL, NULL, NULL, 'Develops products such as RaidSentinel', 'blue', datetime('now'), datetime('now')),
(8, 22, 'stack', 'company_card', 'StingerHost.eu', 'Hosting platform for game servers and Discord bots built on OVHcloud infrastructure with Proxmox virtualization and HostBill billing.', 'https://stingerhost.eu', NULL, 'AMD Ryzen 7 9700X, 64GB RAM, NVMe SSD
Proxmox VE virtualization
HostBill automated billing
Gaming Anti-DDoS protection
Focus on CZ/SK communities', 'Founded 2024 · stingerhost.eu', 'amber', datetime('now'), datetime('now'));
