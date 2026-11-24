-- =====================================================================
-- SOFTBYKE - SCRIPT DE BANCO DE DADOS MYSQL (PROJETO FINAL 2º BIMESTRE)
-- Disciplina: Desenvolvimento Web - Turma A - CEUB
-- Professor: Valdemir S Silva
-- Integrantes (Grupo 4):
--   1. Davi de Sousa Costa
--   2. Igor Christofidis de Lima
--   3. Pedro Carolino Barreto Sarmento
--   4. Vinícius Bernardes Carneiro
-- =====================================================================

CREATE DATABASE IF NOT EXISTS softbyke_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE softbyke_db;

-- ---------------------------------------------------------------------
-- 1. TABELA DE CLIENTES (CRUD de Clientes)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(120) NOT NULL,
    cidade VARCHAR(80) NOT NULL DEFAULT 'Brasília - DF',
    unidade_preferida ENUM('Matriz - Asa Norte', 'Filial - Águas Claras') NOT NULL DEFAULT 'Matriz - Asa Norte',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 2. TABELA DE PRODUTOS E SERVIÇOS (CRUD de Catálogo / Estoque)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria ENUM('Bicicletas', 'Peças', 'Acessórios', 'Manutenção') NOT NULL,
    modelo VARCHAR(120) NOT NULL,
    marca VARCHAR(60) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    estoque INT NOT NULL DEFAULT 0,
    status ENUM('Disponível', 'Sob encomenda', 'Esgotado') NOT NULL DEFAULT 'Disponível',
    descricao VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 3. TABELA DE AGENDAMENTOS DE OFICINA (CRUD de Ordens de Serviço)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    bicicleta_modelo VARCHAR(120) NOT NULL,
    servico VARCHAR(120) NOT NULL,
    unidade ENUM('Matriz - Asa Norte', 'Filial - Águas Claras') NOT NULL DEFAULT 'Matriz - Asa Norte',
    data_agendamento DATE NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    status ENUM('Agendado', 'Em Manutenção', 'Concluído', 'Cancelado') NOT NULL DEFAULT 'Agendado',
    observacoes TEXT DEFAULT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_agendamento_cliente
        FOREIGN KEY (cliente_id) REFERENCES clientes(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- DADOS INICIAIS (SEED) PARA DEMONSTRAÇÃO
-- ---------------------------------------------------------------------
INSERT IGNORE INTO clientes (id, nome, cpf, telefone, email, cidade, unidade_preferida) VALUES
(1, 'Carlos Eduardo Mendes', '123.456.789-01', '(61) 99812-3456', 'carlos.mendes@email.com', 'Brasília - Asa Norte', 'Matriz - Asa Norte'),
(2, 'Fernanda Oliveira Santos', '234.567.890-12', '(61) 99123-4567', 'fernanda.santos@email.com', 'Águas Claras - DF', 'Filial - Águas Claras'),
(3, 'Marcos Vinícius Ribeiro', '345.678.901-23', '(61) 98456-7890', 'marcos.ribeiro@email.com', 'Lago Norte - DF', 'Matriz - Asa Norte'),
(4, 'Juliana Costa Almeida', '456.789.012-34', '(61) 99345-6789', 'juliana.almeida@email.com', 'Taguatinga - DF', 'Filial - Águas Claras');

INSERT IGNORE INTO produtos (id, categoria, modelo, marca, preco, estoque, status, descricao) VALUES
(1, 'Bicicletas', 'SoftByke Urban 100', 'SoftByke', 1299.00, 8, 'Disponível', 'Bicicleta urbana aro 700, quadro em alumínio leve e 21 velocidades.'),
(2, 'Bicicletas', 'SoftByke Trail 200', 'SoftByke', 2199.00, 5, 'Disponível', 'Mountain Bike aro 29 com suspensão dianteira e freios a disco hidráulicos.'),
(3, 'Bicicletas', 'SoftByke Speed 300', 'SoftByke', 3499.00, 2, 'Sob encomenda', 'Bicicleta de estrada (Road/Speed) com grupo Shimano Claris 16v.'),
(4, 'Bicicletas', 'Caloi Explorer Comp', 'Caloi', 2890.00, 4, 'Disponível', 'MTB aro 29 ideal para trilhas moderadas e estradões de terra.'),
(5, 'Bicicletas', 'Oggi Big Wheel 7.3', 'Oggi', 5490.00, 3, 'Disponível', 'MTB performance equipada com transmissão Shimano Deore 12v.'),
(6, 'Peças', 'Corrente Indexada 8v/9v', 'KMC', 89.90, 25, 'Disponível', 'Corrente de alta durabilidade com powerlink incluso.'),
(7, 'Peças', 'Pneu MTB Aro 29 Kevlar', 'Pirelli', 149.90, 18, 'Disponível', 'Pneu de baixa resistência ao rolamento para asfalto e terra.'),
(8, 'Peças', 'Câmbio Traseiro Deore 12v', 'Shimano', 229.90, 6, 'Sob encomenda', 'Câmbio traseiro de precisão para cassetes de até 51 dentes.'),
(9, 'Peças', 'Pedivela Triplo Ty301', 'Shimano', 139.90, 12, 'Disponível', 'Pedivela em alumínio para relações de 21 e 24 marchas.'),
(10, 'Acessórios', 'Capacete Ciclismo Pro LED', 'Giro', 179.90, 15, 'Disponível', 'Capacete ventilado com ajuste traseiro e sinalizador LED.'),
(11, 'Acessórios', 'Farol Dianteiro Recarregável USB', 'Absolute', 59.90, 30, 'Disponível', 'Iluminação frontal de 800 lumens resistente à água.'),
(12, 'Manutenção', 'Revisão Completa Premium', 'Oficina SoftByke', 149.90, 99, 'Disponível', 'Desmontagem, limpeza química, lubrificação, alinhamento de rodas e regulagem.');

INSERT IGNORE INTO agendamentos (id, cliente_id, bicicleta_modelo, servico, unidade, data_agendamento, valor, status, observacoes) VALUES
(1, 1, 'Caloi Elite Carbon', 'Revisão Completa Premium', 'Matriz - Asa Norte', '2026-11-25', 149.90, 'Agendado', 'Verificar ruído no movimento central e sangria do freio traseiro.'),
(2, 2, 'Oggi Float 5.0', 'Regulagem de Câmbio e Freios', 'Filial - Águas Claras', '2026-11-26', 79.90, 'Em Manutenção', 'Troca de cabos e conduítes inclusa.'),
(3, 3, 'SoftByke Trail 200', 'Instalação de Cassete e Corrente', 'Matriz - Asa Norte', '2026-11-20', 110.00, 'Concluído', 'Peças adquiridas na própria loja com desconto de cliente cadastrado.');
