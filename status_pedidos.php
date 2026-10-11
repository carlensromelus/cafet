<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, private');
try {
    require_once __DIR__ . '/bootstrap.php';
    $cliente = current_user($conn);
    if (!$cliente) { http_response_code(401); echo json_encode(['erro'=>'Sessão encerrada.']); exit; }
    session_write_close();
    $stmt = $conn->prepare('SELECT id, status, atualizado_em FROM pedidos WHERE usuario_id = ? ORDER BY id DESC');
    $stmt->bind_param('i', $cliente['id']); $stmt->execute();
    echo json_encode(['pedidos'=>$stmt->get_result()->fetch_all(MYSQLI_ASSOC)], JSON_UNESCAPED_UNICODE);
} catch (Throwable $erro) {
    http_response_code(500); echo json_encode(['erro'=>'Confira a conexão e importe database/atualizar_site.sql.']);
}
