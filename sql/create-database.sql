-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`users` (
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
-- Table `mydb`.`clients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`clients` (
  `clientid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `clientname` VARCHAR(255) NOT NULL,
  `clientabbreviation` VARCHAR(255) NOT NULL,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `employeeid` VARCHAR(255) NOT NULL,
  `startdate` DATE NOT NULL,
  `resetpassword` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`clientid`),
  UNIQUE INDEX `userName_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `employeeID_UNIQUE` (`employeeid` ASC),
  UNIQUE INDEX `clientid_UNIQUE` (`clientid` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`users_clients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`users_clients` (
  `userid` INT UNSIGNED NOT NULL,
  `clientid` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`userid`, `clientid`),
  INDEX `fk_Users_has_Clients_Clients1_idx` (`clientid` ASC),
  INDEX `fk_Users_has_Clients_Users_idx` (`userid` ASC),
  CONSTRAINT `fk_Users_has_Clients_Users`
    FOREIGN KEY (`userid`)
    REFERENCES `mydb`.`users` (`userid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Users_has_Clients_Clients1`
    FOREIGN KEY (`clientid`)
    REFERENCES `mydb`.`clients` (`clientid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`timesheets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`timesheets` (
  `userid` INT UNSIGNED NOT NULL,
  `fromdate` DATE NOT NULL,
  `enddate` DATE NOT NULL,
  `totalhours` FLOAT NOT NULL,
  `filelocation` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`userid`),
  INDEX `fk_TimeSheets_Users1_idx` (`userid` ASC),
  CONSTRAINT `fk_TimeSheets_Users1`
    FOREIGN KEY (`userid`)
    REFERENCES `mydb`.`users` (`userid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`timesheets_clients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`timesheets_clients` (
  `userid` INT UNSIGNED NOT NULL,
  `clientid` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`userid`, `clientid`),
  INDEX `fk_TimeSheets_has_Clients_Clients1_idx` (`clientid` ASC),
  INDEX `fk_TimeSheets_has_Clients_TimeSheets1_idx` (`userid` ASC),
  CONSTRAINT `fk_TimeSheets_has_Clients_TimeSheets1`
    FOREIGN KEY (`userid`)
    REFERENCES `mydb`.`timesheets` (`userid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_TimeSheets_has_Clients_Clients1`
    FOREIGN KEY (`clientid`)
    REFERENCES `mydb`.`clients` (`clientid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

