CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `product_key` VARCHAR(100) NOT NULL,
  `product_title` VARCHAR(255) NULL,
  `product_image` VARCHAR(255) NULL,
  `qty` INT NOT NULL DEFAULT 1,
  `status` ENUM('pending','cancelled','completed') NOT NULL DEFAULT 'pending',
  `payment_method` VARCHAR(50) NOT NULL DEFAULT 'cash',
  `contact_snapshot` VARCHAR(100) NULL,
  `address_snapshot` TEXT NULL,
  `total_amount` DECIMAL(10,2) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`user_id`),
  INDEX (`product_key`),
  CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;