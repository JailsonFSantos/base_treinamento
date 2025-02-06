<table class="table">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preço</th>
            <th>Total</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->cart->contents() as $item): ?>
            <tr data-id="<?= $item['id']; ?>">
                <td><?= $item['name']; ?></td>
                <td>
                    <input type="number" class="update-cart form-control" value="<?= $item['qty']; ?>" min="1">
                </td>
                <td>R$ <?= number_format($item['price'], 2, ',', '.'); ?></td>
                <td>R$ <?= number_format($item['subtotal'], 2, ',', '.'); ?></td>
                <td>
                    <button class="remove-cart-item btn btn-danger" data-id="<?= $item['id']; ?>">Remover</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<button class="btn btn-success">Confirmar Compra</button>