-- Per-page SEO (SQLite 3).
-- Meta copy matches App\Controllers\Home public SEO fallbacks (Visionary Digital Arts defaults).

CREATE TABLE IF NOT EXISTS seo_pages (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  page_key TEXT NOT NULL UNIQUE,
  meta_title TEXT NOT NULL DEFAULT '',
  meta_description TEXT NULL,
  meta_keywords TEXT NOT NULL DEFAULT '',
  created_at TEXT NULL,
  updated_at TEXT NULL
);

INSERT OR IGNORE INTO seo_pages (id, page_key, meta_title, meta_description, meta_keywords, created_at, updated_at) VALUES
(1, 'home', 'Visionary Digital Arts', 'Visionary Digital Arts — A dynamic studio specializing in software for digital art and interactive entertainment.', 'Visionary Digital Arts, VD-Arts, software development, digital arts, interactive entertainment, Discord bots', datetime('now'), datetime('now')),
(2, 'about-us', 'About us — Visionary Digital Arts', 'Visionary Digital Arts — mission, company structure, and hosting partner.', 'Visionary Digital Arts, VD-Arts, software development, digital arts, interactive entertainment, Discord bots', datetime('now'), datetime('now')),
(3, 'services', 'Services — Visionary Digital Arts', 'Discord bots, hosting, and web applications — Visionary Digital Arts.', 'Visionary Digital Arts, VD-Arts, Discord bots, hosting, web applications', datetime('now'), datetime('now')),
(4, 'products', 'Products & services — Visionary Digital Arts', 'RaidSentinel, JokerBot, Discord bots, and hosting — Visionary Digital Arts.', 'Visionary Digital Arts, VD-Arts, RaidSentinel, Discord bots, hosting', datetime('now'), datetime('now')),
(5, 'tech-stack', 'Technology stack — Visionary Digital Arts', 'Technology stack — OVHcloud, Proxmox, Kubernetes, GitHub, Docker, HostBill, Cloudflare, and more.', 'Visionary Digital Arts, technology stack, DevOps, hosting, Kubernetes, Cloudflare', datetime('now'), datetime('now')),
(6, 'values', 'Our values — Visionary Digital Arts', 'Innovation, precision, and user experience — Visionary Digital Arts.', 'Visionary Digital Arts, values, innovation, user experience', datetime('now'), datetime('now')),
(7, 'contact', 'Contact — Visionary Digital Arts', 'Contact Visionary Digital Arts — email, Discord, and company information.', 'Visionary Digital Arts, contact, email, Discord', datetime('now'), datetime('now'));

UPDATE seo_pages SET meta_title = 'Visionary Digital Arts', meta_description = 'Visionary Digital Arts — A dynamic studio specializing in software for digital art and interactive entertainment.', meta_keywords = 'Visionary Digital Arts, VD-Arts, software development, digital arts, interactive entertainment, Discord bots', updated_at = datetime('now') WHERE page_key = 'home';
UPDATE seo_pages SET meta_title = 'About us — Visionary Digital Arts', meta_description = 'Visionary Digital Arts — mission, company structure, and hosting partner.', meta_keywords = 'Visionary Digital Arts, VD-Arts, software development, digital arts, interactive entertainment, Discord bots', updated_at = datetime('now') WHERE page_key = 'about-us';
UPDATE seo_pages SET meta_title = 'Services — Visionary Digital Arts', meta_description = 'Discord bots, hosting, and web applications — Visionary Digital Arts.', meta_keywords = 'Visionary Digital Arts, VD-Arts, Discord bots, hosting, web applications', updated_at = datetime('now') WHERE page_key = 'services';
UPDATE seo_pages SET meta_title = 'Products & services — Visionary Digital Arts', meta_description = 'RaidSentinel, JokerBot, Discord bots, and hosting — Visionary Digital Arts.', meta_keywords = 'Visionary Digital Arts, VD-Arts, RaidSentinel, Discord bots, hosting', updated_at = datetime('now') WHERE page_key = 'products';
UPDATE seo_pages SET meta_title = 'Technology stack — Visionary Digital Arts', meta_description = 'Technology stack — OVHcloud, Proxmox, Kubernetes, GitHub, Docker, HostBill, Cloudflare, and more.', meta_keywords = 'Visionary Digital Arts, technology stack, DevOps, hosting, Kubernetes, Cloudflare', updated_at = datetime('now') WHERE page_key = 'tech-stack';
UPDATE seo_pages SET meta_title = 'Our values — Visionary Digital Arts', meta_description = 'Innovation, precision, and user experience — Visionary Digital Arts.', meta_keywords = 'Visionary Digital Arts, values, innovation, user experience', updated_at = datetime('now') WHERE page_key = 'values';
UPDATE seo_pages SET meta_title = 'Contact — Visionary Digital Arts', meta_description = 'Contact Visionary Digital Arts — email, Discord, and company information.', meta_keywords = 'Visionary Digital Arts, contact, email, Discord', updated_at = datetime('now') WHERE page_key = 'contact';
