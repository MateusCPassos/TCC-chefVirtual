aqui esta o comando de criação do banco de dados de dadoa arrume com base nele
-- -----------------------------------------------------
-- Schema chefVirtual
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `chefVirtual` DEFAULT CHARACTER SET utf8 ;
USE `chefVirtual` ;

-- -----------------------------------------------------
-- Table `chefVirtual`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `chefVirtual`.`usuario` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `nome` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `senha` VARCHAR(45) NOT NULL,
  `fotoUsuariocol` VARCHAR(50) NULL,
  `tipoUsuario` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `chefVirtual`.`categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `chefVirtual`.`categoria` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `nomeCategoria` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `chefVirtual`.`prato`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `chefVirtual`.`prato` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `nome` VARCHAR(120) NOT NULL,
  `descricao` VARCHAR(150) NOT NULL,
  `custo` FLOAT NOT NULL,
  `tempoPreparo` VARCHAR(45) NOT NULL,
  `observacoes` VARCHAR(150) NULL,
  `foto` VARCHAR(50) NULL,
  `categoria_id` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_prato_usuario1_idx` (`usuario_id` ASC),
  INDEX `fk_prato_categoria1_idx` (`categoria_id` ASC),
  CONSTRAINT `fk_prato_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `chefVirtual`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prato_categoria1`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `chefVirtual`.`categoria` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `chefVirtual`.`materiais`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `chefVirtual`.`materiais` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `nomeMaterial` VARCHAR(300) NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `chefVirtual`.`favoritos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `chefVirtual`.`favoritos` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `usuario_id` INT NOT NULL,
  `prato_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_favoritos_usuario1_idx` (`usuario_id` ASC),
  INDEX `fk_favoritos_prato1_idx` (`prato_id` ASC),
  CONSTRAINT `fk_favoritos_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `chefVirtual`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_favoritos_prato1`
    FOREIGN KEY (`prato_id`)
    REFERENCES `chefVirtual`.`prato` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `chefVirtual`.`avaliacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `chefVirtual`.`avaliacao` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `avaliacaoDoPrato` VARCHAR(200) NOT NULL,
  `data` DATE NOT NULL,
  `usuario_id` INT NOT NULL,
  `prato_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_avaliacao_usuario1_idx` (`usuario_id` ASC),
  INDEX `fk_avaliacao_prato1_idx` (`prato_id` ASC),
  CONSTRAINT `fk_avaliacao_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `chefVirtual`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_avaliacao_prato1`
    FOREIGN KEY (`prato_id`)
    REFERENCES `chefVirtual`.`prato` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `chefVirtual`.`indredientes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `chefVirtual`.`indredientes` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `NomeIndrediente` VARCHAR(300) NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `chefVirtual`.`log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `chefVirtual`.`log` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `data` DATETIME NOT NULL,
  `prato_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_log_prato1_idx` (`prato_id` ASC),
  CONSTRAINT `fk_log_prato1`
    FOREIGN KEY (`prato_id`)
    REFERENCES `chefVirtual`.`prato` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `chefVirtual`.`materiais_has_prato`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `chefVirtual`.`materiais_has_prato` (
  `materiais_id` INT NOT NULL,
  `prato_id` INT NOT NULL,
  PRIMARY KEY (`materiais_id`, `prato_id`),
  INDEX `fk_materiais_has_prato_prato1_idx` (`prato_id` ASC),
  INDEX `fk_materiais_has_prato_materiais1_idx` (`materiais_id` ASC),
  CONSTRAINT `fk_materiais_has_prato_materiais1`
    FOREIGN KEY (`materiais_id`)
    REFERENCES `chefVirtual`.`materiais` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_materiais_has_prato_prato1`
    FOREIGN KEY (`prato_id`)
    REFERENCES `chefVirtual`.`prato` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `chefVirtual`.`prato_has_indredientes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `chefVirtual`.`prato_has_indredientes` (
  `prato_id` INT NOT NULL,
  `indredientes_id` INT NOT NULL,
  `quantidade` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`prato_id`, `indredientes_id`),
  INDEX `fk_prato_has_indredientes_indredientes1_idx` (`indredientes_id` ASC),
  INDEX `fk_prato_has_indredientes_prato1_idx` (`prato_id` ASC),
  CONSTRAINT `fk_prato_has_indredientes_prato1`
    FOREIGN KEY (`prato_id`)
    REFERENCES `chefVirtual`.`prato` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prato_has_indredientes_indredientes1`
    FOREIGN KEY (`indredientes_id`)
    REFERENCES `chefVirtual`.`indredientes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;