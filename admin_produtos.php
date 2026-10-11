<?php
require_once __DIR__ . '/bootstrap.php';
require_admin($conn);
$error = '';
function product_image(array $file, string $old = ''): string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return $old;
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 3 * 1024 * 1024) throw new RuntimeException('Imagem inválida ou maior que 3 MB.');
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $ext = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime] ?? null;
    if (!$ext || !getimagesize($file['tmp_name'])) throw new RuntimeException('Envie uma imagem JPG, PNG ou WebP.');
    $path = 'img/' . bin2hex(random_bytes(12)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/' . $path)) throw new RuntimeException('Não foi possível salvar a imagem.');
    return $path;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    try {
        $action = (string)($_POST['acao'] ?? '');
        if (in_array($action, ['cadastrar','atualizar'], true)) {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim((string)($_POST['nome'] ?? ''));
            $description = trim((string)($_POST['descricao'] ?? ''));
            $price = filter_var($_POST['preco'] ?? null, FILTER_VALIDATE_FLOAT);
            $stock = filter_var($_POST['estoque'] ?? null, FILTER_VALIDATE_INT);
            $category = trim((string)($_POST['categoria'] ?? 'Cafés'));
            if ($name === '' || mb_strlen($name) > 150 || $price === false || $price <= 0 || $stock === false || $stock < 0 || $stock > 99999 || $category === '' || mb_strlen($category) > 60) throw new RuntimeException('Confira nome, preço, estoque e categoria.');
            $old = '';
            if ($action === 'atualizar') {
                $find = $conn->prepare('SELECT imagem FROM produtos WHERE id = ?');
                $find->bind_param('i',$id); $find->execute();
                $existing = $find->get_result()->fetch_assoc();
                if (!$existing) throw new RuntimeException('Produto não encontrado.');
                $old = $existing['imagem'] ?? '';
            }
            $image = product_image($_FILES['imagem'] ?? [], $old);
            if ($action === 'cadastrar') {
                $stmt = $conn->prepare('INSERT INTO produtos (nome,descricao,preco,imagem,estoque,categoria) VALUES (?,?,?,?,?,?)');
                $stmt->bind_param('ssdsis',$name,$description,$price,$image,$stock,$category);
            } else {
                $stmt = $conn->prepare('UPDATE produtos SET nome=?,descricao=?,preco=?,imagem=?,estoque=?,categoria=? WHERE id=?');
                $stmt->bind_param('ssdsisi',$name,$description,$price,$image,$stock,$category,$id);
            }
            $stmt->execute();
        } elseif ($action === 'excluir') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id < 1) throw new RuntimeException('Registro inválido.');
            // Mantém produtos históricos vinculados a pedidos.
            $sql = 'UPDATE produtos SET ativo = 0 WHERE id = ?';
            $stmt = $conn->prepare($sql); $stmt->bind_param('i',$id); $stmt->execute();
        }
        header('Location: admin_produtos.php'); exit;
    } catch (Throwable $e) { $error = $e instanceof RuntimeException ? $e->getMessage() : 'Não foi possível salvar. Confira a migração SQL.'; }
}
$produtoEditar = null;
if (isset($_GET['editar'])) {
    $id = (int)$_GET['editar'];
    $stmt = $conn->prepare('SELECT * FROM produtos WHERE id = ? AND ativo = 1');
    $stmt->bind_param('i',$id); $stmt->execute(); $produtoEditar = $stmt->get_result()->fetch_assoc();
}
$produtos = $conn->query('SELECT * FROM produtos WHERE ativo = 1 ORDER BY id DESC');
require __DIR__ . '/admin_layout.php';
admin_inicio($conn, 'Produtos');
?>
<?php if ($error): ?><p class="erro"><?= h($error) ?></p><?php endif; ?>
    <div class="form-box">
        <h2>Adicionar Produto</h2>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= h(csrf()) ?>">
            <input type="hidden" name="acao" value="<?= $produtoEditar ? 'atualizar' : 'cadastrar' ?>">
            <input type="hidden" name="id" value="<?php echo $produtoEditar ? $produtoEditar['id'] : ''; ?>">
            
            <input type="text" name="nome" placeholder="Nome" required value="<?php echo $produtoEditar ? htmlspecialchars($produtoEditar['nome']) : ''; ?>">
            <textarea name="descricao" placeholder="Descrição" required><?php echo $produtoEditar ? htmlspecialchars($produtoEditar['descricao']) : ''; ?></textarea>
            <input type="number" step="0.01" min="0.01" name="preco" placeholder="Preço" required value="<?php echo $produtoEditar ? htmlspecialchars($produtoEditar['preco']) : ''; ?>">
            <input type="number" name="estoque" min="0" max="99999" placeholder="Estoque" required value="<?= $produtoEditar ? (int)$produtoEditar['estoque'] : 20 ?>">
            <input type="text" name="categoria" maxlength="60" placeholder="Categoria" required value="<?= h($produtoEditar['categoria'] ?? 'Cafés') ?>">
            <input type="file" name="imagem" accept="image/jpeg,image/png,image/webp">
            <?php if ($produtoEditar && !empty($produtoEditar['imagem'])) { ?>
                <p>Imagem atual: <?= h(basename($produtoEditar['imagem'])) ?></p>
            <?php } ?>

            <button >
                <?php echo $produtoEditar ? 'Atualizar' : 'Cadastrar'; ?>
            </button>

            <?php if ($produtoEditar) { ?>
                <a href="admin_produtos.php" class="btn-editar" style="margin-left: 10px;">Cancelar</a>
            <?php } ?>
        </form>
    </div>

    <!-- ===================== -->
    <!-- LISTA PRODUTOS -->
    <!-- ===================== -->
    <div class="lista" id="produtos">
        <h2>Produtos ☕</h2>

        <?php while($p = $produtos->fetch_assoc()) { ?>
            <div class="card">
                <div class="info">
                    <h3><?= h($p["nome"]) ?></h3>
                    <p><?= h($p["descricao"]) ?></p>
                    <p class="preco"><?= money($p["preco"]) ?> · estoque: <?= (int)$p["estoque"] ?> · <?= h($p["categoria"]) ?></p>
                </div>

                <?php if (!empty($p["imagem"])) { ?>
                    <img src="<?php echo htmlspecialchars($p["imagem"]); ?>" alt="<?php echo htmlspecialchars($p["nome"]); ?>">
                <?php } ?>

                <a class="btn-editar" href="?editar=<?php echo $p["id"]; ?>">
                    Editar
                </a>
                <form method="post" onsubmit="return confirm('Ocultar este produto do cardápio?')"><input type="hidden" name="csrf" value="<?= h(csrf()) ?>"><input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="excluir">Ocultar</button></form>
            </div>
        <?php } ?>
    </div>

<?php admin_fim(); ?>