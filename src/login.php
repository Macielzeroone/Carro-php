<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
iniciarSessao();

if (usuarioLogado()) {
    header('Location: /index.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$email || !$senha) {
        $erro = 'Preencha e-mail e senha.';
    } else {
        $stmt = $conn->prepare('SELECT id, nome, senha FROM usuarios WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $nome, $hash);
            $stmt->fetch();

            if (password_verify($senha, $hash)) {
                $_SESSION['usuario_id']   = $id;
                $_SESSION['usuario_nome'] = $nome;
                header('Location: /index.php');
                exit;
            }
        }
        $erro = 'E-mail ou senha incorretos.';
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Garagem</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<div class="login-wrap">
    <div class="login-box">
        <h1>&#x1F697; <span class="logo-accent">Garagem Do William</span></h1>
        <p>Entre na sua conta para gerenciar seus carros.</p>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" placeholder="voce@email.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:.5rem">
                Entrar
            </button>
        </form>

        <p style="text-align:center;margin-top:1.2rem;font-size:.875rem;color:#888">
            Não tem conta? <a href="/cadastro_usuario.php" style="color:#e94560">Cadastre-se</a>
        </p>
        <p style="text-align:center;margin-top:.5rem;font-size:.8rem;color:#bbb">
             Conta de teste: aadmin@email.com / 123456
        </p>
    </div>
</div>
</body>
</html>
