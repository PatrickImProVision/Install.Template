-- About Us CMS blocks (Microsoft SQL Server).

IF OBJECT_ID(N'dbo.about_us_items', N'U') IS NULL
CREATE TABLE dbo.about_us_items (
    id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    sort_order INT NOT NULL DEFAULT 0,
    placement NVARCHAR(32) NOT NULL DEFAULT N'stack',
    kind NVARCHAR(32) NOT NULL DEFAULT N'company_card',
    title NVARCHAR(255) NOT NULL DEFAULT N'',
    description NVARCHAR(MAX) NULL,
    href NVARCHAR(512) NULL,
    icon_key NVARCHAR(64) NULL,
    bullets NVARCHAR(MAX) NULL,
    footnote NVARCHAR(512) NULL,
    card_style NVARCHAR(32) NOT NULL DEFAULT N'',
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL
);

IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = N'about_us_items_sort_idx' AND object_id = OBJECT_ID(N'dbo.about_us_items'))
CREATE INDEX about_us_items_sort_idx ON dbo.about_us_items (sort_order);

IF NOT EXISTS (SELECT 1 FROM dbo.about_us_items WHERE id = 1)
BEGIN
    SET IDENTITY_INSERT dbo.about_us_items ON;
    INSERT INTO dbo.about_us_items (id, sort_order, placement, kind, title, description, href, icon_key, bullets, footnote, card_style, created_at, updated_at) VALUES
    (1, 0, N'page_header', N'page_heading', N'About us', N'We are a dynamic studio with a clear vision and a strong foundation', NULL, NULL, NULL, NULL, N'', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (2, 10, N'intro', N'mission', N'Our mission', N'Visionary Digital Arts is a dynamic studio specializing in software for digital art and interactive entertainment.
As part of Visionary Interactive s.r.o., we focus on delivering high-quality solutions—including advanced Discord bots,
web applications, and hosting services for online platforms. We emphasize an innovative approach, precise execution, and a strong user experience.', NULL, NULL, NULL, NULL, N'', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (3, 11, N'intro', N'badge', N'Innovation', NULL, NULL, N'innovation', NULL, NULL, N'', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (4, 12, N'intro', N'badge', N'Expert team', NULL, NULL, N'team', NULL, NULL, N'', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (5, 13, N'intro', N'badge', N'Strong foundation', NULL, NULL, N'foundation', NULL, NULL, N'', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (6, 20, N'stack', N'company_card', N'Visionary Interactive s.r.o.', N'Owner and parent company providing strategic leadership', NULL, NULL, NULL, NULL, N'', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (7, 21, N'stack', N'company_card', N'Visionary Digital Arts', N'Development studio focused on digital art and interactive entertainment', NULL, NULL, NULL, N'Develops products such as RaidSentinel', N'blue', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (8, 22, N'stack', N'company_card', N'StingerHost.eu', N'Hosting platform for game servers and Discord bots built on OVHcloud infrastructure with Proxmox virtualization and HostBill billing.', N'https://stingerhost.eu', NULL, N'AMD Ryzen 7 9700X, 64GB RAM, NVMe SSD
Proxmox VE virtualization
HostBill automated billing
Gaming Anti-DDoS protection
Focus on CZ/SK communities', N'Founded 2024 · stingerhost.eu', N'amber', SYSUTCDATETIME(), SYSUTCDATETIME());
    SET IDENTITY_INSERT dbo.about_us_items OFF;
END
