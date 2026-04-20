<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
iniciarSessao();

if (usuarioLogado()) {
    header('Location: /index.php');
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $conf  = $_POST['confirmar_senha'] ?? '';

    if (!$nome || !$email || !$senha || !$conf) {
        $erro = 'Preencha todos os campos.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter ao menos 6 caracteres.';
    } elseif ($senha !== $conf) {
        $erro = 'As senhas não coincidem.';
    } else {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $nome, $email, $hash);

        if ($stmt->execute()) {
            $sucesso = 'Conta criada! Faça login.';
        } else {
            $erro = 'E-mail já cadastrado.';
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
    <title>Cadastro — Garagem</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<div class="login-wrap">
    <div class="login-box">
        <h1>&#x1F697; <span class="logo-accent">Garagem</span></h1>
        <p>Crie sua conta gratuita.</p>

        <?php if ($erro):    ?><div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
        <?php if ($sucesso): ?><div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div><?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nome completo</label>
                <input type="text" name="nome" placeholder="Seu nome"
                       value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" placeholder="voce@email.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" placeholder="Mínimo 6 caracteres" required>
            </div>
            <div class="form-group">
                <label>Confirmar senha</label>
                <input type="password" name="confirmar_senha" placeholder="Repita a senha" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:.5rem">
                Criar conta
            </button>
        </form>

        <p style="text-align:center;margin-top:1.2rem;font-size:.875rem;color:#888">
            Já tem conta? <a href="/login.php" style="color:#e94560">Entrar</a>
        </p>
    </div>
</div>
</body>
</html>
