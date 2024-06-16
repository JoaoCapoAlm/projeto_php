CREATE TABLE `projeto`.`usuarios` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(255) NOT NULL,
    `nome` VARCHAR(255) NOT NULL,
    `cpf` VARCHAR(20) NOT NULL,
    `senha` VARCHAR(255) NOT NULL,
    `saldo` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `USD` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `EUR` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    PRIMARY KEY (`id`),
    UNIQUE `UQ_usuarios_login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Alteração da tabela caso não queiram criar uma nova:
ALTER TABLE `projeto`.`usuarios`
    ADD COLUMN `saldo` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    ADD COLUMN `USD` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    ADD COLUMN `EUR` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    ENGINE = InnoDB,
    CONVERT TO CHARACTER SET utf8;

