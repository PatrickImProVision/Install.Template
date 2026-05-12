-- Our values page CMS (PostgreSQL).

CREATE TABLE IF NOT EXISTS values_items (
  id SERIAL PRIMARY KEY,
  sort_order INTEGER NOT NULL DEFAULT 0,
  kind VARCHAR(32) NOT NULL DEFAULT 'value_item',
  title VARCHAR(255) NOT NULL DEFAULT '',
  description TEXT NULL,
  emoji VARCHAR(64) NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE INDEX IF NOT EXISTS values_items_sort_idx ON values_items (sort_order);

INSERT INTO values_items (id, sort_order, kind, title, description, emoji, created_at, updated_at) VALUES
(1, 0, 'page_heading', 'Our values', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(2, 10, 'value_item', 'Innovative approach', 'We explore new paths and technologies to achieve the best results.', '🎯', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(3, 20, 'value_item', 'Precise execution', 'Every detail matters, and we give it our full attention.', '⚙️', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(4, 30, 'value_item', 'User experience', 'Our priority is building a strong, memorable experience for users.', '💫', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON CONFLICT (id) DO NOTHING;

SELECT setval(
    pg_get_serial_sequence('values_items', 'id'),
    COALESCE((SELECT MAX(id) FROM values_items), 1)
);
