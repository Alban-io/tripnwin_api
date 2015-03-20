-- -----------------------------------------------------
-- Table `poi`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `poi` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  `latitude` DECIMAL(10,7) NOT NULL,
  `longitude` DECIMAL(10,7) NOT NULL,
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
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENTL,
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