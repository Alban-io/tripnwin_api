-- -----------------------------------------------------
-- Table `poi`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `poi` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  `rue` VARCHAR(128) NOT NULL,
  `localite` VARCHAR(128) NOT NULL,
  `commune` VARCHAR(128) NOT NULL,
  `province` VARCHAR(128) NOT NULL,
  `photo` VARCHAR(128) NULL,
  `url` VARCHAR(128) NULL,
  `email` VARCHAR(128) NULL,
  `tel` VARCHAR(128) NULL,
  `description` TEXT NOT NULL,
  `latitude` DECIMAL(18,14) NOT NULL,
  `longitude` DECIMAL(18,14) NOT NULL,
  `latitude_cos`DECIMAL(18,14) NOT NULL,
  `latitude_sin`DECIMAL(18,14) NOT NULL,
  `longitude_rad`DECIMAL(18,14) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coupon`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `coupon` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  `gain` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `question` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `poi_id` INT UNSIGNED NOT NULL,
  `label` TEXT NOT NULL,
  `right_answer` TEXT NOT NULL,
  `wrong_answer1` TEXT NOT NULL,
  `wrong_answer2` TEXT NULL,
  `wrong_answer3` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_question_poi1_idx` (`poi_id` ASC),
  CONSTRAINT `fk_question_poi1`
    FOREIGN KEY (`poi_id`)
    REFERENCES `poi` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lastname` VARCHAR(64) NOT NULL,
  `firstname` VARCHAR(64) NOT NULL,
  `email` VARCHAR(128) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `facebook_uid` VARCHAR(255) NULL,
  `twitter_uid` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user_won_coupon`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_won_coupon` (
  `user_id` INT UNSIGNED NOT NULL,
  `coupon_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `coupon_id`),
  INDEX `fk_user_won_coupon_coupon1_idx` (`coupon_id` ASC),
  INDEX `fk_user_won_coupon_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_has_coupon_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_coupon_coupon1`
    FOREIGN KEY (`coupon_id`)
    REFERENCES `coupon` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `poi_has_coupon`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `poi_has_coupon` (
  `poi_id` INT UNSIGNED NOT NULL,
  `coupon_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`poi_id`, `coupon_id`),
  INDEX `fk_poi_has_coupon_coupon1_idx` (`coupon_id` ASC),
  INDEX `fk_poi_has_coupon_poi1_idx` (`poi_id` ASC),
  CONSTRAINT `fk_poi_has_coupon1_poi1`
    FOREIGN KEY (`poi_id`)
    REFERENCES `poi` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_poi_has_coupon1_coupon1`
    FOREIGN KEY (`coupon_id`)
    REFERENCES `coupon` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


DELIMITER $$
DROP FUNCTION IF EXISTS `DISTANCE` $$
CREATE FUNCTION `DISTANCE`(lat1_cos DOUBLE, lat1_sin DOUBLE, lng1 DOUBLE, lat2_cos DOUBLE, lat2_sin DOUBLE, lng2 DOUBLE) RETURNS DOUBLE
DETERMINISTIC
COMMENT 'DISTANCE function in kilometers'
BEGIN
    DECLARE distance DOUBLE;

    SET distance = 6378.137 * ACOS(lat1_sin * lat2_sin + lat1_cos * lat2_cos * COS(lng1 - lng2));

    IF distance IS NOT NULL THEN
        RETURN distance;
    ELSE
        RETURN 0;
    END IF;
END $$
DELIMITER ;
