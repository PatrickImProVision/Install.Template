-- Technology stack CMS (Microsoft SQL Server).

IF OBJECT_ID(N'dbo.tech_stack_items', N'U') IS NULL
CREATE TABLE dbo.tech_stack_items (
    id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    sort_order INT NOT NULL DEFAULT 0,
    kind NVARCHAR(32) NOT NULL DEFAULT N'tech_card',
    title NVARCHAR(255) NOT NULL DEFAULT N'',
    description NVARCHAR(MAX) NULL,
    category NVARCHAR(255) NULL,
    product_name NVARCHAR(255) NULL,
    blurb NVARCHAR(MAX) NULL,
    href NVARCHAR(1024) NULL,
    icon_color NVARCHAR(32) NULL,
    name_color NVARCHAR(32) NULL,
    icon_svg NVARCHAR(MAX) NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL
);

IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = N'tech_stack_items_sort_idx' AND object_id = OBJECT_ID(N'dbo.tech_stack_items'))
CREATE INDEX tech_stack_items_sort_idx ON dbo.tech_stack_items (sort_order);

IF NOT EXISTS (SELECT 1 FROM dbo.tech_stack_items WHERE id = 1)
BEGIN
    SET IDENTITY_INSERT dbo.tech_stack_items ON;
    INSERT INTO dbo.tech_stack_items (id, sort_order, kind, title, description, category, product_name, blurb, href, icon_color, name_color, icon_svg, created_at, updated_at) VALUES
    (1, 0, N'page_heading', N'Technology stack', N'We use modern, reliable technology to maximize performance and security', NULL, NULL, NULL, NULL, NULL, NULL, NULL, SYSUTCDATETIME(), SYSUTCDATETIME()),
    (2, 10, N'tech_card', N'', NULL, N'Infrastructure', N'OVHcloud', N'Reliable cloud infrastructure for hosting and performance', N'https://www.ovhcloud.com', N'#2563eb', N'#2563eb', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (3, 20, N'tech_card', N'', NULL, N'Virtualization', N'Proxmox VE', N'A virtualization platform for managing VPS and containers', N'https://www.proxmox.com', N'#16a34a', N'#16a34a', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="8" x="2" y="2" rx="2" ry="2"/><rect width="20" height="8" x="2" y="14" rx="2" ry="2"/><line x1="6" x2="6.01" y1="6" y2="6"/><line x1="6" x2="6.01" y1="18" y2="18"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (4, 30, N'tech_card', N'', NULL, N'Orchestration', N'Kubernetes', N'Automated deployment and operations for containerized applications', N'https://kubernetes.io', N'#9333ea', N'#9333ea', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 7.7c0-.6-.4-1.2-.8-1.5l-6.3-3.9a1.72 1.72 0 0 0-1.7 0l-10.3 6c-.5.2-.9.8-.9 1.4v6.6c0 .5.4 1.2.8 1.5l6.3 3.9a1.72 1.72 0 0 0 1.7 0l10.3-6c.5-.3.9-1 .9-1.5Z"/><path d="M10 21.9V14L2.1 9.1"/><path d="m10 14 11.9-6.9"/><path d="M14 19.8v-8.1"/><path d="M18 17.5V9.4"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (5, 40, N'tech_card', N'', NULL, N'CI/CD & source control', N'GitHub', N'Version control and automated deployment pipelines', N'https://github.com', N'#cbd5e1', N'#e2e8f0', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="6" x2="6" y1="3" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (6, 50, N'tech_card', N'', NULL, N'Container registry', N'Docker Hub', N'Managing and distributing Docker images', N'https://hub.docker.com', N'#0891b2', N'#0891b2', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="8" x="2" y="2" rx="2" ry="2"/><rect width="20" height="8" x="2" y="14" rx="2" ry="2"/><line x1="6" x2="6.01" y1="6" y2="6"/><line x1="6" x2="6.01" y1="18" y2="18"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (7, 60, N'tech_card', N'', NULL, N'Billing & customer management', N'HostBill', N'Professional automated billing and CRM', N'https://hostbill.com', N'#4f46e5', N'#4f46e5', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (8, 70, N'tech_card', N'', NULL, N'Object storage', N'S3/R2', N'Scalable storage for data and backups', N'https://www.cloudflare.com/developer-platform/r2/', N'#ea580c', N'#ea580c', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (9, 80, N'tech_card', N'', NULL, N'Network & security', N'Cloudflare', N'CDN, DDoS protection, and DNS services', N'https://www.cloudflare.com', N'#d97706', N'#d97706', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME());
    SET IDENTITY_INSERT dbo.tech_stack_items OFF;
END
