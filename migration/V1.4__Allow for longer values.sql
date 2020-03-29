ALTER TABLE `object`
	CHANGE COLUMN `ordinal_position_long` `ordinal_position_long` VARCHAR(16) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci' AFTER `_id`;
