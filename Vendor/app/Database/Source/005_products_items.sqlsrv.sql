-- Products & services page CMS (Microsoft SQL Server).

IF OBJECT_ID(N'dbo.products_items', N'U') IS NULL
CREATE TABLE dbo.products_items (
    id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    sort_order INT NOT NULL DEFAULT 0,
    kind NVARCHAR(32) NOT NULL DEFAULT N'product_card',
    title NVARCHAR(255) NOT NULL DEFAULT N'',
    description NVARCHAR(MAX) NULL,
    bullets NVARCHAR(MAX) NULL,
    sub_line NVARCHAR(255) NULL,
    href NVARCHAR(1024) NULL,
    card_style NVARCHAR(64) NULL,
    icon_svg NVARCHAR(MAX) NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL
);

IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = N'products_items_sort_idx' AND object_id = OBJECT_ID(N'dbo.products_items'))
CREATE INDEX products_items_sort_idx ON dbo.products_items (sort_order);

IF NOT EXISTS (SELECT 1 FROM dbo.products_items WHERE id = 1)
BEGIN
    SET IDENTITY_INSERT dbo.products_items ON;
    INSERT INTO dbo.products_items (id, sort_order, kind, title, description, bullets, sub_line, href, card_style, icon_svg, created_at, updated_at) VALUES
    (1, 0, N'page_heading', N'Products & services', N'We build innovative products and deliver hosting at a high standard', NULL, NULL, NULL, NULL, NULL, SYSUTCDATETIME(), SYSUTCDATETIME()),
    (2, 10, N'product_card', N'RaidSentinel →', N'An advanced monitoring and security Discord bot offering comprehensive anti-raid protection and moderation automation.', NULL, N'raidsentinel.app', N'https://raidsentinel.app', N'grad-blue', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (3, 20, N'product_card', N'RaidSentinel Backup →', N'A full backup system for Discord servers with scheduled backups and fast server restoration.', NULL, N'raidsentinel.app', N'https://raidsentinel.app', N'grad-cyan', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (4, 30, N'product_card', N'JokerBot →', N'A multi-purpose Discord bot with advanced features for moderation, fun, and community server management.', NULL, N'github.com/Polda18/JokerBot', N'https://github.com/Polda18/JokerBot/blob/master/README.md', N'grad-emerald', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (5, 40, N'product_card', N'Tic-tac-toe (Gomoku-style) →', N'An interactive Discord bot for tic-tac-toe style play with single-player and multi-player modes and a custom language pack.', N'Single & multi-player
Custom language pack', N'tictactoe-bot.xyz', N'https://tictactoe-bot.xyz/docs', N'grad-purple', N'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/></svg>', SYSUTCDATETIME(), SYSUTCDATETIME());
    SET IDENTITY_INSERT dbo.products_items OFF;
END
