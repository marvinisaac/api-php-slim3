ALTER TABLE `object`
	CHANGE COLUMN `name` `ordinal_position_long` VARCHAR(8) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci' AFTER `_id`,
	ADD COLUMN `ordinal_position_short` VARCHAR(8) NULL DEFAULT NULL AFTER `ordinal_position_long`;

UPDATE `api_php_slim3`.`object` SET `ordinal_position_short`='1st' WHERE  `_id`=1;
UPDATE `api_php_slim3`.`object` SET `ordinal_position_short`='2nd' WHERE  `_id`=2;
UPDATE `api_php_slim3`.`object` SET `ordinal_position_short`='3rd' WHERE  `_id`=3;
UPDATE `api_php_slim3`.`object` SET `ordinal_position_short`='4th' WHERE  `_id`=4;
UPDATE `api_php_slim3`.`object` SET `ordinal_position_short`='5th' WHERE  `_id`=5;
UPDATE `api_php_slim3`.`object` SET `ordinal_position_short`='6th' WHERE  `_id`=6;
UPDATE `api_php_slim3`.`object` SET `ordinal_position_short`='7th' WHERE  `_id`=7;
UPDATE `api_php_slim3`.`object` SET `ordinal_position_short`='8th' WHERE  `_id`=8;
UPDATE `api_php_slim3`.`object` SET `ordinal_position_short`='9th' WHERE  `_id`=9;
UPDATE `api_php_slim3`.`object` SET `ordinal_position_short`='10th' WHERE  `_id`=10;
