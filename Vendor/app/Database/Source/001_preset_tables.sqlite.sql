-- SQLite preset schema (used when DBDriver is SQLite3).

CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  email TEXT NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  role TEXT NOT NULL DEFAULT 'user',
  created_at TEXT NOT NULL,
  updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS site_settings (
  id INTEGER PRIMARY KEY CHECK (id = 1),
  site_name TEXT NOT NULL DEFAULT '',
  site_description TEXT NULL,
  updated_at TEXT NULL
);

INSERT OR IGNORE INTO site_settings (id, site_name, site_description, updated_at)
VALUES (1, '', '', datetime('now'));
