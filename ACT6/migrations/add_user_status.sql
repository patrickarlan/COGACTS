ALTER TABLE users
ADD COLUMN status ENUM('active','deactivated') NOT NULL DEFAULT 'active',
ADD COLUMN deactivated_at DATETIME NULL DEFAULT NULL,
ADD COLUMN deactivated_by INT NULL DEFAULT NULL;