USE `twink`;

CREATE TABLE `users` (
  `user_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `pass` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  INDEX `login` (`name` ASC, `pass` ASC)
) ENGINE = InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `pages` (
  `page_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` LONGTEXT NOT NULL,
  `title` VARCHAR(80) NULL,
  `url_key` VARCHAR(20) NOT NULL,
  `sort_order` SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (`page_id`),
  UNIQUE INDEX `url_key_unique` (`url_key` ASC)
) ENGINE = InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `events` (
  `event_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` TEXT NULL,
  `datetime` DATETIME NOT NULL,
  `title` VARCHAR(60) NOT NULL,
  `location` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE = InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `photos` (
  `photo_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `url` VARCHAR(80) NOT NULL,
  `thumbnail` VARCHAR(80) NOT NULL,
  `title` VARCHAR(80) NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE = InnoDB  DEFAULT CHARSET=utf8;