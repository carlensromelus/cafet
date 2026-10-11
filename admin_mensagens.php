<?php
require_once __DIR__ . '/bootstrap.php'; require_admin($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf(); $id = (int)($_POST['id'] ?? 0); $acao = (string)($_POST['acao'] ?? '');
    if ($id < 1 || !in_array($acao,['lida','nao_lida','excluir'],true)) { http_response_code(400); exit('Dados inválidos.'); }
    if ($acao === 'lida') {
        $stmt = $conn->prepare('INSERT IGNORE INTO mensagens_lidas (mensagem_id) SELECT id FROM mensagens WHERE id=?');
    } elseif ($acao === 'nao_lida') { $stmt = $conn->prepare('DELETE FROM mensagens_lidas WHERE mensagem_id=?'); }
    else { $stmt = $conn->prepare('DELETE FROM mensagens WHERE id=?'); }
    $stmt->bind_param('i',$id); $stmt->execute(); header('Location: admin_mensagens.php'); exit;
}
require __DIR__ . '/admin_layout.php'; require __DIR__ . '/admin_cards.php';
admin_inicio($conn, 'Mensagens');
$mensagens = $conn->query('SELECT m.*, (l.mensagem_id IS NOT NULL) AS lida FROM mensagens m LEFT JOIN mensagens_lidas l ON l.mensagem_id=m.id ORDER BY lida ASC, m.id DESC');
?><p>A leitura é compartilhada entre os administradores. Use “Marcar como lida” depois de ler a mensagem.</p><div id="admin-lista-mensagens"><?php foreach ($mensagens as $m) { mostrar_mensagem($m); } ?></div><?php admin_fim(); ?>
