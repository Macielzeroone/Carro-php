<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
exigirLogin();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marca  = trim($_POST['marca']  ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $ano    = intval($_POST['ano']  ?? 0);
    $cor    = trim($_POST['cor']    ?? '');
    $placa  = strtoupper(trim($_POST['placa'] ?? ''));
    $preco  = $_POST['preco'] !== '' ? floatval(str_replace(',', '.', $_POST['preco'])) : null;
    $uid    = $_SESSION['usuario_id'];

    if (!$marca || !$modelo || !$ano || !$placa) {
        $erro = 'Preencha os campos obrigatórios (marca, modelo, ano e placa).';
    } elseif ($ano < 1900 || $ano > (int)date('Y') + 1) {
        $erro = 'Ano inválido.';
    } else {
        $stmt = $conn->prepare(
            'INSERT INTO carros (marca, modelo, ano, cor, placa, preco, usuario_id)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('ssissdi', $marca, $modelo, $ano, $cor, $placa, $preco, $uid);

        if ($stmt->execute()) {
            header('Location: /index.php?msg=Carro+cadastrado+com+sucesso!');
            exit;
        } else {
            $erro = 'Placa já cadastrada ou erro ao salvar.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Novo Carro — Garagem</title>
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
        <h2>Cadastrar Novo Carro</h2>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Marca *</label>
                    <input type="text" name="marca" placeholder="Ex: Toyota"
                           value="<?= htmlspecialchars($_POST['marca'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Modelo *</label>
                    <input type="text" name="modelo" placeholder="Ex: Corolla"
                           value="<?= htmlspecialchars($_POST['modelo'] ?? '') ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Ano *</label>
                    <input type="number" name="ano" placeholder="Ex: 2022" min="1900"
                           max="<?= date('Y') + 1 ?>"
                           value="<?= htmlspecialchars($_POST['ano'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Cor</label>
                    <input type="text" name="cor" placeholder="Ex: Branco"
                           value="<?= htmlspecialchars($_POST['cor'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Placa *</label>
                    <input type="text" name="placa" placeholder="Ex: ABC1D23"
                           maxlength="10"
                           value="<?= htmlspecialchars($_POST['placa'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Preço (R$)</label>
                    <input type="number" name="preco" placeholder="Ex: 85000.00"
                           step="0.01" min="0"
                           value="<?= htmlspecialchars($_POST['preco'] ?? '') ?>">
                </div>
            </div>
            <div style="display:flex;gap:1rem;margin-top:.5rem">
                <button type="submit" class="btn btn-primary">Salvar carro</button>
                <a href="/index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
