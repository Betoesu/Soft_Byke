<?php
/**
 * =====================================================================
 * SOFTBYKE - SISTEMA COMERCIAL (CRUD COMPLETO EM PHP E MYSQL)
 * Disciplina: Desenvolvimento Web - Turma A - CEUB
 * Professor: Valdemir S Silva
 * Integrantes (Grupo 4):
 *   Davi de Sousa Costa | Igor Christofidis de Lima |
 *   Pedro Carolino Barreto Sarmento | Vinícius Bernardes Carneiro
 * =====================================================================
 * Implementa as 4 operações fundamentais do banco de dados (CRUD):
 *   - CREATE (INSERT INTO): Cadastro de Clientes, Produtos e Agendamentos
 *   - READ   (SELECT):      Listagem, filtros de pesquisa e indicadores
 *   - UPDATE (UPDATE):      Edição de registros existentes por ID
 *   - DELETE (DELETE FROM): Exclusão de registros por ID
 */

require_once __DIR__ . '/php/conexao.php';

$mensagemSucesso = '';
$mensagemErro    = '';
$abaAtiva        = isset($_GET['aba']) ? $_GET['aba'] : 'agendamentos';

// =====================================================================
// PROCESSAMENTO DAS OPERAÇÕES CRUD (QUANDO CONECTADO AO MYSQL)
// =====================================================================
if ($pdo !== null) {

    // -----------------------------------------------------------------
    // 1. MÓDULO CRUD DE CLIENTES (CREATE / UPDATE / DELETE)
    // -----------------------------------------------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao_cliente'])) {
        $abaAtiva  = 'clientes';
        $id        = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $nome      = trim($_POST['nome'] ?? '');
        $cpf       = trim($_POST['cpf'] ?? '');
        $telefone  = trim($_POST['telefone'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $cidade    = trim($_POST['cidade'] ?? 'Brasília - DF');
        $unidade   = $_POST['unidade_preferida'] ?? 'Matriz - Asa Norte';

        if ($nome === '' || $cpf === '' || $telefone === '' || $email === '') {
            $mensagemErro = 'Preencha todos os campos obrigatórios do cliente.';
        } else {
            try {
                if ($_POST['acao_cliente'] === 'cadastrar') {
                    $sql = "INSERT INTO clientes (nome, cpf, telefone, email, cidade, unidade_preferida)
                            VALUES (:nome, :cpf, :telefone, :email, :cidade, :unidade)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':nome'     => $nome,
                        ':cpf'      => $cpf,
                        ':telefone' => $telefone,
                        ':email'    => $email,
                        ':cidade'   => $cidade,
                        ':unidade'  => $unidade
                    ]);
                    $mensagemSucesso = "Cliente \"{$nome}\" cadastrado com sucesso no banco de dados (CREATE)!";
                } elseif ($_POST['acao_cliente'] === 'atualizar' && $id > 0) {
                    $sql = "UPDATE clientes
                            SET nome = :nome, cpf = :cpf, telefone = :telefone,
                                email = :email, cidade = :cidade, unidade_preferida = :unidade
                            WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':nome'     => $nome,
                        ':cpf'      => $cpf,
                        ':telefone' => $telefone,
                        ':email'    => $email,
                        ':cidade'   => $cidade,
                        ':unidade'  => $unidade,
                        ':id'       => $id
                    ]);
                    $mensagemSucesso = "Dados do cliente #{$id} atualizados com sucesso (UPDATE)!";
                }
            } catch (PDOException $e) {
                $mensagemErro = 'Erro ao salvar cliente (verifique se o CPF já está cadastrado): ' . $e->getMessage();
            }
        }
    }

    if (isset($_GET['excluir_cliente'])) {
        $abaAtiva = 'clientes';
        $idExcluir = (int) $_GET['excluir_cliente'];
        try {
            $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
            $stmt->execute([':id' => $idExcluir]);
            $mensagemSucesso = "Cliente #{$idExcluir} removido com sucesso do banco de dados (DELETE)!";
        } catch (PDOException $e) {
            $mensagemErro = 'Erro ao excluir cliente: ' . $e->getMessage();
        }
    }

    // -----------------------------------------------------------------
    // 2. MÓDULO CRUD DE PRODUTOS E ESTOQUE (CREATE / UPDATE / DELETE)
    // -----------------------------------------------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao_produto'])) {
        $abaAtiva  = 'produtos';
        $id        = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $categoria = $_POST['categoria'] ?? 'Bicicletas';
        $modelo    = trim($_POST['modelo'] ?? '');
        $marca     = trim($_POST['marca'] ?? '');
        $preco     = (float) str_replace(',', '.', $_POST['preco'] ?? '0');
        $estoque   = (int) ($_POST['estoque'] ?? 0);
        $status    = $_POST['status'] ?? 'Disponível';
        $descricao = trim($_POST['descricao'] ?? '');

        if ($modelo === '' || $marca === '' || $preco <= 0) {
            $mensagemErro = 'Informe o modelo, a marca e um preço válido maior que zero.';
        } else {
            try {
                if ($_POST['acao_produto'] === 'cadastrar') {
                    $sql = "INSERT INTO produtos (categoria, modelo, marca, preco, estoque, status, descricao)
                            VALUES (:categoria, :modelo, :marca, :preco, :estoque, :status, :descricao)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':categoria' => $categoria,
                        ':modelo'    => $modelo,
                        ':marca'     => $marca,
                        ':preco'     => $preco,
                        ':estoque'   => $estoque,
                        ':status'    => $status,
                        ':descricao' => $descricao
                    ]);
                    $mensagemSucesso = "Produto \"{$modelo}\" cadastrado no catálogo com sucesso (CREATE)!";
                } elseif ($_POST['acao_produto'] === 'atualizar' && $id > 0) {
                    $sql = "UPDATE produtos
                            SET categoria = :categoria, modelo = :modelo, marca = :marca,
                                preco = :preco, estoque = :estoque, status = :status, descricao = :descricao
                            WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':categoria' => $categoria,
                        ':modelo'    => $modelo,
                        ':marca'     => $marca,
                        ':preco'     => $preco,
                        ':estoque'   => $estoque,
                        ':status'    => $status,
                        ':descricao' => $descricao,
                        ':id'       => $id
                    ]);
                    $mensagemSucesso = "Produto #{$id} atualizado com sucesso no estoque (UPDATE)!";
                }
            } catch (PDOException $e) {
                $mensagemErro = 'Erro ao salvar produto: ' . $e->getMessage();
            }
        }
    }

    if (isset($_GET['excluir_produto'])) {
        $abaAtiva = 'produtos';
        $idExcluir = (int) $_GET['excluir_produto'];
        try {
            $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
            $stmt->execute([':id' => $idExcluir]);
            $mensagemSucesso = "Produto #{$idExcluir} excluído do catálogo com sucesso (DELETE)!";
        } catch (PDOException $e) {
            $mensagemErro = 'Erro ao excluir produto: ' . $e->getMessage();
        }
    }

    // -----------------------------------------------------------------
    // 3. MÓDULO CRUD DE AGENDAMENTOS DE OFICINA (CREATE / UPDATE / DELETE)
    // -----------------------------------------------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao_agendamento'])) {
        $abaAtiva        = 'agendamentos';
        $id              = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $clienteId       = (int) ($_POST['cliente_id'] ?? 0);
        $bicicletaModelo = trim($_POST['bicicleta_modelo'] ?? '');
        $servico         = trim($_POST['servico'] ?? '');
        $unidade         = $_POST['unidade'] ?? 'Matriz - Asa Norte';
        $dataAgendamento = $_POST['data_agendamento'] ?? date('Y-m-d');
        $valor           = (float) str_replace(',', '.', $_POST['valor'] ?? '0');
        $status          = $_POST['status'] ?? 'Agendado';
        $observacoes     = trim($_POST['observacoes'] ?? '');

        if ($clienteId <= 0 || $bicicletaModelo === '' || $servico === '' || $valor <= 0) {
            $mensagemErro = 'Selecione um cliente, informe a bicicleta, o serviço e um valor válido.';
        } else {
            try {
                if ($_POST['acao_agendamento'] === 'cadastrar') {
                    $sql = "INSERT INTO agendamentos (cliente_id, bicicleta_modelo, servico, unidade, data_agendamento, valor, status, observacoes)
                            VALUES (:cliente_id, :bicicleta_modelo, :servico, :unidade, :data_agendamento, :valor, :status, :observacoes)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':cliente_id'       => $clienteId,
                        ':bicicleta_modelo' => $bicicletaModelo,
                        ':servico'          => $servico,
                        ':unidade'          => $unidade,
                        ':data_agendamento' => $dataAgendamento,
                        ':valor'            => $valor,
                        ':status'           => $status,
                        ':observacoes'      => $observacoes
                    ]);
                    $mensagemSucesso = "Ordem de Serviço / Agendamento registrado com sucesso na oficina (CREATE)!";
                } elseif ($_POST['acao_agendamento'] === 'atualizar' && $id > 0) {
                    $sql = "UPDATE agendamentos
                            SET cliente_id = :cliente_id, bicicleta_modelo = :bicicleta_modelo,
                                servico = :servico, unidade = :unidade, data_agendamento = :data_agendamento,
                                valor = :valor, status = :status, observacoes = :observacoes
                            WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':cliente_id'       => $clienteId,
                        ':bicicleta_modelo' => $bicicletaModelo,
                        ':servico'          => $servico,
                        ':unidade'          => $unidade,
                        ':data_agendamento' => $dataAgendamento,
                        ':valor'            => $valor,
                        ':status'           => $status,
                        ':observacoes'      => $observacoes,
                        ':id'               => $id
                    ]);
                    $mensagemSucesso = "Agendamento #{$id} atualizado com sucesso (UPDATE)!";
                }
            } catch (PDOException $e) {
                $mensagemErro = 'Erro ao salvar agendamento: ' . $e->getMessage();
            }
        }
    }

    if (isset($_GET['excluir_agendamento'])) {
        $abaAtiva = 'agendamentos';
        $idExcluir = (int) $_GET['excluir_agendamento'];
        try {
            $stmt = $pdo->prepare("DELETE FROM agendamentos WHERE id = :id");
            $stmt->execute([':id' => $idExcluir]);
            $mensagemSucesso = "Agendamento #{$idExcluir} removido com sucesso (DELETE)!";
        } catch (PDOException $e) {
            $mensagemErro = 'Erro ao excluir agendamento: ' . $e->getMessage();
        }
    }
}

