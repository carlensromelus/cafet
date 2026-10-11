<?php
require_once __DIR__ . '/admin_resumo.php';
function admin_inicio($conn, $titulo) {
    try { $resumo = resumo_admin($conn); }
    catch (Throwable $erro) { exit('Importe database/atualizar_site.sql no banco lumora_cafe_v2 pelo phpMyAdmin para ativar o painel.'); }
    $ultimoPedido = (int)$conn->query('SELECT COALESCE(MAX(id),0) AS id FROM pedidos')->fetch_assoc()['id'];
    $ultimaMensagem = (int)$conn->query('SELECT COALESCE(MAX(id),0) AS id FROM mensagens')->fetch_assoc()['id'];
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= h($titulo) ?> | Lumora</title><link rel="stylesheet" href="assets/css/pedidos.css?v=20261011"><link rel="stylesheet" href="assets/css/admin_paginas.css?v=20261011"></head><body>
<header class="admin-menu"><strong>☕ Lumora · Administração</strong><nav>
<a href="admin.php">Dashboard</a><a href="admin_produtos.php">Produtos <b data-contador="produtos"><?= $resumo['produtos'] ?></b></a>
<a href="admin_pedidos.php">Pedidos pendentes <b data-contador="pedidos_pendentes"><?= $resumo['pedidos_pendentes'] ?></b></a>
<a href="admin_mensagens.php">Mensagens não lidas <b data-contador="mensagens_nao_lidas"><?= $resumo['mensagens_nao_lidas'] ?></b></a>
<a href="index.php">Ver site</a><a href="logout.php">Sair</a></nav></header><main><h1><?= h($titulo) ?></h1>
<section id="admin-avisos" data-pedidos="<?= $ultimoPedido ?>" data-mensagens="<?= $ultimaMensagem ?>"><p id="admin-conexao" role="status">Atualização automática a cada 5 segundos.</p><p>Novos pedidos: <b id="avisos-pedidos">0</b> · Novas mensagens: <b id="avisos-mensagens">0</b> <button id="limpar-avisos" type="button">Limpar avisos</button></p><div id="admin-eventos" aria-live="polite"></div></section>
<?php }
function admin_fim() { ?><script src="assets/js/admin_automatico.js?v=20261011"></script></main></body></html><?php }
