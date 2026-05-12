-- Per-page SEO (Microsoft SQL Server).
-- Meta copy matches App\Controllers\Home public SEO fallbacks (Visionary Digital Arts defaults).

IF OBJECT_ID(N'dbo.seo_pages', N'U') IS NULL
CREATE TABLE dbo.seo_pages (
    id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    page_key NVARCHAR(64) NOT NULL,
    meta_title NVARCHAR(255) NOT NULL DEFAULT N'',
    meta_description NVARCHAR(MAX) NULL,
    meta_keywords NVARCHAR(512) NOT NULL DEFAULT N'',
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT seo_pages_page_key_unique UNIQUE (page_key)
);

IF NOT EXISTS (SELECT 1 FROM dbo.seo_pages WHERE id = 1)
BEGIN
    SET IDENTITY_INSERT dbo.seo_pages ON;
    INSERT INTO dbo.seo_pages (id, page_key, meta_title, meta_description, meta_keywords, created_at, updated_at) VALUES
    (1, N'home', N'Visionary Digital Arts', N'Visionary Digital Arts — A dynamic studio specializing in software for digital art and interactive entertainment.', N'Visionary Digital Arts, VD-Arts, software development, digital arts, interactive entertainment, Discord bots', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (2, N'about-us', N'About us — Visionary Digital Arts', N'Visionary Digital Arts — mission, company structure, and hosting partner.', N'Visionary Digital Arts, VD-Arts, software development, digital arts, interactive entertainment, Discord bots', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (3, N'services', N'Services — Visionary Digital Arts', N'Discord bots, hosting, and web applications — Visionary Digital Arts.', N'Visionary Digital Arts, VD-Arts, Discord bots, hosting, web applications', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (4, N'products', N'Products & services — Visionary Digital Arts', N'RaidSentinel, JokerBot, Discord bots, and hosting — Visionary Digital Arts.', N'Visionary Digital Arts, VD-Arts, RaidSentinel, Discord bots, hosting', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (5, N'tech-stack', N'Technology stack — Visionary Digital Arts', N'Technology stack — OVHcloud, Proxmox, Kubernetes, GitHub, Docker, HostBill, Cloudflare, and more.', N'Visionary Digital Arts, technology stack, DevOps, hosting, Kubernetes, Cloudflare', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (6, N'values', N'Our values — Visionary Digital Arts', N'Innovation, precision, and user experience — Visionary Digital Arts.', N'Visionary Digital Arts, values, innovation, user experience', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (7, N'contact', N'Contact — Visionary Digital Arts', N'Contact Visionary Digital Arts — email, Discord, and company information.', N'Visionary Digital Arts, contact, email, Discord', SYSUTCDATETIME(), SYSUTCDATETIME());
    SET IDENTITY_INSERT dbo.seo_pages OFF;
END

UPDATE dbo.seo_pages SET meta_title = N'Visionary Digital Arts', meta_description = N'Visionary Digital Arts — A dynamic studio specializing in software for digital art and interactive entertainment.', meta_keywords = N'Visionary Digital Arts, VD-Arts, software development, digital arts, interactive entertainment, Discord bots', updated_at = SYSUTCDATETIME() WHERE page_key = N'home';
UPDATE dbo.seo_pages SET meta_title = N'About us — Visionary Digital Arts', meta_description = N'Visionary Digital Arts — mission, company structure, and hosting partner.', meta_keywords = N'Visionary Digital Arts, VD-Arts, software development, digital arts, interactive entertainment, Discord bots', updated_at = SYSUTCDATETIME() WHERE page_key = N'about-us';
UPDATE dbo.seo_pages SET meta_title = N'Services — Visionary Digital Arts', meta_description = N'Discord bots, hosting, and web applications — Visionary Digital Arts.', meta_keywords = N'Visionary Digital Arts, VD-Arts, Discord bots, hosting, web applications', updated_at = SYSUTCDATETIME() WHERE page_key = N'services';
UPDATE dbo.seo_pages SET meta_title = N'Products & services — Visionary Digital Arts', meta_description = N'RaidSentinel, JokerBot, Discord bots, and hosting — Visionary Digital Arts.', meta_keywords = N'Visionary Digital Arts, VD-Arts, RaidSentinel, Discord bots, hosting', updated_at = SYSUTCDATETIME() WHERE page_key = N'products';
UPDATE dbo.seo_pages SET meta_title = N'Technology stack — Visionary Digital Arts', meta_description = N'Technology stack — OVHcloud, Proxmox, Kubernetes, GitHub, Docker, HostBill, Cloudflare, and more.', meta_keywords = N'Visionary Digital Arts, technology stack, DevOps, hosting, Kubernetes, Cloudflare', updated_at = SYSUTCDATETIME() WHERE page_key = N'tech-stack';
UPDATE dbo.seo_pages SET meta_title = N'Our values — Visionary Digital Arts', meta_description = N'Innovation, precision, and user experience — Visionary Digital Arts.', meta_keywords = N'Visionary Digital Arts, values, innovation, user experience', updated_at = SYSUTCDATETIME() WHERE page_key = N'values';
UPDATE dbo.seo_pages SET meta_title = N'Contact — Visionary Digital Arts', meta_description = N'Contact Visionary Digital Arts — email, Discord, and company information.', meta_keywords = N'Visionary Digital Arts, contact, email, Discord', updated_at = SYSUTCDATETIME() WHERE page_key = N'contact';
