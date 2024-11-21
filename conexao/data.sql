create database if not exists sgs;

use sgs;


-- Criação da tabela `tipo_sala`
CREATE TABLE if not exists tipo_sala (
    id_tipo_sala INT AUTO_INCREMENT PRIMARY KEY,
    nome_tipo_sala VARCHAR(255) NOT NULL
);

INSERT INTO tipo_sala (nome_tipo_sala) VALUES
('Reuniao'),
('Auditório'),
('Salão'),
('Laboratorio');



-- Criação da tabela `salas`
CREATE TABLE salas (
    id_sala INT AUTO_INCREMENT PRIMARY KEY,
    nome_sala VARCHAR(255) NOT NULL,
    tipo_sala INT NOT NULL,
    largura_sala INT NOT NULL,
    comprimento_sala INT NOT NULL,
    capacidade_sala INT NOT NULL,
    acessibilidade_sala_para_cadeirantes BOOLEAN DEFAULT FALSE,
    descricao_sala TEXT,
    localizacao_sala TEXT,
    valor_por_hora DECIMAL(10, 2) NOT NULL,
    valor_por_mes DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (tipo_sala) REFERENCES tipo_sala(id_tipo_sala)
);

-- Inserir itens na tabela `salas`
INSERT INTO salas (nome_sala, tipo_sala, largura_sala, comprimento_sala, capacidade_sala, acessibilidade_sala_para_cadeirantes, descricao_sala, localizacao_sala, valor_por_hora, valor_por_mes)
VALUES
('Sala de Reunião 1', 1, 3, 3, 10, TRUE, 'Ideal para reuniões corporativas', 'Prédio A, 2º Andar', 25.00, 900.00),
('Auditório', 2, 7, 9, 50, FALSE, 'Adequado para apresentações e palestras', 'Prédio B, Térreo', 25.00, 900.00),
('Espaço Coworking', 3, 5, 4, 20, TRUE, 'Ambiente para trabalhos colaborativos', 'Prédio C, 1º Andar', 20.00, 750.00),
('Sala de Treinamento', 4, 5, 5, 15, FALSE, 'Espaço para capacitações e workshops', 'Prédio A, 3º Andar', 30.00, 1000.00),
('Laboratório de Informática', 4, 6, 8, 25, TRUE, 'Laboratório equipado com computadores e projetor', 'Prédio A, Sala 4', 25.00, 900.00);



-- Criação da tabela `recursos_disponiveis`
CREATE TABLE if not exists recursos_disponiveis (
    id_recurso INT AUTO_INCREMENT PRIMARY KEY,
    nome_recurso VARCHAR(255) NOT NULL
);

INSERT INTO recursos_disponiveis (nome_recurso) VALUES
('Wi-Fi'),
('Projetor'),
('Computadores'),
('Microfone'),
('Sistema de Som'),
('Lousa');




-- Criação da tabela `sala_recurso` para o relacionamento n para n entre `salas` e `recursos_disponiveis`
CREATE TABLE if not exists sala_recurso (
    id_sala_recurso INT AUTO_INCREMENT PRIMARY KEY,
    id_sala INT,
    id_recurso INT,
    FOREIGN KEY (id_sala) REFERENCES salas(id_sala),
    FOREIGN KEY (id_recurso) REFERENCES recursos_disponiveis(id_recurso)
);

INSERT INTO sala_recurso (id_sala, id_recurso) VALUES
(1, 1),
(1, 2),
(1, 6),
(2, 4),
(2, 5),
(2, 1),
(3, 1),
(3, 3),
(3, 2),
(4, 3),
(4, 2),
(4, 6);



-- Criação da tabela `usuarios`
CREATE TABLE if not exists usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(255) NOT NULL,
    nick_usuario VARCHAR(255) UNIQUE NOT NULL,
    email_usuario VARCHAR(255) UNIQUE NOT NULL,
    senha_usuario VARCHAR(255) NOT NULL,
    telefone_usuario VARCHAR(20) NOT NULL,
    funcao_usuario ENUM('cliente', 'funcionario', 'adm') NOT NULL,
    codigo_recuperacao varchar(6) DEFAULT(NULL)
);

