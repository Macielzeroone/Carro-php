<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
exigirLogin();

$uid = $_SESSION['usuario_id'];

// Busca os carros do usuário logado
$res = $conn->query(
    "SELECT * FROM carros WHERE usuario_id = $uid ORDER BY criado_em DESC"
);
$carros = $res->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minha Garagem</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<nav>
    <a class="logo" href="/index.php">&#x1F697; Garagem</a>
    <div class="nav-links">
        <span style="color:#ccc;font-size:.9rem">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
        <a href="/carros/novo.php" class="btn btn-primary btn-sm">+ Novo carro</a>
        <form method="POST" action="/logout.php" style="margin:0">
            <button class="btn-logout">Sair</button>
        </form>
    </div>
</nav>

<div class="container">
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Meus Carros <span class="badge badge-red"><?= count($carros) ?></span></h2>

        <?php if (empty($carros)): ?>
            <div class="empty">
                <span>&#x1F697;</span>
                Sua garagem está vazia.<br>
                <a href="/carros/novo.php" class="btn btn-primary" style="margin-top:1rem">Cadastrar primeiro carro</a>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Ano</th>
                            <th>Cor</th>
                            <th>Placa</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carros as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= htmlspecialchars($c['marca']) ?></td>
                            <td><?= htmlspecialchars($c['modelo']) ?></td>
                            <td><?= $c['ano'] ?></td>
                            <td><?= htmlspecialchars($c['cor'] ?? '-') ?></td>
                            <td><strong><?= htmlspecialchars($c['placa']) ?></strong></td>
                            <td>
                                <?= $c['preco']
                                    ? 'R$ ' . number_format($c['preco'], 2, ',', '.')
                                    : '-' ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="/carros/editar.php?id=<?= $c['id'] ?>"
                                       class="btn btn-secondary btn-sm">Editar</a>
                                    <a href="/carros/excluir.php?id=<?= $c['id'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Excluir este carro?')">Excluir</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
