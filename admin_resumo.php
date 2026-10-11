<?php
function resumo_admin($conn) {
    $resumo = ['mensagens_nao_lidas'=>0,'mensagens_total'=>0,'pedidos_pendentes'=>0,'recebido'=>0,'em_preparo'=>0,'pronto'=>0,'entregue'=>0,'cancelado'=>0,'produtos'=>0];
    $r = $conn->query('SELECT COUNT(*) AS total, COALESCE(SUM(l.mensagem_id IS NULL),0) AS nao_lidas FROM mensagens m LEFT JOIN mensagens_lidas l ON l.mensagem_id=m.id')->fetch_assoc();
    $resumo['mensagens_total'] = (int)$r['total']; $resumo['mensagens_nao_lidas'] = (int)$r['nao_lidas'];
    foreach ($conn->query('SELECT status, COUNT(*) AS total FROM pedidos GROUP BY status') as $r) { $resumo[$r['status']] = (int)$r['total']; }
    $resumo['pedidos_pendentes'] = $resumo['recebido'] + $resumo['em_preparo'] + $resumo['pronto'];
    $resumo['produtos'] = (int)$conn->query('SELECT COUNT(*) AS total FROM produtos WHERE ativo=1')->fetch_assoc()['total'];
    return $resumo;
}
