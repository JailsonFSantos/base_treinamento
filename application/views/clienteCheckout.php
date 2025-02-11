<div class="container">
    <h1>Resumo da Compra</h1>

    <?php if (!empty($cart_items)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_geral = 0; ?>
                <?php foreach ($cart_items as $item): ?>
                    <?php $total = $item['qty'] * $item['price']; ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']); ?></td>
                        <td><?= $item['qty']; ?></td>
                        <td>R$ <?= number_format($item['price'], 2, ',', '.'); ?></td>
                        <td>R$ <?= number_format($total, 2, ',', '.'); ?></td>
                    </tr>
                    <?php $total_geral += $total; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total da Compra:</strong></td>
                    <td><strong>R$ <?= number_format($total_geral, 2, ',', '.'); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    <?php else: ?>
        <p class="alert alert-warning text-center">Seu carrinho está vazio!</p>
    <?php endif; ?>

    <a href="<?= base_url('cliente') ?>" class="btn btn-default">Continuar Comprando</a>
    <a href="<?= base_url('cliente/finalizar_pedido') ?>" class="btn btn-success">Confirmar Pedido</a>
</div>