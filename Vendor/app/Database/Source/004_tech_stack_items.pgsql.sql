-- Technology stack CMS (PostgreSQL).

CREATE TABLE IF NOT EXISTS tech_stack_items (
  id SERIAL PRIMARY KEY,
  sort_order INTEGER NOT NULL DEFAULT 0,
  kind VARCHAR(32) NOT NULL DEFAULT 'tech_card',
  title VARCHAR(255) NOT NULL DEFAULT '',
  description TEXT NULL,
  category VARCHAR(255) NULL,
  product_name VARCHAR(255) NULL,
  blurb TEXT NULL,
  href VARCHAR(1024) NULL,
  icon_color VARCHAR(32) NULL,
  name_color VARCHAR(32) NULL,
  icon_svg TEXT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE INDEX IF NOT EXISTS tech_stack_items_sort_idx ON tech_stack_items (sort_order);

INSERT INTO tech_stack_items (id, sort_order, kind, title, description, category, product_name, blurb, href, icon_color, name_color, icon_svg, created_at, updated_at) VALUES
(1, 0, 'page_heading', 'Technology stack', 'We use modern, reliable technology to maximize performance and security', NULL, NULL, NULL, NULL, NULL, NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(2, 10, 'tech_card', '', NULL, 'Infrastructure', 'OVHcloud', 'Reliable cloud infrastructure for hosting and performance', 'https://www.ovhcloud.com', '#2563eb', '#2563eb', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(3, 20, 'tech_card', '', NULL, 'Virtualization', 'Proxmox VE', 'A virtualization platform for managing VPS and containers', 'https://www.proxmox.com', '#16a34a', '#16a34a', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="8" x="2" y="2" rx="2" ry="2"/><rect width="20" height="8" x="2" y="14" rx="2" ry="2"/><line x1="6" x2="6.01" y1="6" y2="6"/><line x1="6" x2="6.01" y1="18" y2="18"/></svg>', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(4, 30, 'tech_card', '', NULL, 'Orchestration', 'Kubernetes', 'Automated deployment and operations for containerized applications', 'https://kubernetes.io', '#9333ea', '#9333ea', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 7.7c0-.6-.4-1.2-.8-1.5l-6.3-3.9a1.72 1.72 0 0 0-1.7 0l-10.3 6c-.5.2-.9.8-.9 1.4v6.6c0 .5.4 1.2.8 1.5l6.3 3.9a1.72 1.72 0 0 0 1.7 0l10.3-6c.5-.3.9-1 .9-1.5Z"/><path d="M10 21.9V14L2.1 9.1"/><path d="m10 14 11.9-6.9"/><path d="M14 19.8v-8.1"/><path d="M18 17.5V9.4"/></svg>', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(5, 40, 'tech_card', '', NULL, 'CI/CD & source control', 'GitHub', 'Version control and automated deployment pipelines', 'https://github.com', '#cbd5e1', '#e2e8f0', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="6" x2="6" y1="3" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(6, 50, 'tech_card', '', NULL, 'Container registry', 'Docker Hub', 'Managing and distributing Docker images', 'https://hub.docker.com', '#0891b2', '#0891b2', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="8" x="2" y="2" rx="2" ry="2"/><rect width="20" height="8" x="2" y="14" rx="2" ry="2"/><line x1="6" x2="6.01" y1="6" y2="6"/><line x1="6" x2="6.01" y1="18" y2="18"/></svg>', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(7, 60, 'tech_card', '', NULL, 'Billing & customer management', 'HostBill', 'Professional automated billing and CRM', 'https://hostbill.com', '#4f46e5', '#4f46e5', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(8, 70, 'tech_card', '', NULL, 'Object storage', 'S3/R2', 'Scalable storage for data and backups', 'https://www.cloudflare.com/developer-platform/r2/', '#ea580c', '#ea580c', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(9, 80, 'tech_card', '', NULL, 'Network & security', 'Cloudflare', 'CDN, DDoS protection, and DNS services', 'https://www.cloudflare.com', '#d97706', '#d97706', '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON CONFLICT (id) DO NOTHING;

SELECT setval(
    pg_get_serial_sequence('tech_stack_items', 'id'),
    COALESCE((SELECT MAX(id) FROM tech_stack_items), 1)
);
