-- PostgreSQL preset schema (used when DBDriver is Postgre).
-- Pair with MySQL file 001_preset_tables.sql for MySQLi.

CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(32) NOT NULL DEFAULT 'user',
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS site_settings (
  id SMALLINT PRIMARY KEY,
  site_name VARCHAR(255) NOT NULL DEFAULT '',
  site_description TEXT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL
);

INSERT INTO site_settings (id, site_name, site_description, updated_at)
VALUES (1, '', '', CURRENT_TIMESTAMP)
ON CONFLICT (id) DO NOTHING;
