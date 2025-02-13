<div class="container">
    <h1>Vendas da Loja</h1>

    <form method="GET" action="<?= base_url('loja/vendas') ?>" class="form-inline">
        <div class="form-group">
            <label for="data_inicio">De:</label>
            <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?= isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '' ?>">
        </div>
        <div class="form-group">
            <label for="data_fim">Até:</label>
            <input type="date" id="data_fim" name="data_fim" class="form-control" value="<?= isset($_GET['data_fim']) ? $_GET['data_fim'] : '' ?>">
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>

    <hr>

    <?php if (!empty($pedidos)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Número do Pedido</th>
                    <th>Data</th>
                    <th>Valor</th>
                    <th>Lucro</th> <!-- Coluna para o lucro -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $venda): ?>
                    <tr>
                        <td><?= htmlspecialchars($venda['id_venda']); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($venda['data_venda'])); ?></td>
                        <td>R$ <?= number_format($venda['total'], 2, ',', '.'); ?></td>
                        <td>R$ <?= number_format($venda['lucro_total'], 2, ',', '.'); ?></td> 
                        <td><a href="<?= base_url('loja/vendas/detalhes/' . $venda['id_venda']) ?>" class="btn btn-default">Detalhes</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma venda encontrada no período selecionado.</p>
    <?php endif; ?>

    <a href="<?= base_url('loja') ?>" class="btn btn-default">Voltar</a>
</div>
