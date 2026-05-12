-- Services page CMS (Microsoft SQL Server).

IF OBJECT_ID(N'dbo.services_items', N'U') IS NULL
CREATE TABLE dbo.services_items (
    id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    sort_order INT NOT NULL DEFAULT 0,
    kind NVARCHAR(32) NOT NULL DEFAULT N'service_card',
    title NVARCHAR(255) NOT NULL DEFAULT N'',
    description NVARCHAR(MAX) NULL,
    bullets NVARCHAR(MAX) NULL,
    image_url NVARCHAR(1024) NULL,
    image_alt NVARCHAR(255) NULL,
    icon_svg NVARCHAR(MAX) NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL
);

IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = N'services_items_sort_idx' AND object_id = OBJECT_ID(N'dbo.services_items'))
CREATE INDEX services_items_sort_idx ON dbo.services_items (sort_order);

IF NOT EXISTS (SELECT 1 FROM dbo.services_items WHERE id = 1)
BEGIN
    SET IDENTITY_INSERT dbo.services_items ON;
    INSERT INTO dbo.services_items (id, sort_order, kind, title, description, bullets, image_url, image_alt, icon_svg, created_at, updated_at) VALUES
    (1, 0, N'page_heading', N'Our services', N'We deliver high-quality solutions in digital art and interactive entertainment', NULL, NULL, NULL, NULL, SYSUTCDATETIME(), SYSUTCDATETIME()),
    (2, 10, N'service_card', N'Discord bots', N'Development of advanced Discord bots with a focus on security, automation, and user experience.', N'Security and monitoring systems
Automation and moderation
Server backups
Custom functionality', N'https://images.unsplash.com/photo-1649451844931-57e22fc82de3?w=1080&q=80', N'Discord development', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (3, 20, N'service_card', N'Game & bot hosting', N'High-performance hosting for game servers and Discord bots on top-tier infrastructure.', N'AMD Ryzen 7 9700X + 64GB RAM
Proxmox VE virtualization
HostBill billing system
Gaming Anti-DDoS protection
Focus on CZ/SK communities', N'https://images.unsplash.com/photo-1667984390553-7f439e6ae401?w=1080&q=80', N'Cloud hosting', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (4, 30, N'service_card', N'Web applications & tools', N'End-to-end solutions for online platforms including web apps, dashboards, and API services.', N'Modern web applications
RESTful APIs
Admin dashboards
Database systems', N'https://images.unsplash.com/photo-1526242767279-2ad8d8271177?w=1080&q=80', N'Web workspace', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME());
    SET IDENTITY_INSERT dbo.services_items OFF;
END
