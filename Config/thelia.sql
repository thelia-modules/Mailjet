
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- mailjet_newsletter
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `mailjet_newsletter`;

CREATE TABLE `mailjet_newsletter`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `mailjet_id` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `relation_id` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `email_UNIQUE` (`email`),
    UNIQUE INDEX `relation_id_UNIQUE` (`relation_id`),
    INDEX `idx_mailjet_newsletter_email` (`email`),
    INDEX `idx_mailjet_newsletter_relation_id` (`relation_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- mailjet_contact_list
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `mailjet_contact_list`;

CREATE TABLE `mailjet_contact_list`
(
    `id_cl` INTEGER NOT NULL AUTO_INCREMENT,
    `name_cl` VARCHAR(255) NOT NULL,
    `slug_cl` VARCHAR(255) NOT NULL,
    `locale` VARCHAR(255) NOT NULL,
    `default_list` TINYINT(1) NOT NULL,
    PRIMARY KEY (`id_cl`),
    UNIQUE INDEX `name_UNIQUE` (`name_cl`),
    UNIQUE INDEX `slug_UNIQUE` (`slug_cl`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
