<?php
require_once __DIR__ . '/php/conexao.php';

$mensagemSucesso = '';
$mensagemErro    = '';
$abaAtiva        = isset($_GET['aba']) && $_GET['aba'] === 'produtos' ? 'produtos' : 'agendamentos';

if ($pdo !== null) {

    // 1. MÓDULO DE PRODUTOS E ESTOQUE
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
                    $mensagemSucesso = "Produto \"{$modelo}\" adicionado ao estoque com sucesso!";
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
                    $mensagemSucesso = "Dados do produto atualizados com sucesso no estoque!";
                }
            } catch (PDOException $e) {
                $mensagemErro = 'Não foi possível salvar os dados do produto no momento.';
            }
        }
    }

    if (isset($_GET['excluir_produto'])) {
        $abaAtiva = 'produtos';
        $idExcluir = (int) $_GET['excluir_produto'];
        try {
            $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
            $stmt->execute([':id' => $idExcluir]);
            $mensagemSucesso = "Produto removido do catálogo com sucesso!";
        } catch (PDOException $e) {
            $mensagemErro = 'Não foi possível remover o produto selecionado.';
        }
    }

    // 2. MÓDULO DE AGENDAMENTOS DE OFICINA
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao_agendamento'])) {
        $abaDestino      = isset($_POST['aba_origem']) && $_POST['aba_origem'] === 'produtos' ? 'produtos' : 'agendamentos';
        $abaAtiva        = $abaDestino;
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
                    $mensagemSucesso = "Seu agendamento de serviço foi registrado com sucesso na oficina!";
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
                    $mensagemSucesso = "Ordem de serviço atualizada com sucesso!";
                }
            } catch (PDOException $e) {
                $mensagemErro = 'Não foi possível registrar o agendamento no momento.';
            }
        }
    }

    if (isset($_GET['excluir_agendamento'])) {
        $abaAtiva = 'produtos';
        $idExcluir = (int) $_GET['excluir_agendamento'];
        try {
            $stmt = $pdo->prepare("DELETE FROM agendamentos WHERE id = :id");
            $stmt->execute([':id' => $idExcluir]);
            $mensagemSucesso = "Agendamento cancelado e removido com sucesso!";
        } catch (PDOException $e) {
            $mensagemErro = 'Não foi possível remover o agendamento selecionado.';
        }
    }
}

$listaClientes     = [];
$listaProdutos     = [];
$listaAgendamentos = [];

$produtoEdicao     = null;
$agendamentoEdicao = null;

$filtroCategoria   = trim($_GET['filtro_categoria'] ?? '');
$filtroStatusOs    = trim($_GET['filtro_status_os'] ?? '');

