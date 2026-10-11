<?php
header('Content-Type: application/json; charset=utf-8'); header('Cache-Control: no-store, private');
try {
    require_once __DIR__ . '/bootstrap.php'; $usuario = current_user($conn);
    if (!$usuario) { http_response_code(401); echo json_encode(['erro'=>'Sessão encerrada.']); exit; }
    if ($usuario['role'] !== 'admin') { http_response_code(403); echo json_encode(['erro'=>'Acesso restrito.']); exit; }
    require __DIR__ . '/admin_resumo.php'; require __DIR__ . '/admin_cards.php'; csrf();
    $resumo = resumo_admin($conn);
    $de_pedidos = max(0,(int)($_GET['pedidos'] ?? 0)); $de_mensagens = max(0,(int)($_GET['mensagens'] ?? 0));
    $ultimoPedido = (int)$conn->query('SELECT COALESCE(MAX(id),0) AS id FROM pedidos')->fetch_assoc()['id'];
    $ultimaMensagem = (int)$conn->query('SELECT COALESCE(MAX(id),0) AS id FROM mensagens')->fetch_assoc()['id'];
    $eventos = [];
    foreach ($conn->query("SELECT id FROM pedidos WHERE id > $de_pedidos AND id <= $ultimoPedido ORDER BY id DESC LIMIT 50") as $r) $eventos[] = ['tipo'=>'pedido','texto'=>'Novo pedido #' . $r['id']];
    foreach ($conn->query("SELECT id, assunto FROM mensagens WHERE id > $de_mensagens AND id <= $ultimaMensagem ORDER BY id DESC LIMIT 50") as $r) $eventos[] = ['tipo'=>'mensagem','texto'=>'Nova mensagem #' . $r['id'] . ': ' . $r['assunto']];
    $cards=[]; $html=''; $pagina=(string)($_GET['pagina'] ?? '');
    if ($pagina === 'pedidos') {
        $statuses=['recebido','em_preparo','pronto','entregue','cancelado'];
        foreach ($conn->query('SELECT p.*,u.nome AS nome_usuario,u.email FROM pedidos p JOIN usuarios u ON u.id=p.usuario_id ORDER BY p.id DESC LIMIT 100') as $o) {
            ob_start(); mostrar_pedido($o,$statuses); $cards[]=['id'=>(int)$o['id'],'html'=>ob_get_clean()];
        }
    }
    if ($pagina === 'mensagens') {
        ob_start(); foreach ($conn->query('SELECT m.*, (l.mensagem_id IS NOT NULL) AS lida FROM mensagens m LEFT JOIN mensagens_lidas l ON l.mensagem_id=m.id ORDER BY lida ASC,m.id DESC') as $m) mostrar_mensagem($m); $html=ob_get_clean();
    }
    session_write_close();
    echo json_encode(['resumo'=>$resumo,'pedidos'=>$cards,'mensagens_html'=>$html,'eventos'=>$eventos,'ultimo_pedido'=>max($de_pedidos,$ultimoPedido),'ultima_mensagem'=>max($de_mensagens,$ultimaMensagem)],JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Throwable $erro) { http_response_code(500); echo json_encode(['erro'=>'Confira a conexão e importe database/atualizar_site.sql.']); }