// =====================================================================
// CONSULTAS DE LEITURA (READ) E BUSCA DE REGISTROS PARA EDIÇÃO
// =====================================================================
$listaClientes     = [];
$listaProdutos     = [];
$listaAgendamentos = [];

$clienteEdicao     = null;
$produtoEdicao     = null;
$agendamentoEdicao = null;

$buscaCliente      = trim($_GET['busca_cliente'] ?? '');
$filtroCategoria   = trim($_GET['filtro_categoria'] ?? '');
$filtroStatusOs    = trim($_GET['filtro_status_os'] ?? '');

// Pré-preenchimento caso o usuário venha do Simulador de Orçamento (JS)
$servicoPre        = trim($_GET['servico_orcamento'] ?? '');
$valorPre          = trim($_GET['valor_orcamento'] ?? '');
$obsPre            = trim($_GET['obs_orcamento'] ?? '');

if ($pdo !== null) {
    // Registro em edição: Cliente
    if (isset($_GET['editar_cliente'])) {
        $abaAtiva = 'clientes';
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
        $stmt->execute([':id' => (int) $_GET['editar_cliente']]);
        $clienteEdicao = $stmt->fetch();
    }

    // Registro em edição: Produto
    if (isset($_GET['editar_produto'])) {
        $abaAtiva = 'produtos';
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->execute([':id' => (int) $_GET['editar_produto']]);
        $produtoEdicao = $stmt->fetch();
    }

    // Registro em edição: Agendamento
    if (isset($_GET['editar_agendamento'])) {
        $abaAtiva = 'agendamentos';
        $stmt = $pdo->prepare("SELECT * FROM agendamentos WHERE id = :id");
        $stmt->execute([':id' => (int) $_GET['editar_agendamento']]);
        $agendamentoEdicao = $stmt->fetch();
    }

    // READ: Lista de Clientes
    if ($buscaCliente !== '') {
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE nome LIKE :b OR cpf LIKE :b OR email LIKE :b ORDER BY id DESC");
        $stmt->execute([':b' => '%' . $buscaCliente . '%']);
        $listaClientes = $stmt->fetchAll();
    } else {
        $listaClientes = $pdo->query("SELECT * FROM clientes ORDER BY id DESC")->fetchAll();
    }

    // READ: Lista de Produtos
    if ($filtroCategoria !== '') {
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE categoria = :cat ORDER BY categoria, modelo");
        $stmt->execute([':cat' => $filtroCategoria]);
        $listaProdutos = $stmt->fetchAll();
    } else {
        $listaProdutos = $pdo->query("SELECT * FROM produtos ORDER BY categoria, modelo")->fetchAll();
    }

    // READ: Lista de Agendamentos com JOIN na tabela de Clientes
    $sqlAg = "SELECT a.*, c.nome AS cliente_nome, c.telefone AS cliente_telefone
              FROM agendamentos a
              INNER JOIN clientes c ON a.cliente_id = c.id";
    if ($filtroStatusOs !== '') {
        $sqlAg .= " WHERE a.status = :status ORDER BY a.data_agendamento ASC, a.id DESC";
        $stmt = $pdo->prepare($sqlAg);
        $stmt->execute([':status' => $filtroStatusOs]);
        $listaAgendamentos = $stmt->fetchAll();
    } else {
        $sqlAg .= " ORDER BY a.data_agendamento ASC, a.id DESC";
        $listaAgendamentos = $pdo->query($sqlAg)->fetchAll();
    }
}

