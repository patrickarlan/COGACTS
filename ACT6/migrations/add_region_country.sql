-- Migration: add region and country columns to users
ALTER TABLE `users`
  ADD COLUMN `region` VARCHAR(150) NULL AFTER `address`,
  ADD COLUMN `country` VARCHAR(150) NULL AFTER `region`;

-- Run this in your MySQL client (phpMyAdmin or mysql CLI). It will add the columns only if they don't already exist.
