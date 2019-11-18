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

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;