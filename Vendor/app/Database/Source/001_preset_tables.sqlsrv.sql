-- Microsoft SQL Server preset schema (used when DBDriver is SQLSRV).

IF OBJECT_ID(N'dbo.users', N'U') IS NULL
CREATE TABLE dbo.users (
    id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    email NVARCHAR(255) NOT NULL UNIQUE,
    password_hash NVARCHAR(255) NOT NULL,
    role NVARCHAR(32) NOT NULL DEFAULT N'user',
    created_at DATETIME2 NOT NULL,
    updated_at DATETIME2 NULL
);

IF OBJECT_ID(N'dbo.site_settings', N'U') IS NULL
CREATE TABLE dbo.site_settings (
    id TINYINT NOT NULL PRIMARY KEY,
    site_name NVARCHAR(255) NOT NULL DEFAULT N'',
    site_description NVARCHAR(MAX) NULL,
    updated_at DATETIME2 NULL
);

IF NOT EXISTS (SELECT 1 FROM dbo.site_settings WHERE id = 1)
INSERT INTO dbo.site_settings (id, site_name, site_description, updated_at)
VALUES (1, N'', N'', SYSUTCDATETIME());
