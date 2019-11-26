# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- mailjet_newsletter
-- ---------------------------------------------------------------------

ALTER TABLE `mailjet_newsletter` MODIFY `relation_id` INT;

ALTER TABLE `mailjet_newsletter` MODIFY `id` VARCHAR(255);

ALTER TABLE `mailjet_newsletter` CHANGE `id` `mailjet_id` VARCHAR(255);

ALTER TABLE `mailjet_newsletter` ADD COLUMN `id` INT NOT NULL AUTO_INCREMENT UNIQUE FIRST;

ALTER TABLE `mailjet_newsletter`
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (`id`);

ALTER TABLE `mailjet_newsletter` ADD COLUMN `relation_id` VARCHAR(255);

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
