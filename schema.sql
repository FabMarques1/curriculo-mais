-- NÃO UTILIZAR, EM DESENVOLVIMENTO!!
-- Fabricio.

CREATE DATABASE curriculo_mais_db
	DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_0900_ai_ci;
    
USE curriculo_mais_db;

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

CREATE TABLE IF NOT EXISTS tbl_usuario(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(40) NOT NULL,
    sobrenome VARCHAR(75),
    email VARCHAR(80) UNIQUE NOT NULL,
    senha CHAR(64) NOT NULL,
	id_cidade TINYINT UNSIGNED NOT NULL DEFAULT 1,
    
    PRIMARY KEY (id),
    
    CONSTRAINT fk_cidade
    FOREIGN KEY (id_cidade)
    REFERENCES tbl_cidade (id)
);

CREATE TABLE IF NOT EXISTS tbl_telefone(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    telefone CHAR(11) NOT NULL,
    id_usuario SMALLINT UNSIGNED NOT NULL,
    
    PRIMARY KEY (id),
    
    CONSTRAINT fk_usuario 
    FOREIGN KEY (id_usuario) 
    REFERENCES tbl_usuario (id)
    ON DELETE CASCADE
);

-- Mexer depois
CREATE TABLE IF NOT EXISTS tbl_vaga(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(75) NOT NULL,
    descricao TEXT NOT NULL,
    
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS tbl_curriculo(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    resumo_profissional VARCHAR(200),
    curriculo CHAR(31) NOT NULL,
    data_envio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_ativo BOOLEAN NOT NULL DEFAULT 1 CHECK (is_ativo BETWEEN 0 AND 1),
    id_usuario SMALLINT UNSIGNED NOT NULL,
    id_vaga SMALLINT UNSIGNED NOT NULL,
    
    PRIMARY KEY (id),
    
    CONSTRAINT fk_usuario_curriculo
    FOREIGN KEY (id_usuario)
    REFERENCES tbl_usuario (id)
    ON DELETE CASCADE,
    
    CONSTRAINT fk_vaga_curriculo
    FOREIGN KEY (id_vaga)
    REFERENCES tbl_vaga (id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS tbl_instituicao(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    cnpj CHAR(14) NOT NULL,
    id_cidade TINYINT UNSIGNED NOT NULL DEFAULT 1,
    
    PRIMARY KEY (id)
);
