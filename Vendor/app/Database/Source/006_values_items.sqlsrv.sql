-- Our values page CMS (Microsoft SQL Server).

IF OBJECT_ID(N'dbo.values_items', N'U') IS NULL
CREATE TABLE dbo.values_items (
    id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    sort_order INT NOT NULL DEFAULT 0,
    kind NVARCHAR(32) NOT NULL DEFAULT N'value_item',
    title NVARCHAR(255) NOT NULL DEFAULT N'',
    description NVARCHAR(MAX) NULL,
    emoji NVARCHAR(64) NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL
);

IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = N'values_items_sort_idx' AND object_id = OBJECT_ID(N'dbo.values_items'))
CREATE INDEX values_items_sort_idx ON dbo.values_items (sort_order);

IF NOT EXISTS (SELECT 1 FROM dbo.values_items WHERE id = 1)
BEGIN
    SET IDENTITY_INSERT dbo.values_items ON;
    INSERT INTO dbo.values_items (id, sort_order, kind, title, description, emoji, created_at, updated_at) VALUES
    (1, 0, N'page_heading', N'Our values', NULL, NULL, SYSUTCDATETIME(), SYSUTCDATETIME()),
    (2, 10, N'value_item', N'Innovative approach', N'We explore new paths and technologies to achieve the best results.', N'🎯', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (3, 20, N'value_item', N'Precise execution', N'Every detail matters, and we give it our full attention.', N'⚙️', SYSUTCDATETIME(), SYSUTCDATETIME()),
    (4, 30, N'value_item', N'User experience', N'Our priority is building a strong, memorable experience for users.', N'💫', SYSUTCDATETIME(), SYSUTCDATETIME());
    SET IDENTITY_INSERT dbo.values_items OFF;
END
