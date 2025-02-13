<div class="container">
    <h1>Detalhe do Pedido</h1>

    <?php if (!empty($pedidos)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome do Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $venda): ?>
                    <tr>
                        <td><?= htmlspecialchars($venda['nome']); ?></td>
                        <td><?= htmlspecialchars($venda['quantidade']); ?></td>
                        <td>R$ <?= number_format($venda['preco'], 2, ',', '.'); ?></td>
                        <td>R$ <?= number_format($venda['subtotal'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Detalhes não encontrados.</p>
    <?php endif; ?>

    <a href="<?= base_url('loja/vendas') ?>" class="btn btn-default">Voltar</a>
</div>
