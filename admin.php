<?php
require_once __DIR__ . '/bootstrap.php'; require_admin($conn);
require __DIR__ . '/admin_layout.php'; admin_inicio($conn, 'Dashboard');
$resumo = resumo_admin($conn);
$indicadores = ['mensagens_nao_lidas'=>['Mensagens não lidas','admin_mensagens.php'],'mensagens_total'=>['Todas as mensagens','admin_mensagens.php'],'pedidos_pendentes'=>['Pedidos pendentes','admin_pedidos.php'],'recebido'=>['Pedidos recebidos','admin_pedidos.php'],'em_preparo'=>['Em preparo','admin_pedidos.php'],'pronto'=>['Prontos','admin_pedidos.php'],'entregue'=>['Entregues','admin_pedidos.php'],'cancelado'=>['Cancelados','admin_pedidos.php'],'produtos'=>['Produtos ativos','admin_produtos.php']];
?>
<p>Pedidos pendentes são os recebidos, em preparo e prontos. Mensagens só deixam de contar como não lidas quando você marca como lida.</p>
<div class="resumos"><?php foreach ($indicadores as $chave=>$item): ?><a href="<?= $item[1] ?>"><?= $item[0] ?><b data-contador="<?= $chave ?>"><?= $resumo[$chave] ?></b></a><?php endforeach; ?></div>
<?php admin_fim(); ?>
