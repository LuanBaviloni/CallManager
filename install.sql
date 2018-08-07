-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema callmanager
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `callmanager` ;

-- -----------------------------------------------------
-- Schema callmanager
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `callmanager` DEFAULT CHARACTER SET utf8 ;
USE `callmanager` ;

-- -----------------------------------------------------
-- Table `callmanager`.`configs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `callmanager`.`configs` (
  `config_nome` VARCHAR(100) NOT NULL,
  `config_value` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`config_nome`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `callmanager`.`groups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `callmanager`.`groups` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `description` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `callmanager`.`logins`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `callmanager`.`logins` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `username` VARCHAR(100) NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `salt` VARCHAR(255) NULL DEFAULT NULL,
  `email` VARCHAR(254) NOT NULL,
  `activation_code` VARCHAR(40) NULL DEFAULT NULL,
  `forgotten_password_code` VARCHAR(40) NULL DEFAULT NULL,
  `forgotten_password_time` INT(11) UNSIGNED NULL DEFAULT NULL,
  `remember_code` VARCHAR(40) NULL DEFAULT NULL,
  `created_on` INT(11) UNSIGNED NOT NULL,
  `last_login` INT(11) UNSIGNED NULL DEFAULT NULL,
  `active` TINYINT(1) UNSIGNED NULL DEFAULT NULL,
  `first_name` VARCHAR(50) NULL DEFAULT NULL,
  `last_name` VARCHAR(50) NULL DEFAULT NULL,
  `company` VARCHAR(100) NULL DEFAULT NULL,
  `phone` VARCHAR(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `callmanager`.`listas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `callmanager`.`listas` (
  `lista_id` INT(11) NOT NULL AUTO_INCREMENT,
  `lista_nome` VARCHAR(100) NOT NULL,
  `lista_arquivo` VARCHAR(255) NULL DEFAULT NULL,
  `login_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`lista_id`),
  INDEX `fk_listas_users1_idx` (`login_id` ASC),
  CONSTRAINT `fk_listas_users1`
    FOREIGN KEY (`login_id`)
    REFERENCES `callmanager`.`logins` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `callmanager`.`login_attempts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `callmanager`.`login_attempts` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `login` VARCHAR(100) NOT NULL,
  `time` INT(11) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `callmanager`.`status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `callmanager`.`status` (
  `status_id` INT(11) NOT NULL AUTO_INCREMENT,
  `status_nome` VARCHAR(45) NULL DEFAULT NULL,
  `status_cor` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`status_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `callmanager`.`pessoas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `callmanager`.`pessoas` (
  `pessoa_id` INT(11) NOT NULL AUTO_INCREMENT,
  `pessoa_nome` VARCHAR(200) NOT NULL,
  `pessoa_responsavel` VARCHAR(100) NULL DEFAULT NULL,
  `pessoa_telmain` VARCHAR(45) NOT NULL,
  `pessoa_telsec` VARCHAR(45) NULL DEFAULT NULL,
  `pessoa_cel` VARCHAR(45) NULL DEFAULT NULL,
  `pessoa_agencia` VARCHAR(10) NULL DEFAULT NULL,
  `pessoa_conta` VARCHAR(20) NULL DEFAULT NULL,
  `pessoa_cnpj` VARCHAR(45) NULL DEFAULT NULL,
  `pessoa_alerta` DATETIME NULL DEFAULT NULL,
  `pessoa_detalhes` TEXT NULL DEFAULT NULL,
  `lista_id` INT(11) NOT NULL,
  `status_id` INT(11) NOT NULL,
  PRIMARY KEY (`pessoa_id`),
  INDEX `fk_pessoas_listas1_idx` (`lista_id` ASC),
  INDEX `fk_pessoas_status1_idx` (`status_id` ASC),
  CONSTRAINT `fk_pessoas_listas1`
    FOREIGN KEY (`lista_id`)
    REFERENCES `callmanager`.`listas` (`lista_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pessoas_status1`
    FOREIGN KEY (`status_id`)
    REFERENCES `callmanager`.`status` (`status_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `callmanager`.`users_groups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `callmanager`.`users_groups` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `group_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uc_users_groups` (`user_id` ASC, `group_id` ASC),
  INDEX `fk_users_groups_users1_idx` (`user_id` ASC),
  INDEX `fk_users_groups_groups1_idx` (`group_id` ASC),
  CONSTRAINT `fk_users_groups_groups1`
    FOREIGN KEY (`group_id`)
    REFERENCES `callmanager`.`groups` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `callmanager`.`logins` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `callmanager`.`groups`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `callmanager`.`groups` (`id`, `name`, `description`) VALUES (1, 'admin', 'Administrador');
INSERT INTO `callmanager`.`groups` (`id`, `name`, `description`) VALUES (2, 'user', 'User');

COMMIT;

-- -----------------------------------------------------
-- Data for table `callmanager`.`logins`
-- -----------------------------------------------------

START TRANSACTION;
INSERT INTO `callmanager`.`logins` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
     ('1','127.0.0.1','administrator','$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36','','admin@admin.com','',NULL,'1268889823','1268889823','1', 'Admin','istrator','ADMIN','0');


COMMIT;


-- -----------------------------------------------------
-- Data for table `callmanager`.`status`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `callmanager`.`status` (`status_id`, `status_nome`, `status_cor`) VALUES (1, '-', NULL);
INSERT INTO `callmanager`.`status` (`status_id`, `status_nome`, `status_cor`) VALUES (2, 'Retornar', 'warning');
INSERT INTO `callmanager`.`status` (`status_id`, `status_nome`, `status_cor`) VALUES (3, 'Finalizado', 'success');
INSERT INTO `callmanager`.`status` (`status_id`, `status_nome`, `status_cor`) VALUES (4, 'Sem sucesso', 'danger');

COMMIT;


-- -----------------------------------------------------
-- Data for table `callmanager`.`users_groups`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `callmanager`.`users_groups` (`id`, `user_id`, `group_id`) VALUES (1, 1, 1);
INSERT INTO `callmanager`.`users_groups` (`id`, `user_id`, `group_id`) VALUES (2, 1, 2);
COMMIT;

