<div class="container">
    <h1>Detalhes do Pedido</h1>

    <?php if (!empty($pedidos)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome do Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total do Item</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $produto): ?>
                    <tr>
                        <td><?= htmlspecialchars($produto['nome_produto']); ?></td>
                        <td><?= htmlspecialchars($produto['quantidade']); ?></td>
                        <td>R$ <?= number_format($produto['preco_unitario'], 2, ',', '.'); ?></td>
                        <td>R$ <?= number_format($produto['total_item'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma venda encontrada.</p>
    <?php endif; ?>

    <a href="<?= base_url('cliente/pedidos') ?>" class="btn btn-default">Voltar</a>
</div>