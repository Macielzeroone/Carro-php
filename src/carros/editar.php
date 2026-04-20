<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
exigirLogin();

$uid = $_SESSION['usuario_id'];
$id  = intval($_GET['id'] ?? 0);

// Busca carro garantindo que pertence ao usuário logado
$stmt = $conn->prepare('SELECT * FROM carros WHERE id = ? AND usuario_id = ?');
$stmt->bind_param('ii', $id, $uid);
$stmt->execute();
$res   = $stmt->get_result();
$carro = $res->fetch_assoc();
$stmt->close();

if (!$carro) {
    header('Location: /index.php?erro=Carro+não+encontrado.');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marca  = trim($_POST['marca']  ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $ano    = intval($_POST['ano']  ?? 0);
    $cor    = trim($_POST['cor']    ?? '');
    $placa  = strtoupper(trim($_POST['placa'] ?? ''));
    $preco  = $_POST['preco'] !== '' ? floatval(str_replace(',', '.', $_POST['preco'])) : null;

    if (!$marca || !$modelo || !$ano || !$placa) {
        $erro = 'Preencha os campos obrigatórios.';
    } elseif ($ano < 1900 || $ano > (int)date('Y') + 1) {
        $erro = 'Ano inválido.';
    } else {
        $upd = $conn->prepare(
            'UPDATE carros SET marca=?, modelo=?, ano=?, cor=?, placa=?, preco=?
             WHERE id=? AND usuario_id=?'
        );
        $upd->bind_param('ssisdiii', $marca, $modelo, $ano, $cor, $placa, $preco, $id, $uid);

        if ($upd->execute()) {
            header('Location: /index.php?msg=Carro+atualizado+com+sucesso!');
            exit;
        } else {
            $erro = 'Placa já cadastrada ou erro ao atualizar.';
        }
        $upd->close();
    }

    // Atualiza o array local para repopular o form
    $carro = array_merge($carro, $_POST);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Carro — Garagem</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<nav>
    <a class="logo" href="/index.php">&#x1F697; Garagem</a>
    <div class="nav-links">
        <a href="/index.php">← Voltar</a>
        <form method="POST" action="/logout.php" style="margin:0">
            <button class="btn-logout">Sair</button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="card">
        <h2>Editar Carro</h2>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Marca *</label>
                    <input type="text" name="marca"
                           value="<?= htmlspecialchars($carro['marca']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Modelo *</label>
                    <input type="text" name="modelo"
                           value="<?= htmlspecialchars($carro['modelo']) ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Ano *</label>
                    <input type="number" name="ano" min="1900" max="<?= date('Y') + 1 ?>"
                           value="<?= htmlspecialchars($carro['ano']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Cor</label>
                    <input type="text" name="cor"
                           value="<?= htmlspecialchars($carro['cor'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Placa *</label>
                    <input type="text" name="placa" maxlength="10"
                           value="<?= htmlspecialchars($carro['placa']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Preço (R$)</label>
                    <input type="number" name="preco" step="0.01" min="0"
                           value="<?= htmlspecialchars($carro['preco'] ?? '') ?>">
                </div>
            </div>
            <div style="display:flex;gap:1rem;margin-top:.5rem">
                <button type="submit" class="btn btn-primary">Salvar alterações</button>
                <a href="/index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
