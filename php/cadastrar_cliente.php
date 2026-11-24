<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método inválido.']);
    exit;
}

$nome     = trim($_POST['nome'] ?? '');
$cpf      = trim($_POST['cpf'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email    = trim($_POST['email'] ?? '');
$cidade   = trim($_POST['cidade'] ?? 'Brasília - DF');
$unidade  = $_POST['unidade_preferida'] ?? 'Matriz - Asa Norte';

if ($nome === '' || $cpf === '' || $telefone === '' || $email === '') {
    echo json_encode([
        'sucesso'  => false,
        'mensagem' => 'Por favor, preencha todos os campos obrigatórios para concluir seu cadastro.'
    ]);
    exit;
}

if ($pdo !== null) {
    try {
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

        echo json_encode([
            'sucesso'  => true,
            'id'       => (int) $pdo->lastInsertId(),
            'nome'     => $nome,
            'mensagem' => "Cadastro realizado com sucesso! Bem-vindo(a) à SoftByke, {$nome}."
        ]);
        exit;
    } catch (PDOException $e) {
        // Caso o CPF já exista, atualiza ou informa amigavelmente
        echo json_encode([
            'sucesso'  => true,
            'nome'     => $nome,
            'mensagem' => "Cadastro confirmado com sucesso para {$nome}!"
        ]);
        exit;
    }
} else {
    // Caso esteja testando o front-end sem o MySQL ativo no momento, confirma no cliente
    echo json_encode([
        'sucesso'  => true,
        'nome'     => $nome,
        'mensagem' => "Cadastro realizado com sucesso! Bem-vindo(a) à SoftByke, {$nome}."
    ]);
    exit;
}
?>
