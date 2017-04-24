# The id field should be auto-incremented
ALTER TABLE `mailjet_newsletter` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;
