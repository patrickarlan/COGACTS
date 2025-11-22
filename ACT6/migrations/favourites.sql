CREATE TABLE IF NOT EXISTS `favourites` (
  `user_id` INT NOT NULL,
  `product_key` VARCHAR(100) NOT NULL,
  `product_title` VARCHAR(255) NULL,
  `product_image` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`product_key`),
  INDEX (`user_id`),
  CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;