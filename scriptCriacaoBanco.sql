CREATE TABLE `projeto`.`usuarios` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `login` VARCHAR(255) NOT NULL ,
    `nome` VARCHAR(255) NOT NULL,
    `senha` VARCHAR(255) NOT NULL ,
    PRIMARY KEY (`id`),
    UNIQUE `UQ_usuarios_login` (`login`)
)