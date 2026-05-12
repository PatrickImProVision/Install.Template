-- Services page CMS (SQLite 3).

CREATE TABLE IF NOT EXISTS services_items (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  sort_order INTEGER NOT NULL DEFAULT 0,
  kind TEXT NOT NULL DEFAULT 'service_card',
  title TEXT NOT NULL DEFAULT '',
  description TEXT NULL,
  bullets TEXT NULL,
  image_url TEXT NULL,
  image_alt TEXT NULL,
  icon_svg TEXT NULL,
  created_at TEXT NULL,
  updated_at TEXT NULL
);

CREATE INDEX IF NOT EXISTS services_items_sort_idx ON services_items (sort_order);

INSERT OR IGNORE INTO services_items (id, sort_order, kind, title, description, bullets, image_url, image_alt, icon_svg, created_at, updated_at) VALUES
(1, 0, 'page_heading', 'Our services', 'We deliver high-quality solutions in digital art and interactive entertainment', NULL, NULL, NULL, NULL, datetime('now'), datetime('now')),
(2, 10, 'service_card', 'Discord bots', 'Development of advanced Discord bots with a focus on security, automation, and user experience.', 'Security and monitoring systems
Automation and moderation
Server backups
Custom functionality', 'https://images.unsplash.com/photo-1649451844931-57e22fc82de3?w=1080&q=80', 'Discord development', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>', datetime('now'), datetime('now')),
(3, 20, 'service_card', 'Game & bot hosting', 'High-performance hosting for game servers and Discord bots on top-tier infrastructure.', 'AMD Ryzen 7 9700X + 64GB RAM
Proxmox VE virtualization
HostBill billing system
Gaming Anti-DDoS protection
Focus on CZ/SK communities', 'https://images.unsplash.com/photo-1667984390553-7f439e6ae401?w=1080&q=80', 'Cloud hosting', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>', datetime('now'), datetime('now')),
(4, 30, 'service_card', 'Web applications & tools', 'End-to-end solutions for online platforms including web apps, dashboards, and API services.', 'Modern web applications
RESTful APIs
Admin dashboards
Database systems', 'https://images.unsplash.com/photo-1526242767279-2ad8d8271177?w=1080&q=80', 'Web workspace', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>', datetime('now'), datetime('now'));