if ($pdo !== null) {
    if (isset($_GET['editar_produto'])) {
        $abaAtiva = 'produtos';
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->execute([':id' => (int) $_GET['editar_produto']]);
        $produtoEdicao = $stmt->fetch();
    }

    if (isset($_GET['editar_agendamento'])) {
        $abaAtiva = 'produtos';
        $stmt = $pdo->prepare("SELECT * FROM agendamentos WHERE id = :id");
        $stmt->execute([':id' => (int) $_GET['editar_agendamento']]);
        $agendamentoEdicao = $stmt->fetch();
    }

    $listaClientes = $pdo->query("SELECT * FROM clientes ORDER BY id DESC")->fetchAll();

    if ($filtroCategoria !== '') {
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE categoria = :cat ORDER BY categoria, modelo");
        $stmt->execute([':cat' => $filtroCategoria]);
        $listaProdutos = $stmt->fetchAll();
    } else {
        $listaProdutos = $pdo->query("SELECT * FROM produtos ORDER BY categoria, modelo")->fetchAll();
    }

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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoftByke - <?php echo ($abaAtiva === 'produtos') ? 'Gestão de Estoque e Ordens de Serviço' : 'Agendamento de Oficina'; ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body class="pg-sistema" data-pagina="sistema">

    <header class="cabecalho">
        <a href="index.html" class="logo">Soft<span>Byke</span></a>
        <nav class="menu-principal" id="menuPrincipal">
            <ul>
                <li><a href="index.html" data-pagina="inicio">Início</a></li>
                <li><a href="galeria.html" data-pagina="galeria">Catálogo</a></li>
                <li><a href="orcamento.html" data-pagina="orcamento">Orçamento</a></li>
                <li><a href="sistema.php?aba=agendamentos" class="<?php echo ($abaAtiva === 'agendamentos') ? 'ativo' : ''; ?>" data-pagina="oficina">Oficina</a></li>
                <li><a href="sistema.php?aba=produtos" class="<?php echo ($abaAtiva === 'produtos') ? 'ativo' : ''; ?>" data-pagina="estoque">Estoque</a></li>
                <li id="itemNavCadastro"><a href="#" class="nav-destaque-cadastro" onclick="abrirModalCadastro(event)">Cadastre-se</a></li>
            </ul>
        </nav>
    </header>

    <section class="faixa-titulo">
        <?php if ($abaAtiva === 'agendamentos'): ?>
            <h1>Agendamento de Serviços e Oficina Especializada</h1>
            <p>Agende revisões completas, regulagens de transmissão e manutenções nas unidades Asa Norte e Águas Claras.</p>
        <?php else: ?>
            <h1>Controle de Estoque e Ordens de Serviço</h1>
            <p>Gerenciamento comercial de bicicletas, peças de reposição, acessórios e acompanhamento das ordens de serviço da oficina.</p>
        <?php endif; ?>
    </section>

    <main>
        <?php if ($erroConexao !== null): ?>
            <div class="alerta alerta-erro">
                <strong>Aviso de Sistema:</strong> <?php echo htmlspecialchars($erroConexao); ?>
            </div>
        <?php endif; ?>

        <?php if ($mensagemSucesso !== ''): ?>
            <div class="alerta alerta-sucesso">
                <strong>Operação concluída:</strong> <?php echo htmlspecialchars($mensagemSucesso); ?>
            </div>
        <?php endif; ?>

        <?php if ($mensagemErro !== ''): ?>
            <div class="alerta alerta-erro">
                <strong>Atenção:</strong> <?php echo htmlspecialchars($mensagemErro); ?>
            </div>
        <?php endif; ?>

        <!-- =============================================================
             ABA 1: OFICINA (VISÃO DO CLIENTE - APENAS FORMULÁRIO DE AGENDAMENTO)
             ============================================================= -->
        <section class="secao-crud" id="modulo-agendamentos" style="<?php echo ($abaAtiva === 'agendamentos') ? 'display:block;' : 'display:none;'; ?>">
            <div class="bloco-sistema">
                <h2>Novo Agendamento de Serviço na Oficina</h2>
                <p>Informe seu cadastro, o modelo da bicicleta e o serviço desejado para programar seu atendimento técnico.</p>

                <form method="POST" action="sistema.php?aba=agendamentos" class="form-grid">
                    <input type="hidden" name="acao_agendamento" value="cadastrar">
                    <input type="hidden" name="aba_origem" value="agendamentos">
                    <input type="hidden" name="status" value="Agendado">

                    <div class="campo-form">
                        <label for="ag_cliente_id">Cliente *</label>
                        <select name="cliente_id" id="ag_cliente_id" required>
                            <option value="">Selecione o cliente cadastrado</option>
                            <?php foreach ($listaClientes as $cli): ?>
                                <option value="<?php echo (int) $cli['id']; ?>">
                                    <?php echo htmlspecialchars($cli['nome']); ?> — CPF <?php echo htmlspecialchars($cli['cpf']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo-form">
                        <label for="ag_bicicleta">Modelo da Bicicleta *</label>
                        <input type="text" name="bicicleta_modelo" id="ag_bicicleta" required
                               placeholder="Ex.: Caloi Explorer Aro 29">
                    </div>

                    <div class="campo-form">
                        <label for="ag_servico">Serviço Solicitado *</label>
                        <input type="text" name="servico" id="ag_servico" required
                               placeholder="Ex.: Revisão Completa Premium">
                    </div>

                    <div class="campo-form">
                        <label for="ag_unidade">Unidade de Atendimento *</label>
                        <select name="unidade" id="ag_unidade">
                            <option value="Matriz - Asa Norte">Matriz - Asa Norte</option>
                            <option value="Filial - Águas Claras">Filial - Águas Claras</option>
                        </select>
                    </div>

                    <div class="campo-form">
                        <label for="ag_data">Data Programada *</label>
                        <input type="date" name="data_agendamento" id="ag_data" required
                               value="<?php echo date('Y-m-d', strtotime('+2 days')); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="ag_valor">Valor Estimado do Serviço em R$ *</label>
                        <input type="number" step="0.01" min="1" name="valor" id="ag_valor" required
                               placeholder="Ex.: 149.90">
                    </div>

                    <div class="campo-form campo-largo">
                        <label for="ag_obs">Observações Técnicas</label>
                        <input type="text" name="observacoes" id="ag_obs"
                               placeholder="Ex.: Verificar desgaste de pastilhas, alinhar gancheira e lubrificar corrente.">
                    </div>

                    <div class="acoes-form">
                        <button type="submit" class="botao">Confirmar Agendamento</button>
                    </div>
                </form>
            </div>
        </section>

        <!-- =============================================================
             ABA 2: ESTOQUE (VISÃO ADMINISTRATIVA - ESTOQUE + ORDENS DE SERVIÇO PROGRAMADAS)
             ============================================================= -->
        <section class="secao-crud" id="modulo-produtos" style="<?php echo ($abaAtiva === 'produtos') ? 'display:block;' : 'display:none;'; ?>">
            <div class="bloco-sistema">
                <h2><?php echo $produtoEdicao ? 'Atualizar Produto #' . (int)$produtoEdicao['id'] : 'Adicionar Novo Produto ou Serviço ao Catálogo'; ?></h2>
                <p>Mantenha o catálogo e a disponibilidade de bicicletas, peças, acessórios e serviços sempre atualizados.</p>

                <form method="POST" action="sistema.php?aba=produtos" class="form-grid">
                    <input type="hidden" name="acao_produto" value="<?php echo $produtoEdicao ? 'atualizar' : 'cadastrar'; ?>">
                    <?php if ($produtoEdicao): ?>
                        <input type="hidden" name="id" value="<?php echo (int) $produtoEdicao['id']; ?>">
                    <?php endif; ?>

                    <div class="campo-form">
                        <label for="prod_categoria">Categoria *</label>
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
                        <label for="prod_modelo">Nome ou Modelo do Produto *</label>
                        <input type="text" name="modelo" id="prod_modelo" required
                               placeholder="Ex.: Bicicleta Oggi Hacker Sport Aro 29"
                               value="<?php echo htmlspecialchars($produtoEdicao['modelo'] ?? ''); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="prod_marca">Marca ou Fabricante *</label>
                        <input type="text" name="marca" id="prod_marca" required
                               placeholder="Ex.: Caloi, Oggi, Shimano, SoftByke"
                               value="<?php echo htmlspecialchars($produtoEdicao['marca'] ?? ''); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="prod_preco">Preço Unitário em R$ *</label>
                        <input type="number" step="0.01" min="0.01" name="preco" id="prod_preco" required
                               placeholder="Ex.: 1299.00"
                               value="<?php echo htmlspecialchars($produtoEdicao['preco'] ?? ''); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="prod_estoque">Quantidade Disponível *</label>
                        <input type="number" min="0" name="estoque" id="prod_estoque" required
                               value="<?php echo htmlspecialchars($produtoEdicao['estoque'] ?? '5'); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="prod_status">Disponibilidade Comercial *</label>
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
                        <label for="prod_descricao">Especificações e Detalhes</label>
                        <input type="text" name="descricao" id="prod_descricao"
                               placeholder="Especificações resumidas da bicicleta, peça ou acessório."
                               value="<?php echo htmlspecialchars($produtoEdicao['descricao'] ?? ''); ?>">
                    </div>

                    <div class="acoes-form">
                        <button type="submit" class="botao">
                            <?php echo $produtoEdicao ? 'Salvar Alterações' : 'Incluir no Catálogo'; ?>
                        </button>
                        <?php if ($produtoEdicao): ?>
                            <a href="sistema.php?aba=produtos" class="botao botao-secundario">Cancelar Edição</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="bloco-sistema">
                <div class="cabecalho-listagem">
                    <h2>Produtos e Serviços em Estoque</h2>
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
                    <table class="tabela-carrinho tabela-crud">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Categoria</th>
                                <th>Modelo e Especificações</th>
                                <th>Marca</th>
                                <th>Preço</th>
                                <th>Estoque</th>
                                <th>Disponibilidade</th>
                                <th>Gerenciar</th>
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
                                        <td class="col-produto-carrinho">
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
                                               onclick="return confirmarExclusaoLink(event, this.href, 'Deseja realmente remover o produto <?php echo htmlspecialchars(addslashes($prod['modelo'])); ?> do catálogo?');">Remover</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if ($agendamentoEdicao): ?>
                <div class="bloco-sistema" id="blocoEdicaoOrdem">
                    <h2>Atualizar Ordem de Serviço #<?php echo (int) $agendamentoEdicao['id']; ?></h2>
                    <p>Altere a situação, data, unidade ou valores da ordem de serviço selecionada.</p>

                    <form method="POST" action="sistema.php?aba=produtos" class="form-grid">
                        <input type="hidden" name="acao_agendamento" value="atualizar">
                        <input type="hidden" name="aba_origem" value="produtos">
                        <input type="hidden" name="id" value="<?php echo (int) $agendamentoEdicao['id']; ?>">

                        <div class="campo-form">
                            <label for="ed_ag_cliente_id">Cliente *</label>
                            <select name="cliente_id" id="ed_ag_cliente_id" required>
                                <?php foreach ($listaClientes as $cli): ?>
                                    <option value="<?php echo (int) $cli['id']; ?>"
                                        <?php echo ((int)$agendamentoEdicao['cliente_id'] === (int)$cli['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cli['nome']); ?> — CPF <?php echo htmlspecialchars($cli['cpf']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="campo-form">
                            <label for="ed_ag_bicicleta">Modelo da Bicicleta *</label>
                            <input type="text" name="bicicleta_modelo" id="ed_ag_bicicleta" required
                                   value="<?php echo htmlspecialchars($agendamentoEdicao['bicicleta_modelo']); ?>">
                        </div>

                        <div class="campo-form">
                            <label for="ed_ag_servico">Serviço Solicitado *</label>
                            <input type="text" name="servico" id="ed_ag_servico" required
                                   value="<?php echo htmlspecialchars($agendamentoEdicao['servico']); ?>">
                        </div>

                        <div class="campo-form">
                            <label for="ed_ag_unidade">Unidade de Atendimento *</label>
                            <select name="unidade" id="ed_ag_unidade">
                                <?php
                                $unidades = ['Matriz - Asa Norte', 'Filial - Águas Claras'];
                                foreach ($unidades as $un):
                                ?>
                                    <option value="<?php echo htmlspecialchars($un); ?>" <?php echo ($agendamentoEdicao['unidade'] === $un) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($un); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="campo-form">
                            <label for="ed_ag_data">Data Programada *</label>
                            <input type="date" name="data_agendamento" id="ed_ag_data" required
                                   value="<?php echo htmlspecialchars($agendamentoEdicao['data_agendamento']); ?>">
                        </div>

                        <div class="campo-form">
                            <label for="ed_ag_valor">Valor do Serviço em R$ *</label>
                            <input type="number" step="0.01" min="1" name="valor" id="ed_ag_valor" required
                                   value="<?php echo htmlspecialchars($agendamentoEdicao['valor']); ?>">
                        </div>

                        <div class="campo-form">
                            <label for="ed_ag_status">Situação do Atendimento *</label>
                            <select name="status" id="ed_ag_status">
                                <?php
                                $statusOpcoes = ['Agendado', 'Em Manutenção', 'Concluído', 'Cancelado'];
                                foreach ($statusOpcoes as $st):
                                ?>
                                    <option value="<?php echo htmlspecialchars($st); ?>" <?php echo ($agendamentoEdicao['status'] === $st) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($st); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="campo-form campo-largo">
                            <label for="ed_ag_obs">Observações Técnicas</label>
                            <input type="text" name="observacoes" id="ed_ag_obs"
                                   value="<?php echo htmlspecialchars($agendamentoEdicao['observacoes'] ?? ''); ?>">
                        </div>

                        <div class="acoes-form">
                            <button type="submit" class="botao">Salvar Alterações da Ordem</button>
                            <a href="sistema.php?aba=produtos" class="botao botao-secundario">Cancelar Edição</a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <div class="bloco-sistema">
                <div class="cabecalho-listagem">
                    <h2>Ordens de Serviço Programadas</h2>
                    <form method="GET" action="sistema.php" class="form-filtro-inline">
                        <input type="hidden" name="aba" value="produtos">
                        <label for="filtro_status_os">Filtrar Situação:</label>
                        <select name="filtro_status_os" id="filtro_status_os" onchange="this.form.submit()">
                            <option value="">Todas as situações</option>
                            <option value="Agendado" <?php echo ($filtroStatusOs === 'Agendado') ? 'selected' : ''; ?>>Agendado</option>
                            <option value="Em Manutenção" <?php echo ($filtroStatusOs === 'Em Manutenção') ? 'selected' : ''; ?>>Em Manutenção</option>
                            <option value="Concluído" <?php echo ($filtroStatusOs === 'Concluído') ? 'selected' : ''; ?>>Concluído</option>
                            <option value="Cancelado" <?php echo ($filtroStatusOs === 'Cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                        </select>
                    </form>
                </div>

                <div class="tabela-responsiva">
                    <table class="tabela-carrinho tabela-crud">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Cliente e Contato</th>
                                <th>Bicicleta</th>
                                <th>Serviço e Detalhes</th>
                                <th>Unidade</th>
                                <th>Data</th>
                                <th>Valor</th>
                                <th>Situação</th>
                                <th>Gerenciar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listaAgendamentos)): ?>
                                <tr>
                                    <td colspan="9">Nenhum agendamento encontrado para o filtro selecionado.</td>
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
                                            <a href="sistema.php?aba=produtos&amp;editar_agendamento=<?php echo (int) $ag['id']; ?>#blocoEdicaoOrdem" class="btn-acao btn-editar">Editar</a>
                                            <a href="sistema.php?aba=produtos&amp;excluir_agendamento=<?php echo (int) $ag['id']; ?>"
                                               class="btn-acao btn-excluir"
                                               onclick="return confirmarExclusaoLink(event, this.href, 'Deseja realmente remover a Ordem de Serviço #<?php echo (int) $ag['id']; ?>?');">Remover</a>
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
        <p><strong>SoftByke</strong> — Loja de Bicicletas, Peças e Oficina Especializada</p>
        <p>Unidades Brasília: Matriz Asa Norte &bull; Filial Águas Claras</p>
        <p>Davi de Sousa Costa &bull; Igor Christofidis de Lima &bull; Pedro Carolino Barreto Sarmento &bull; Vinícius Bernardes Carneiro</p>
        <p>&copy; 2026 SoftByke — Todos os direitos reservados</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
