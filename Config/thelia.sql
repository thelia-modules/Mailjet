
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
    `relation_id` INTEGER,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `email_UNIQUE` (`email`),
    UNIQUE INDEX `relation_id_UNIQUE` (`relation_id`),
    INDEX `idx_mailjet_newsletter_email` (`email`),
    INDEX `idx_mailjet_newsletter_relation_id` (`relation_id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
