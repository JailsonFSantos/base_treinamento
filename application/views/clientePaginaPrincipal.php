<div class="container">
    <h1>Produtos Disponíveis</h1>

    <?php if (!empty($produtos) && is_array($produtos)): ?>
        <div class="row">
            <?php foreach ($produtos as $produto): ?>
                <div class="col-md-4 col-sm-6 product-card">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="text-center"><?= htmlspecialchars($produto->nome); ?></h4>
                        </div>

                        <div class="panel-body">
                            <p><?= nl2br(htmlspecialchars($produto->descricao)); ?></p>
                            <p><strong>Preço:</strong> R$ <?= number_format((float) $produto->preco, 2, ',', '.'); ?></p>
                            <p><strong>Estoque:</strong> <span id="estoque_<?= (int) $produto->id_produto; ?>" class="estoque-produto">
                                    <?= (int) $produto->estoque; ?> unidades
                                </span></p>

                            <form class="add-to-cart-form" action="<?= base_url('cliente/comprar_produto') ?>" method="post">
                                <div class="form-group">
                                    <label for="quantidade_<?= (int) $produto->id_produto; ?>">Quantidade:</label>
                                    <input type="number" name="quantidade" id="quantidade_<?= (int) $produto->id_produto; ?>"
                                        class="form-control" min="1" max="<?= (int) $produto->estoque; ?>" value="1" required>
                                </div>
                                <input type="hidden" name="id_produto" value="<?= (int) $produto->id_produto; ?>">
                                <button type="submit" class="btn btn-primary btn-block add-to-cart">Adicionar ao Carrinho</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="alert alert-warning text-center">Nenhum produto disponível no momento.</p>
    <?php endif; ?>
</div>