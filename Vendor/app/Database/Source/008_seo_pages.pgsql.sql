-- Per-page SEO (PostgreSQL).
-- Meta copy matches App\Controllers\Home public SEO fallbacks (Visionary Digital Arts defaults).

CREATE TABLE IF NOT EXISTS seo_pages (
  id SERIAL PRIMARY KEY,
  page_key VARCHAR(64) NOT NULL UNIQUE,
  meta_title VARCHAR(255) NOT NULL DEFAULT '',
  meta_description TEXT NULL,
  meta_keywords VARCHAR(512) NOT NULL DEFAULT '',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL
);

INSERT INTO seo_pages (id, page_key, meta_title, meta_description, meta_keywords, created_at, updated_at) VALUES
(1, 'home', 'Visionary Digital Arts', 'Visionary Digital Arts — A dynamic studio specializing in software for digital art and interactive entertainment.', 'Visionary Digital Arts, VD-Arts, software development, digital arts, interactive entertainment, Discord bots', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(2, 'about-us', 'About us — Visionary Digital Arts', 'Visionary Digital Arts — mission, company structure, and hosting partner.', 'Visionary Digital Arts, VD-Arts, software development, digital arts, interactive entertainment, Discord bots', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(3, 'services', 'Services — Visionary Digital Arts', 'Discord bots, hosting, and web applications — Visionary Digital Arts.', 'Visionary Digital Arts, VD-Arts, Discord bots, hosting, web applications', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(4, 'products', 'Products & services — Visionary Digital Arts', 'RaidSentinel, JokerBot, Discord bots, and hosting — Visionary Digital Arts.', 'Visionary Digital Arts, VD-Arts, RaidSentinel, Discord bots, hosting', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(5, 'tech-stack', 'Technology stack — Visionary Digital Arts', 'Technology stack — OVHcloud, Proxmox, Kubernetes, GitHub, Docker, HostBill, Cloudflare, and more.', 'Visionary Digital Arts, technology stack, DevOps, hosting, Kubernetes, Cloudflare', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(6, 'values', 'Our values — Visionary Digital Arts', 'Innovation, precision, and user experience — Visionary Digital Arts.', 'Visionary Digital Arts, values, innovation, user experience', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(7, 'contact', 'Contact — Visionary Digital Arts', 'Contact Visionary Digital Arts — email, Discord, and company information.', 'Visionary Digital Arts, contact, email, Discord', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON CONFLICT (page_key) DO UPDATE SET
  meta_title = EXCLUDED.meta_title,
  meta_description = EXCLUDED.meta_description,
  meta_keywords = EXCLUDED.meta_keywords,
  updated_at = EXCLUDED.updated_at;

SELECT setval(
    pg_get_serial_sequence('seo_pages', 'id'),
    COALESCE((SELECT MAX(id) FROM seo_pages), 1)
);
