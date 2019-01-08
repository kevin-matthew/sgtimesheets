-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema sgdb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema sgdb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `sgdb` DEFAULT CHARACTER SET utf8 ;
USE `sgdb` ;

-- -----------------------------------------------------
-- Table `sgdb`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sgdb`.`users` (
  `userid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `firstname` VARCHAR(255) NOT NULL,
  `middlename` VARCHAR(255) NULL,
  `lastname` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `admin` INT NOT NULL DEFAULT 0,
  `employeeid` VARCHAR(255) NOT NULL,
  `startdate` DATE NOT NULL,
  `resetpassword` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`userid`),
  UNIQUE INDEX `UserName_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `employeeID_UNIQUE` (`employeeid` ASC),
  UNIQUE INDEX `userid_UNIQUE` (`userid` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sgdb`.`timesheets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sgdb`.`timesheets` (
  `timesheetid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` INT UNSIGNED NOT NULL,
  `fromdate` DATE NOT NULL,
  `enddate` DATE NOT NULL,
  `totalhours` FLOAT NOT NULL,
  `filelocation` VARCHAR(255) NOT NULL,
  `ts` TIMESTAMP NOT NULL,
  INDEX `fk_TimeSheets_Users1_idx` (`userid` ASC),
  PRIMARY KEY (`timesheetid`),
  UNIQUE INDEX `timesheetid_UNIQUE` (`timesheetid` ASC),
  CONSTRAINT `fk_TimeSheets_Users1`
    FOREIGN KEY (`userid`)
    REFERENCES `sgdb`.`users` (`userid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
