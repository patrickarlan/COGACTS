-- Migration: add profile columns to users table
-- Backup your database before running this.

ALTER TABLE users
  ADD COLUMN first_name VARCHAR(100) NULL,
  ADD COLUMN last_name VARCHAR(100) NULL,
  ADD COLUMN contact_number VARCHAR(32) NULL,
  ADD COLUMN region VARCHAR(100) NULL,
  ADD COLUMN country VARCHAR(100) NULL,
  ADD COLUMN postal_id VARCHAR(32) NULL,
  ADD COLUMN address TEXT NULL;

-- End of migration
