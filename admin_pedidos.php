<?php
require_once __DIR__ . '/bootstrap.php';
require_admin($conn);
$statuses = ['recebido','em_preparo','pronto','entregue','cancelado'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $id = filter_var($_POST['pedido_id'] ?? null, FILTER_VALIDATE_INT);
    $status = (string)($_POST['status'] ?? '');
    if (!$id || $id < 1 || !in_array($status,$statuses,true)) $error = 'Dados inválidos.';
    else {
        try {
            $conn->begin_transaction();
            $stmt = $conn->prepare('SELECT status FROM pedidos WHERE id = ? FOR UPDATE');
            $stmt->bind_param('i',$id); $stmt->execute(); $order = $stmt->get_result()->fetch_assoc();
            if (!$order) throw new RuntimeException('Pedido não encontrado.');
            if ($order['status'] === 'cancelado' && $status !== 'cancelado') throw new RuntimeException('Pedido cancelado não pode ser reaberto.');
            if ($status === 'cancelado' && $order['status'] !== 'cancelado') {
                $stock = $conn->prepare('UPDATE produtos SET estoque = estoque + ? WHERE id = ?');
                $lines = $conn->prepare('SELECT produto_id,quantidade FROM itens_pedido WHERE pedido_id = ?');
                $lines->bind_param('i',$id); $lines->execute();
                foreach ($lines->get_result() as $line) if ($line['produto_id'] !== null) {
                    $pid=(int)$line['produto_id']; $qty=(int)$line['quantidade'];
                    $stock->bind_param('ii',$qty,$pid); $stock->execute();
                }
            }
            $update = $conn->prepare('UPDATE pedidos SET status = ? WHERE id = ?');
            $update->bind_param('si',$status,$id); $update->execute(); $conn->commit();
            header('Location: admin_pedidos.php'); exit;
        } catch (Throwable $e) { $conn->rollback(); $error = $e instanceof RuntimeException ? $e->getMessage() : 'Não foi possível atualizar o pedido.'; }
    }
}
$orders = $conn->query('SELECT p.*,u.nome AS nome_usuario,u.email FROM pedidos p JOIN usuarios u ON u.id=p.usuario_id ORDER BY p.id DESC LIMIT 100');
require __DIR__ . '/admin_layout.php'; require __DIR__ . '/admin_cards.php';
admin_inicio($conn, 'Pedidos');
?>
<?php if ($error): ?><p class="erro"><?= h($error) ?></p><?php endif; ?>
<p>Mostrando os 100 pedidos mais recentes. Os contadores consideram todos os pedidos.</p><div id="admin-lista-pedidos"><?php foreach ($orders as $o) { mostrar_pedido($o, $statuses); } ?></div>
<?php admin_fim(); ?>