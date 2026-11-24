CREATE DATABASE IF NOT EXISTS softbyke_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE softbyke_db;

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

INSERT IGNORE INTO clientes (id, nome, cpf, telefone, email, cidade, unidade_preferida) VALUES
(1, 'Carlos Eduardo Mendes', '123.456.789-01', '(61) 99812-3456', 'carlos.mendes@email.com', 'Brasília - Asa Norte', 'Matriz - Asa Norte'),
(2, 'Fernanda Oliveira Santos', '234.567.890-12', '(61) 99123-4567', 'fernanda.santos@email.com', 'Águas Claras - DF', 'Filial - Águas Claras'),
(3, 'Marcos Vinícius Ribeiro', '345.678.901-23', '(61) 98456-7890', 'marcos.ribeiro@email.com', 'Lago Norte - DF', 'Matriz - Asa Norte'),
(4, 'Juliana Costa Almeida', '456.789.012-34', '(61) 99345-6789', 'juliana.almeida@email.com', 'Taguatinga - DF', 'Filial - Águas Claras');

INSERT IGNORE INTO produtos (id, categoria, modelo, marca, preco, estoque, status, descricao) VALUES
(1, 'Bicicletas', 'Caloi Velox', 'Caloi', 1199.00, 10, 'Disponível', 'Bicicleta aro 29 ideal para passeios e lazer urbano.'),
(2, 'Bicicletas', 'Caloi Elite Carbon', 'Caloi', 12990.00, 2, 'Disponível', 'Mountain Bike quadro em carbono para alta performance.'),
(3, 'Bicicletas', 'Caloi Elite Deore', 'Caloi', 6490.00, 4, 'Disponível', 'MTB aro 29 equipada com transmissão Shimano Deore.'),
(4, 'Bicicletas', 'Caloi Explorer', 'Caloi', 2890.00, 6, 'Disponível', 'MTB aro 29 versátil para trilhas e estradões.'),
(5, 'Bicicletas', 'Caloi Aluminum Pro', 'Caloi', 1890.00, 8, 'Disponível', 'Quadro em alumínio leve com 21 velocidades.'),
(6, 'Bicicletas', 'Caloi Commander', 'Caloi', 2490.00, 5, 'Disponível', 'Bicicleta urbana e trilha leve com freios a disco.'),
(7, 'Bicicletas', 'Caloi Montana', 'Caloi', 1390.00, 9, 'Disponível', 'Modelo resistente para deslocamento diário e lazer.'),
(8, 'Bicicletas', 'Caloi E-Vibe', 'Caloi', 7990.00, 3, 'Disponível', 'Bicicleta elétrica urbana com pedal assistido.'),
(9, 'Bicicletas', 'Caloi 100 Comfort', 'Caloi', 1590.00, 7, 'Disponível', 'Geometria ergonômica focada no conforto urbano.'),
(10, 'Bicicletas', 'Caloi Supra', 'Caloi', 2190.00, 6, 'Disponível', 'MTB aro 29 com suspensão dianteira e freio a disco.'),
(11, 'Bicicletas', 'Oggi Float 5.0', 'Oggi', 2690.00, 5, 'Disponível', 'Geometria feminina ergonômica aro 29 com 24 marchas.'),
(12, 'Bicicletas', 'Oggi Razzo T-130', 'Oggi', 9490.00, 2, 'Sob encomenda', 'Full suspension para trilhas técnicas e all-mountain.'),
(13, 'Bicicletas', 'Oggi Potenza Sport', 'Oggi', 14990.00, 2, 'Sob encomenda', 'E-MTB equipada com motor central e grupo Shimano Deore.'),
(14, 'Bicicletas', 'Oggi Big WHEEL 7.3', 'Oggi', 5490.00, 4, 'Disponível', 'Consagrada MTB performance com Shimano Deore 12v.'),
(15, 'Bicicletas', 'Oggi Float Sport', 'Oggi', 2290.00, 7, 'Disponível', 'Quadros com tubos hidroformados e ótimo custo-benefício.'),
(16, 'Bicicletas', 'Oggi Street Go', 'Oggi', 1990.00, 8, 'Disponível', 'Agilidade e leveza para mobilidade urbana diária.'),
(17, 'Bicicletas', 'Oggi Agile Pro XT', 'Oggi', 13590.00, 2, 'Sob encomenda', 'Quadro superleve em fibra de carbono com grupo Shimano XT.'),
(18, 'Bicicletas', 'Oggi Agile Sport', 'Oggi', 8990.00, 3, 'Disponível', 'MTB em carbono para competições de Cross-Country.'),
(19, 'Bicicletas', 'Oggi Agile Pro Sram S1000', 'Oggi', 15490.00, 1, 'Sob encomenda', 'MTB carbono de elite com transmissão eletrônica Sram.'),
(20, 'Bicicletas', 'Oggi Hacker Sport', 'Oggi', 2090.00, 11, 'Disponível', 'Porta de entrada para o MTB aro 29 com componentes Shimano.'),
(21, 'Peças', 'Pedivela Shimano Ty301', 'Shimano', 139.90, 15, 'Disponível', 'Pedivela triplo em alumínio para 21 e 24 velocidades.'),
(22, 'Peças', 'Cassete Shimano Deore 12v CS-M6100', 'Shimano', 459.90, 8, 'Disponível', 'Cassete 10-51 dentes com tecnologia Hyperglide+.'),
(23, 'Peças', 'Cambio Traseiro High One 10v 11/42d Shimano Sram', 'High One', 189.90, 12, 'Disponível', 'Câmbio traseiro de alta precisão compatível com 10v.'),
(24, 'Peças', 'Câmbio Traseiro Caloi Para Bicicleta 21/24 Marchas', 'Caloi', 79.90, 20, 'Disponível', 'Câmbio indexado para reposição em bicicletas de 7v e 8v.'),
(25, 'Peças', 'Câmbio Dianteiro Yamada Q25', 'Yamada', 49.90, 25, 'Disponível', 'Câmbio dianteiro puxada dupla para quadros MTB.'),
(26, 'Manutenção', 'Revisão Completa Premium', 'SoftByke', 149.90, 99, 'Disponível', 'Desmontagem completa, limpeza química, lubrificação e alinhamento.'),
(27, 'Manutenção', 'Regulagem de Câmbio e Freios', 'SoftByke', 69.90, 99, 'Disponível', 'Ajuste fino de precisão para marchas e sistema de frenagem.');

INSERT IGNORE INTO agendamentos (id, cliente_id, bicicleta_modelo, servico, unidade, data_agendamento, valor, status, observacoes) VALUES
(1, 1, 'Caloi Elite Carbon', 'Revisão Completa Premium', 'Matriz - Asa Norte', '2026-11-25', 149.90, 'Agendado', 'Verificar ruído no movimento central e sangria do freio traseiro.'),
(2, 2, 'Oggi Float 5.0', 'Regulagem de Câmbio e Freios', 'Filial - Águas Claras', '2026-11-26', 69.90, 'Em Manutenção', 'Troca de cabos e conduítes inclusa.'),
(3, 3, 'Oggi Big WHEEL 7.3', 'Instalação de Cassete e Corrente', 'Matriz - Asa Norte', '2026-11-20', 110.00, 'Concluído', 'Componentes adquiridos na própria loja.');
