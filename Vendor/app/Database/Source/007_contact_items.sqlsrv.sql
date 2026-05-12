-- Contact page & site footer CMS (Microsoft SQL Server).

IF OBJECT_ID(N'dbo.contact_items', N'U') IS NULL
CREATE TABLE dbo.contact_items (
    id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    sort_order INT NOT NULL DEFAULT 0,
    kind NVARCHAR(32) NOT NULL DEFAULT N'contact_entry',
    column_group NVARCHAR(32) NOT NULL DEFAULT N'contact',
    title NVARCHAR(255) NOT NULL DEFAULT N'',
    description NVARCHAR(MAX) NULL,
    meta_label NVARCHAR(255) NULL,
    href NVARCHAR(1024) NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL
);

IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = N'contact_items_sort_idx' AND object_id = OBJECT_ID(N'dbo.contact_items'))
CREATE INDEX contact_items_sort_idx ON dbo.contact_items (sort_order);

IF NOT EXISTS (SELECT 1 FROM dbo.contact_items WHERE id = 1)
BEGIN
    SET IDENTITY_INSERT dbo.contact_items ON;
    INSERT INTO dbo.contact_items (id, sort_order, kind, column_group, title, description, meta_label, href, created_at, updated_at) VALUES
    (1, 0, N'page_heading', N'page', N'Contact', N'Get in touch for collaborations, partnerships, and support.', NULL, NULL, SYSUTCDATETIME(), SYSUTCDATETIME()),
    (2, 10, N'brand', N'intro', N'Visionary Digital Arts', N'A studio specializing in software for digital art and interactive entertainment.', NULL, NULL, SYSUTCDATETIME(), SYSUTCDATETIME()),
    (3, 20, N'column_heading', N'company', N'Company structure', NULL, NULL, NULL, SYSUTCDATETIME(), SYSUTCDATETIME()),
    (4, 30, N'company_entry', N'company', N'Visionary Interactive s.r.o.', NULL, N'Owner', NULL, SYSUTCDATETIME(), SYSUTCDATETIME()),
    (5, 40, N'company_entry', N'company', N'Visionary Digital Arts', NULL, N'Development studio', NULL, SYSUTCDATETIME(), SYSUTCDATETIME()),
    (6, 50, N'company_entry', N'company', N'StingerHost.eu', NULL, N'Game & bot hosting', N'https://stingerhost.eu', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (7, 60, N'column_heading', N'contact', N'Contact', NULL, NULL, NULL, SYSUTCDATETIME(), SYSUTCDATETIME()),
    (8, 70, N'contact_entry', N'contact', N'info@visionarydigitalarts.cz', NULL, NULL, N'mailto:info@visionarydigitalarts.cz', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (9, 80, N'contact_entry', N'contact', N'Czech Republic', NULL, N'meta', NULL, SYSUTCDATETIME(), SYSUTCDATETIME()),
    (10, 90, N'contact_entry', N'contact', N'Discord community', NULL, NULL, N'https://discord.gg/3PeXKZJdBs', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (11, 100, N'contact_entry', N'contact', N'https://vd-arts.studio', NULL, NULL, N'https://vd-arts.studio', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (12, 110, N'legal', N'legal', N'', N'© {year} Visionary Digital Arts. All rights reserved.', NULL, NULL, SYSUTCDATETIME(), SYSUTCDATETIME()),
    (13, 120, N'legal', N'legal', N'', N'Part of Visionary Interactive s.r.o.', NULL, NULL, SYSUTCDATETIME(), SYSUTCDATETIME());
    SET IDENTITY_INSERT dbo.contact_items OFF;
END