-- Inserção de usuários de exemplo na tabela `usuarios`
INSERT INTO usuarios (nome_usuario, nick_usuario, email_usuario, senha_usuario, telefone_usuario, funcao_usuario) VALUES
('Maria Oliveira', 'maria_oliveira', 'maria.oliveira@email.com', '$2y$10$9G2lIQIzWMxH3Kg45QIHn.rpjy/C/OF9KEb7C1/HndhZWyA9S8qhO', '68992516786', 'funcionario'),
('Carlos Santos', 'carlos.santos', 'carlos.santos@email.com', '$2y$10$b5Et8S5KTCqy1vVx1OAbsu9ivBL5nYDaSNrKr9sa0zpaYleITCtqK', '68999258134', 'funcionario'),
('Ana Souza Pinho', 'ana.souza', 'ana.souza@email.com', '$2y$10$EhQ89PbcPX8ts86BgDIcoO9i9ufT8SlVDgje3YuMoWVCY2LAac.Hm', '68992554237', 'cliente'),
('Paulo Lima', 'paulo.lima', 'paulo.lima@email.com', '$2y$10$fIFFMkFc/LBGYT7SPz6wtuOIXxYZijHxAc0xPHRQM3J47UJHFfIwm', '689992252856', 'funcionario'),
('Santiago Rocha de Melo', 'Russo007sr', 'santiagomelo121@gmail.com', '$2y$10$cWVX3tKdIy186AIcBk7uH.EzMx2PkQ6EyxCcCyEP1Qe7cULrrfN3e', '69992252857', 'adm'),
('asdfg', 'asdfsdg', 'ma@email.com', '$2y$10$rTTLWNUCC20atQobwidEKewU0BDP8csE8O4kis3EHTwprXfwtac5y', '69992252856', 'cliente'),
('cliente', 'cliente123', 'cliente@gmail.com', '$2y$10$uafxjIIaewcKkuEIu4B3aO0DN7KWwhqqIJBptV3DwVHSsT7uIQZ6q', '69992252856', 'cliente'),
('adm', 'adm123', 'adm@gmail.com', '$2y$10$LCq4t2OLR508Q0QQzaqX0uX70KMadDg4BCkR7SFIg99byOCVMY3qu', '69992252856', 'cliente'),
('Santiago Rocha de Melo', 'acb', 'santiago@gmail.com', '$2y$10$L7lENOfMMKSy1rDBztVx4ewLxwf6D5knGOtsLLnh1O4SlPsfbVnxu', '00000889565', 'cliente'),
('Arthur Rangel', 'cp180', 'arthurhangel@gmail.com', '$2y$10$4vpUyPITGqZPgr6xCDXpFO.XRLzuZgNzYvu/b7.KPQFfUwbe7752.', '68999931913', 'adm');



-- Criação da tabela `reservas`
CREATE TABLE IF NOT EXISTS reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_sala INT NOT NULL,
    data_hora_aluguel DATETIME NOT NULL,
    data_hora_entrega DATETIME NOT NULL,
    duracao ENUM('hora', 'mes') NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_sala) REFERENCES salas(id_sala) ON DELETE CASCADE
);

-- Inserção de dados de exemplo na tabela `reservas`
INSERT INTO reservas (id_usuario, id_sala, data_hora_aluguel, data_hora_entrega, duracao, valor) VALUES
(1, 1, '2024-10-11 09:00:00', '2024-10-11 11:00:00', 'hora', 100.00),
(2, 2, '2024-10-10 08:00:00', '2024-10-10 18:00:00', 'hora', 500.00),
(3, 3, '2024-10-11 14:00:00', '2024-10-14 14:00:00', 'mes', 1200.00);



CREATE TABLE IF NOT EXISTS imagens (
id_imagem INT NOT NULL auto_increment primary KEY,
nome_imagem varchar(255) not null
);

INSERT INTO imagens (nome_imagem) VALUES
('671675caf1750.png'),
('671675caf18a2.jpg'),
('67169da7564a0.png'),
('67169da7565a3.jpg'),
('67169da756670.jpg'),
('67169da7566ea.jpg'),
('67169da756756.jpg'),
('67169da7567d2.jpg'),
('671718f5c18c8.png'),
('67171fd4c3280.jpg'),
('67171fd4c33d5.png'),
('67171fd4c34a6.jpg'),
('67171fd4c3525.jpg'),
('671721e3834d9.jpg'),
('671721e38360c.jpg'),
('67282d2919bee.png'),
('67282d291a219.png'),
('67282d291a2df.jpg'),
('67282d291a38b.png');


create table IF NOT EXISTS imagens_salas (
id_sala INT NOT NULL,
id_imagem int not null,
FOREIGN KEY (id_sala) REFERENCES salas(id_sala) ON DELETE CASCADE,
foreign key (id_imagem) references imagens(id_imagem)
);

INSERT INTO imagens_salas VALUES
('5', '3'),
('5', '4'),
('5', '5'),
('5', '6'),
('5', '7'),
('5', '8'),
('4', '9'),
('3', '10'),
('3', '11'),
('3', '12'),
('3', '13'),
('2', '14'),
('2', '15'),
('1', '16'),
('1', '17'),
('1', '18'),
('1', '19');
