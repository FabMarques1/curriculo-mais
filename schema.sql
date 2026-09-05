CREATE DATABASE curriculo_mais_db
	DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_0900_ai_ci;
    
USE curriculo_mais_db;

CREATE TABLE tbl_usuario(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(40) NOT NULL,
    sobrenome VARCHAR(75),
    email VARCHAR(80) UNIQUE NOT NULL,
    senha CHAR(64) NOT NULL,
    cidade VARCHAR(30) NOT NULL DEFAULT 'Cruzeiro',
    
    PRIMARY KEY (id)
);

CREATE TABLE tbl_telefone(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    telefone CHAR(11) NOT NULL,
    id_usuario SMALLINT UNSIGNED NOT NULL,
    
    PRIMARY KEY (id),
    
    CONSTRAINT fk_usuario 
    FOREIGN KEY (id_usuario) 
    REFERENCES tbl_usuario (id)
    ON DELETE CASCADE
);

-- ALTERAÇÕES

CREATE TABLE IF NOT EXISTS tbl_cidade(
	id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    
    PRIMARY KEY (id)
);

INSERT INTO tbl_cidade (nome) VALUES
("Cruzeiro"),
("Queluz"),
("Lavrinhas"),
("Piquete"),
("Cachoeira Paulista"),
("Lorena");

ALTER TABLE tbl_usuario
MODIFY COLUMN cidade TINYINT UNSIGNED NOT NULL;

ALTER TABLE tbl_usuario
RENAME COLUMN cidade TO id_cidade;

ALTER TABLE tbl_usuario
ADD CONSTRAINT fk_cidade FOREIGN KEY (id_cidade) REFERENCES tbl_cidade (id);

CREATE TABLE IF NOT EXISTS tbl_curriculo(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    resumo_profissional VARCHAR(200),
    curriculo CHAR(31) NOT NULL,
    data_envio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_usuario SMALLINT UNSIGNED NOT NULL,
    
    PRIMARY KEY (id),
    
    CONSTRAINT fk_usuario_curriculo
    FOREIGN KEY (id_usuario)
    REFERENCES tbl_usuario (id)
    ON DELETE CASCADE
);
