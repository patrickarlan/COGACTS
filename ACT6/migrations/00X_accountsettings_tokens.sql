ALTER TABLE users
  ADD COLUMN email_verified TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN email_verified_at DATETIME NULL,
  ADD COLUMN pending_email VARCHAR(255) NULL,
  ADD COLUMN email_change_token VARCHAR(128) NULL,
  ADD COLUMN email_change_expires DATETIME NULL,
  ADD COLUMN password_change_token VARCHAR(128) NULL,
  ADD COLUMN password_change_expires DATETIME NULL,
  ADD COLUMN password_last_changed DATETIME NULL;