// Cálculo de indicadores gerais
$totalClientes     = count($listaClientes);
$totalProdutos     = count($listaProdutos);
$totalAgendamentos = count($listaAgendamentos);
$valorEstoqueTotal = 0.0;
foreach ($listaProdutos as $p) {
    if ($p['categoria'] !== 'Manutenção') {
        $valorEstoqueTotal += ((float) $p['preco']) * ((int) $p['estoque']);
    }
}
$receitaOficina = 0.0;
foreach ($listaAgendamentos as $ag) {
    if ($ag['status'] !== 'Cancelado') {
        $receitaOficina += (float) $ag['valor'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoftByke - Sistema de Gestão Comercial (CRUD PHP &amp; MySQL)</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body class="pg-sistema" data-pagina="sistema">

    <header class="cabecalho">
        <a href="index.html" class="logo">Soft<span>Byke</span></a>
        <nav class="menu-principal" id="menuPrincipal">
            <ul>
                <li><a href="index.html" data-pagina="inicio">Início</a></li>
                <li><a href="empresa.html" data-pagina="empresa">Empresa</a></li>
                <li><a href="galeria.html" data-pagina="galeria">Galeria</a></li>
                <li><a href="tabela.html" data-pagina="tabela">Catálogo</a></li>
                <li><a href="orcamento.html" data-pagina="orcamento">Orçamento (JS)</a></li>
                <li><a href="sistema.php" class="ativo" data-pagina="sistema">Sistema (PHP)</a></li>
                <li><a href="documentos.html" data-pagina="documentos">Documentos</a></li>
            </ul>
        </nav>
    </header>

    <section class="faixa-titulo">
        <h1>Sistema de Gestão Comercial SoftByke (CRUD PHP &amp; MySQL)</h1>
        <p>Gerenciamento integrado de Ordens de Serviço da Oficina, Catálogo de Produtos/Estoque e Clientes no banco de dados <code>softbyke_db</code>.</p>
    </section>

    <main>
        <?php if ($erroConexao !== null): ?>
            <div class="alerta alerta-erro">
                <strong>Atenção (Banco de Dados MySQL):</strong> <?php echo htmlspecialchars($erroConexao); ?>
                <br><small>Para testar o CRUD completo, inicie os módulos <b>Apache</b> e <b>MySQL</b> no painel do XAMPP e acesse <code>http://localhost/SoftByke-Desenvolvimento-Web/sistema.php</code>. O script SQL também está disponível em <a href="sql/softbyke_db.sql">sql/softbyke_db.sql</a>.</small>
            </div>
        <?php endif; ?>

        <?php if ($mensagemSucesso !== ''): ?>
            <div class="alerta alerta-sucesso">
                <strong>Sucesso:</strong> <?php echo htmlspecialchars($mensagemSucesso); ?>
            </div>
        <?php endif; ?>

        <?php if ($mensagemErro !== ''): ?>
            <div class="alerta alerta-erro">
                <strong>Erro:</strong> <?php echo htmlspecialchars($mensagemErro); ?>
            </div>
        <?php endif; ?>

        <!-- INDICADORES DO BANCO DE DADOS -->
        <div class="painel-indicadores">
            <div class="card-indicador">
                <span class="rotulo-indicador">Ordens de Serviço (Oficina)</span>
                <strong class="valor-indicador"><?php echo $totalAgendamentos; ?> registros</strong>
                <small>Receita prevista: R$ <?php echo number_format($receitaOficina, 2, ',', '.'); ?></small>
            </div>
            <div class="card-indicador">
                <span class="rotulo-indicador">Produtos e Serviços no Catálogo</span>
                <strong class="valor-indicador"><?php echo $totalProdutos; ?> itens</strong>
                <small>Patrimônio em estoque: R$ <?php echo number_format($valorEstoqueTotal, 2, ',', '.'); ?></small>
            </div>
            <div class="card-indicador">
                <span class="rotulo-indicador">Clientes Cadastrados</span>
                <strong class="valor-indicador"><?php echo $totalClientes; ?> clientes</strong>
                <small>Atendimento Matriz e Filial</small>
            </div>
        </div>

        <!-- NAVEGAÇÃO ENTRE OS 3 MÓDULOS CRUD -->
        <div class="abas-crud">
            <a href="sistema.php?aba=agendamentos" class="aba-btn <?php echo ($abaAtiva === 'agendamentos') ? 'ativa' : ''; ?>">
                1. CRUD Oficina / Agendamentos (<?php echo $totalAgendamentos; ?>)
            </a>
            <a href="sistema.php?aba=produtos" class="aba-btn <?php echo ($abaAtiva === 'produtos') ? 'ativa' : ''; ?>">
                2. CRUD Catálogo e Estoque (<?php echo $totalProdutos; ?>)
            </a>
            <a href="sistema.php?aba=clientes" class="aba-btn <?php echo ($abaAtiva === 'clientes') ? 'ativa' : ''; ?>">
                3. CRUD Clientes (<?php echo $totalClientes; ?>)
            </a>
        </div>

        <!-- =============================================================
             MÓDULO 1: CRUD DE AGENDAMENTOS DE OFICINA (ORDENS DE SERVIÇO)
             ============================================================= -->
        <section class="secao-crud" id="modulo-agendamentos" style="<?php echo ($abaAtiva === 'agendamentos' || $abaAtiva === 'todos') ? 'display:block;' : 'display:none;'; ?>">
            <div class="bloco-sistema">
                <h2><?php echo $agendamentoEdicao ? 'Editar Ordem de Serviço #' . (int)$agendamentoEdicao['id'] . ' (UPDATE)' : 'Novo Agendamento de Oficina / Ordem de Serviço (CREATE)'; ?></h2>
                <p>Registre ou atualize serviços de revisão, manutenção e instalação de componentes vinculados aos clientes cadastrados.</p>

                <form method="POST" action="sistema.php?aba=agendamentos" class="form-grid">
                    <input type="hidden" name="acao_agendamento" value="<?php echo $agendamentoEdicao ? 'atualizar' : 'cadastrar'; ?>">
                    <?php if ($agendamentoEdicao): ?>
                        <input type="hidden" name="id" value="<?php echo (int) $agendamentoEdicao['id']; ?>">
                    <?php endif; ?>

                    <div class="campo-form">
                        <label for="ag_cliente_id">Cliente Cadastrado: *</label>
                        <select name="cliente_id" id="ag_cliente_id" required>
                            <option value="">-- Selecione o cliente --</option>
                            <?php foreach ($listaClientes as $cli): ?>
                                <option value="<?php echo (int) $cli['id']; ?>"
                                    <?php echo ($agendamentoEdicao && (int)$agendamentoEdicao['cliente_id'] === (int)$cli['id']) ? 'selected' : ''; ?>>
                                    #<?php echo (int) $cli['id']; ?> - <?php echo htmlspecialchars($cli['nome']); ?> (<?php echo htmlspecialchars($cli['cpf']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo-form">
                        <label for="ag_bicicleta">Modelo da Bicicleta do Cliente: *</label>
                        <input type="text" name="bicicleta_modelo" id="ag_bicicleta" required
                               placeholder="Ex.: Caloi Explorer Aro 29"
                               value="<?php echo htmlspecialchars($agendamentoEdicao['bicicleta_modelo'] ?? ''); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="ag_servico">Serviço / Pacote Escolhido: *</label>
                        <input type="text" name="servico" id="ag_servico" required
                               placeholder="Ex.: Revisão Completa Premium"
                               value="<?php echo htmlspecialchars($agendamentoEdicao['servico'] ?? $servicoPre); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="ag_unidade">Unidade de Atendimento: *</label>
                        <select name="unidade" id="ag_unidade">
                            <?php
                            $unidades = ['Matriz - Asa Norte', 'Filial - Águas Claras'];
                            $unidadeAtual = $agendamentoEdicao['unidade'] ?? 'Matriz - Asa Norte';
                            foreach ($unidades as $un):
                            ?>
                                <option value="<?php echo htmlspecialchars($un); ?>" <?php echo ($unidadeAtual === $un) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($un); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo-form">
                        <label for="ag_data">Data Agendada: *</label>
                        <input type="date" name="data_agendamento" id="ag_data" required
                               value="<?php echo htmlspecialchars($agendamentoEdicao['data_agendamento'] ?? date('Y-m-d', strtotime('+2 days'))); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="ag_valor">Valor do Orçamento / Serviço (R$): *</label>
                        <input type="number" step="0.01" min="1" name="valor" id="ag_valor" required
                               placeholder="Ex.: 149.90"
                               value="<?php echo htmlspecialchars($agendamentoEdicao['valor'] ?? $valorPre); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="ag_status">Status da Ordem de Serviço: *</label>
                        <select name="status" id="ag_status">
                            <?php
                            $statusOpcoes = ['Agendado', 'Em Manutenção', 'Concluído', 'Cancelado'];
                            $statusAtual  = $agendamentoEdicao['status'] ?? 'Agendado';
                            foreach ($statusOpcoes as $st):
                            ?>
                                <option value="<?php echo htmlspecialchars($st); ?>" <?php echo ($statusAtual === $st) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($st); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo-form campo-largo">
                        <label for="ag_obs">Observações Técnicas / Diagnóstico:</label>
                        <input type="text" name="observacoes" id="ag_obs"
                               placeholder="Ex.: Verificar desgaste de pastilhas, alinhar gancheira e lubrificar corrente."
                               value="<?php echo htmlspecialchars($agendamentoEdicao['observacoes'] ?? $obsPre); ?>">
                    </div>

                    <div class="acoes-form">
                        <button type="submit" class="botao">
                            <?php echo $agendamentoEdicao ? 'Salvar Alterações (UPDATE)' : 'Cadastrar Agendamento (CREATE)'; ?>
                        </button>
                        <?php if ($agendamentoEdicao): ?>
                            <a href="sistema.php?aba=agendamentos" class="botao botao-secundario">Cancelar Edição</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="bloco-sistema">
                <div class="cabecalho-listagem">
                    <h2>Ordens de Serviço Cadastradas (READ)</h2>
                    <form method="GET" action="sistema.php" class="form-filtro-inline">
                        <input type="hidden" name="aba" value="agendamentos">
                        <label for="filtro_status_os">Filtrar Status:</label>
                        <select name="filtro_status_os" id="filtro_status_os" onchange="this.form.submit()">
                            <option value="">Todos os status</option>
                            <option value="Agendado" <?php echo ($filtroStatusOs === 'Agendado') ? 'selected' : ''; ?>>Agendado</option>
                            <option value="Em Manutenção" <?php echo ($filtroStatusOs === 'Em Manutenção') ? 'selected' : ''; ?>>Em Manutenção</option>
                            <option value="Concluído" <?php echo ($filtroStatusOs === 'Concluído') ? 'selected' : ''; ?>>Concluído</option>
                            <option value="Cancelado" <?php echo ($filtroStatusOs === 'Cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                        </select>
                    </form>
                </div>

                <div class="tabela-responsiva">
                    <table class="tabela-catalogo tabela-crud">
                        <thead>
                            <tr>
                                <th class="cor-cabecalho">OS #</th>
                                <th class="cor-cabecalho">Cliente / Contato</th>
                                <th class="cor-cabecalho">Bicicleta</th>
                                <th class="cor-cabecalho">Serviço / Observações</th>
                                <th class="cor-cabecalho">Unidade</th>
                                <th class="cor-cabecalho">Data</th>
                                <th class="cor-cabecalho">Valor (R$)</th>
                                <th class="cor-cabecalho">Status</th>
                                <th class="cor-cabecalho">Ações (UPDATE / DELETE)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listaAgendamentos)): ?>
                                <tr>
                                    <td colspan="9">Nenhum agendamento encontrado no banco de dados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($listaAgendamentos as $ag): ?>
                                    <tr>
                                        <td><strong>#<?php echo (int) $ag['id']; ?></strong></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($ag['cliente_nome']); ?></strong><br>
                                            <small><?php echo htmlspecialchars($ag['cliente_telefone']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($ag['bicicleta_modelo']); ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($ag['servico']); ?></strong>
                                            <?php if (!empty($ag['observacoes'])): ?>
                                                <br><small><?php echo htmlspecialchars($ag['observacoes']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($ag['unidade']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($ag['data_agendamento'])); ?></td>
                                        <td><strong>R$ <?php echo number_format((float) $ag['valor'], 2, ',', '.'); ?></strong></td>
                                        <td>
                                            <span class="badge-status status-<?php echo strtolower(str_replace([' ', 'ú', 'ê', 'ã'], ['-', 'u', 'e', 'a'], $ag['status'])); ?>">
                                                <?php echo htmlspecialchars($ag['status']); ?>
                                            </span>
                                        </td>
                                        <td class="celula-acoes">
                                            <a href="sistema.php?aba=agendamentos&amp;editar_agendamento=<?php echo (int) $ag['id']; ?>" class="btn-acao btn-editar">Editar</a>
                                            <a href="sistema.php?aba=agendamentos&amp;excluir_agendamento=<?php echo (int) $ag['id']; ?>"
                                               class="btn-acao btn-excluir"
                                               onclick="return confirm('Tem certeza que deseja excluir a Ordem de Serviço #<?php echo (int) $ag['id']; ?>?');">Excluir</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- =============================================================
             MÓDULO 2: CRUD DE PRODUTOS E ESTOQUE
             ============================================================= -->
        <section class="secao-crud" id="modulo-produtos" style="<?php echo ($abaAtiva === 'produtos' || $abaAtiva === 'todos') ? 'display:block;' : 'display:none;'; ?>">
            <div class="bloco-sistema">
                <h2><?php echo $produtoEdicao ? 'Editar Produto #' . (int)$produtoEdicao['id'] . ' (UPDATE)' : 'Cadastrar Novo Produto / Serviço no Catálogo (CREATE)'; ?></h2>
                <p>Gerencie o estoque de bicicletas, peças, acessórios e serviços de oficina comercializados pela SoftByke.</p>

                <form method="POST" action="sistema.php?aba=produtos" class="form-grid">
                    <input type="hidden" name="acao_produto" value="<?php echo $produtoEdicao ? 'atualizar' : 'cadastrar'; ?>">
                    <?php if ($produtoEdicao): ?>
                        <input type="hidden" name="id" value="<?php echo (int) $produtoEdicao['id']; ?>">
                    <?php endif; ?>

                    <div class="campo-form">
                        <label for="prod_categoria">Categoria: *</label>
                        <select name="categoria" id="prod_categoria" required>
                            <?php
                            $categorias = ['Bicicletas', 'Peças', 'Acessórios', 'Manutenção'];
                            $catAtual = $produtoEdicao['categoria'] ?? 'Bicicletas';
                            foreach ($categorias as $cat):
                            ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($catAtual === $cat) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo-form">
                        <label for="prod_modelo">Nome / Modelo do Produto: *</label>
                        <input type="text" name="modelo" id="prod_modelo" required
                               placeholder="Ex.: Bicicleta Oggi Hacker Sport Aro 29"
                               value="<?php echo htmlspecialchars($produtoEdicao['modelo'] ?? ''); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="prod_marca">Marca / Fabricante: *</label>
                        <input type="text" name="marca" id="prod_marca" required
                               placeholder="Ex.: Caloi, Oggi, Shimano, SoftByke"
                               value="<?php echo htmlspecialchars($produtoEdicao['marca'] ?? ''); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="prod_preco">Preço Unitário (R$): *</label>
                        <input type="number" step="0.01" min="0.01" name="preco" id="prod_preco" required
                               placeholder="Ex.: 1299.00"
                               value="<?php echo htmlspecialchars($produtoEdicao['preco'] ?? ''); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="prod_estoque">Quantidade em Estoque: *</label>
                        <input type="number" min="0" name="estoque" id="prod_estoque" required
                               value="<?php echo htmlspecialchars($produtoEdicao['estoque'] ?? '5'); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="prod_status">Status Comercial: *</label>
                        <select name="status" id="prod_status">
                            <?php
                            $statusProd = ['Disponível', 'Sob encomenda', 'Esgotado'];
                            $stProdAtual = $produtoEdicao['status'] ?? 'Disponível';
                            foreach ($statusProd as $sp):
                            ?>
                                <option value="<?php echo htmlspecialchars($sp); ?>" <?php echo ($stProdAtual === $sp) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($sp); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo-form campo-largo">
                        <label for="prod_descricao">Descrição Técnica:</label>
                        <input type="text" name="descricao" id="prod_descricao"
                               placeholder="Especificações técnicas resumidas da bicicleta, peça ou acessório."
                               value="<?php echo htmlspecialchars($produtoEdicao['descricao'] ?? ''); ?>">
                    </div>

                    <div class="acoes-form">
                        <button type="submit" class="botao">
                            <?php echo $produtoEdicao ? 'Atualizar Produto (UPDATE)' : 'Adicionar ao Catálogo (CREATE)'; ?>
                        </button>
                        <?php if ($produtoEdicao): ?>
                            <a href="sistema.php?aba=produtos" class="botao botao-secundario">Cancelar Edição</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="bloco-sistema">
                <div class="cabecalho-listagem">
                    <h2>Itens do Catálogo e Estoque no Banco de Dados (READ)</h2>
                    <form method="GET" action="sistema.php" class="form-filtro-inline">
                        <input type="hidden" name="aba" value="produtos">
                        <label for="filtro_categoria">Filtrar Categoria:</label>
                        <select name="filtro_categoria" id="filtro_categoria" onchange="this.form.submit()">
                            <option value="">Todas as categorias</option>
                            <option value="Bicicletas" <?php echo ($filtroCategoria === 'Bicicletas') ? 'selected' : ''; ?>>Bicicletas</option>
                            <option value="Peças" <?php echo ($filtroCategoria === 'Peças') ? 'selected' : ''; ?>>Peças</option>
                            <option value="Acessórios" <?php echo ($filtroCategoria === 'Acessórios') ? 'selected' : ''; ?>>Acessórios</option>
                            <option value="Manutenção" <?php echo ($filtroCategoria === 'Manutenção') ? 'selected' : ''; ?>>Manutenção</option>
                        </select>
                    </form>
                </div>

                <div class="tabela-responsiva">
                    <table class="tabela-catalogo tabela-crud">
                        <thead>
                            <tr>
                                <th class="cor-cabecalho">ID</th>
                                <th class="cor-cabecalho">Categoria</th>
                                <th class="cor-cabecalho">Modelo / Descrição</th>
                                <th class="cor-cabecalho">Marca</th>
                                <th class="cor-cabecalho">Preço (R$)</th>
                                <th class="cor-cabecalho">Qtd. Estoque</th>
                                <th class="cor-cabecalho">Status</th>
                                <th class="cor-cabecalho">Ações (UPDATE / DELETE)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listaProdutos)): ?>
                                <tr>
                                    <td colspan="8">Nenhum produto encontrado para o filtro selecionado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($listaProdutos as $prod): ?>
                                    <tr>
                                        <td><strong>#<?php echo (int) $prod['id']; ?></strong></td>
                                        <td><?php echo htmlspecialchars($prod['categoria']); ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($prod['modelo']); ?></strong>
                                            <?php if (!empty($prod['descricao'])): ?>
                                                <br><small><?php echo htmlspecialchars($prod['descricao']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($prod['marca']); ?></td>
                                        <td><strong>R$ <?php echo number_format((float) $prod['preco'], 2, ',', '.'); ?></strong></td>
                                        <td><?php echo (int) $prod['estoque']; ?> un.</td>
                                        <td><?php echo htmlspecialchars($prod['status']); ?></td>
                                        <td class="celula-acoes">
                                            <a href="sistema.php?aba=produtos&amp;editar_produto=<?php echo (int) $prod['id']; ?>" class="btn-acao btn-editar">Editar</a>
                                            <a href="sistema.php?aba=produtos&amp;excluir_produto=<?php echo (int) $prod['id']; ?>"
                                               class="btn-acao btn-excluir"
                                               onclick="return confirm('Deseja realmente excluir o produto <?php echo htmlspecialchars(addslashes($prod['modelo'])); ?>?');">Excluir</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- =============================================================
             MÓDULO 3: CRUD DE CLIENTES
             ============================================================= -->
        <section class="secao-crud" id="modulo-clientes" style="<?php echo ($abaAtiva === 'clientes' || $abaAtiva === 'todos') ? 'display:block;' : 'display:none;'; ?>">
            <div class="bloco-sistema">
                <h2><?php echo $clienteEdicao ? 'Editar Cliente #' . (int)$clienteEdicao['id'] . ' (UPDATE)' : 'Cadastrar Novo Cliente SoftByke (CREATE)'; ?></h2>
                <p>Conforme a Regra de Negócio RN01, o cliente deve estar cadastrado para realizar compras faturadas e agendamentos de revisão na oficina.</p>

                <form method="POST" action="sistema.php?aba=clientes" class="form-grid">
                    <input type="hidden" name="acao_cliente" value="<?php echo $clienteEdicao ? 'atualizar' : 'cadastrar'; ?>">
                    <?php if ($clienteEdicao): ?>
                        <input type="hidden" name="id" value="<?php echo (int) $clienteEdicao['id']; ?>">
                    <?php endif; ?>

                    <div class="campo-form">
                        <label for="cli_nome">Nome Completo: *</label>
                        <input type="text" name="nome" id="cli_nome" required
                               placeholder="Ex.: João Gabriel da Silva"
                               value="<?php echo htmlspecialchars($clienteEdicao['nome'] ?? ''); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="cli_cpf">CPF: *</label>
                        <input type="text" name="cpf" id="cli_cpf" required maxlength="14"
                               placeholder="000.000.000-00"
                               value="<?php echo htmlspecialchars($clienteEdicao['cpf'] ?? ''); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="cli_telefone">Telefone / WhatsApp: *</label>
                        <input type="text" name="telefone" id="cli_telefone" required
                               placeholder="(61) 99999-0000"
                               value="<?php echo htmlspecialchars($clienteEdicao['telefone'] ?? ''); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="cli_email">E-mail: *</label>
                        <input type="email" name="email" id="cli_email" required
                               placeholder="cliente@email.com"
                               value="<?php echo htmlspecialchars($clienteEdicao['email'] ?? ''); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="cli_cidade">Bairro / Cidade - UF: *</label>
                        <input type="text" name="cidade" id="cli_cidade" required
                               placeholder="Brasília - Asa Norte"
                               value="<?php echo htmlspecialchars($clienteEdicao['cidade'] ?? 'Brasília - DF'); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="cli_unidade">Unidade de Preferência: *</label>
                        <select name="unidade_preferida" id="cli_unidade">
                            <?php
                            $unidadesCli = ['Matriz - Asa Norte', 'Filial - Águas Claras'];
                            $unCliAtual = $clienteEdicao['unidade_preferida'] ?? 'Matriz - Asa Norte';
                            foreach ($unidadesCli as $uc):
                            ?>
                                <option value="<?php echo htmlspecialchars($uc); ?>" <?php echo ($unCliAtual === $uc) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($uc); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="acoes-form">
                        <button type="submit" class="botao">
                            <?php echo $clienteEdicao ? 'Atualizar Cliente (UPDATE)' : 'Cadastrar Cliente (CREATE)'; ?>
                        </button>
                        <?php if ($clienteEdicao): ?>
                            <a href="sistema.php?aba=clientes" class="botao botao-secundario">Cancelar Edição</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="bloco-sistema">
                <div class="cabecalho-listagem">
                    <h2>Base de Clientes Cadastrados (READ)</h2>
                    <form method="GET" action="sistema.php" class="form-filtro-inline">
                        <input type="hidden" name="aba" value="clientes">
                        <input type="text" name="busca_cliente" placeholder="Buscar por nome, CPF ou e-mail..."
                               value="<?php echo htmlspecialchars($buscaCliente); ?>">
                        <button type="submit" class="botao">Pesquisar</button>
                        <?php if ($buscaCliente !== ''): ?>
                            <a href="sistema.php?aba=clientes" class="botao botao-secundario">Limpar</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="tabela-responsiva">
                    <table class="tabela-catalogo tabela-crud">
                        <thead>
                            <tr>
                                <th class="cor-cabecalho">ID</th>
                                <th class="cor-cabecalho">Nome Completo</th>
                                <th class="cor-cabecalho">CPF</th>
                                <th class="cor-cabecalho">Telefone</th>
                                <th class="cor-cabecalho">E-mail</th>
                                <th class="cor-cabecalho">Cidade / Bairro</th>
                                <th class="cor-cabecalho">Unidade Preferida</th>
                                <th class="cor-cabecalho">Ações (UPDATE / DELETE)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listaClientes)): ?>
                                <tr>
                                    <td colspan="8">Nenhum cliente encontrado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($listaClientes as $cli): ?>
                                    <tr>
                                        <td><strong>#<?php echo (int) $cli['id']; ?></strong></td>
                                        <td><strong><?php echo htmlspecialchars($cli['nome']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($cli['cpf']); ?></td>
                                        <td><?php echo htmlspecialchars($cli['telefone']); ?></td>
                                        <td><?php echo htmlspecialchars($cli['email']); ?></td>
                                        <td><?php echo htmlspecialchars($cli['cidade']); ?></td>
                                        <td><?php echo htmlspecialchars($cli['unidade_preferida']); ?></td>
                                        <td class="celula-acoes">
                                            <a href="sistema.php?aba=clientes&amp;editar_cliente=<?php echo (int) $cli['id']; ?>" class="btn-acao btn-editar">Editar</a>
                                            <a href="sistema.php?aba=clientes&amp;excluir_cliente=<?php echo (int) $cli['id']; ?>"
                                               class="btn-acao btn-excluir"
                                               onclick="return confirm('Tem certeza que deseja excluir o cliente <?php echo htmlspecialchars(addslashes($cli['nome'])); ?>? Os agendamentos vinculados também serão removidos.');">Excluir</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p><strong>SoftByke</strong> - Loja de bicicletas, peças e oficina especializada</p>
        <p>Ciência da Computação - Disciplina: Desenvolvimento Web <sup>Turma: A</sup> - Prof. Valdemir S Silva</p>
        <p>Davi de Sousa Costa &bull; Igor Christofidis de Lima &bull; Pedro Carolino Barreto Sarmento &bull; Vinícius Bernardes Carneiro</p>
        <p>&copy; 2026 SoftByke <sub>Todos os direitos reservados</sub></p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
