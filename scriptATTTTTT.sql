CREATE TABLE Posto(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cnpj CHAR(255) NOT NULL,
    horario_abertura VARCHAR(255) NOT NULL,
    horario_fechamento VARCHAR(255) NOT NULL,
    id_endereco BIGINT UNSIGNED NOT NULL
);
ALTER TABLE
    Posto ADD UNIQUE posto_cnpj_unique(cnpj);
CREATE TABLE Endereco(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    bairro VARCHAR(255) NOT NULL,
    rua VARCHAR(255) NOT NULL,
    numero INT NOT NULL
);
CREATE TABLE Vacina(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    data_aplicacao DATETIME NOT NULL,
    id_posto BIGINT UNSIGNED NOT NULL,
    idade_recomendada VARCHAR(255) NOT NULL,
    url_imagem VARCHAR(255) NOT NULL
);
CREATE TABLE Administrador(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(255) NOT NULL,
    telefone VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    admin_prefeitura BOOLEAN NOT NULL,
    id_posto BIGINT UNSIGNED NULL
);
ALTER TABLE
    Administrador ADD UNIQUE administrador_email_unique(email);
ALTER TABLE
    Administrador ADD UNIQUE administrador_cpf_unique(cpf);
ALTER TABLE
    Administrador ADD CONSTRAINT administrador_id_posto_foreign FOREIGN KEY(id_posto) REFERENCES Posto(id);
ALTER TABLE
    Vacina ADD CONSTRAINT vacina_id_posto_foreign FOREIGN KEY(id_posto) REFERENCES Posto(id);
ALTER TABLE
    Posto ADD CONSTRAINT posto_id_endereco_foreign FOREIGN KEY(id_endereco) REFERENCES Endereco(id);
