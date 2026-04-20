<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
exigirLogin();

$uid = $_SESSION['usuario_id'];
$id  = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare('DELETE FROM carros WHERE id = ? AND usuario_id = ?');
$stmt->bind_param('ii', $id, $uid);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    header('Location: /index.php?msg=Carro+excluído+com+sucesso.');
} else {
    header('Location: /index.php?erro=Carro+não+encontrado+ou+sem+permissão.');
}
$stmt->close();
exit;
