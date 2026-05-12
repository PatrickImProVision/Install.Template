-- Our values page CMS (SQLite 3).

CREATE TABLE IF NOT EXISTS values_items (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  sort_order INTEGER NOT NULL DEFAULT 0,
  kind TEXT NOT NULL DEFAULT 'value_item',
  title TEXT NOT NULL DEFAULT '',
  description TEXT NULL,
  emoji TEXT NULL,
  created_at TEXT NULL,
  updated_at TEXT NULL
);

CREATE INDEX IF NOT EXISTS values_items_sort_idx ON values_items (sort_order);

INSERT OR IGNORE INTO values_items (id, sort_order, kind, title, description, emoji, created_at, updated_at) VALUES
(1, 0, 'page_heading', 'Our values', NULL, NULL, datetime('now'), datetime('now')),
(2, 10, 'value_item', 'Innovative approach', 'We explore new paths and technologies to achieve the best results.', '🎯', datetime('now'), datetime('now')),
(3, 20, 'value_item', 'Precise execution', 'Every detail matters, and we give it our full attention.', '⚙️', datetime('now'), datetime('now')),
(4, 30, 'value_item', 'User experience', 'Our priority is building a strong, memorable experience for users.', '💫', datetime('now'), datetime('now'));
