ALTER TABLE `settings` DROP PRIMARY KEY;

ALTER TABLE
    `settings`
ADD
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
FIRST;

ALTER TABLE
    `settings`
ADD
    `type` ENUM('empty', 'text', 'serialised', 'boolean')
NOT NULL DEFAULT 'empty';

ALTER TABLE
    `settings`
ADD
    `archive` boolean
DEFAULT FALSE;

ALTER TABLE
    `settings`
ADD `prefix` VARCHAR (32);

ALTER TABLE
    `settings`
ADD INDEX(`type`);

ALTER TABLE
    `settings`
ADD INDEX(`archive`);

ALTER TABLE
    `settings`
ADD INDEX(`prefix`);

ALTER TABLE
    `settings`
ADD UNIQUE(`name`);

ALTER TABLE
    `settings`
ADD FULLTEXT(`value`);

ALTER TABLE
    `settings`
ADD INDEX(`description`);

UPDATE `settings`
SET `prefix` = REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(`name`, '.', 1),'-',1),'_',1), `name`, 'OTHER');

