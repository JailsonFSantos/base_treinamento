<h2>Gerenciar Produtos</h2>


<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= htmlspecialchars($this->session->flashdata('success')); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('error')); ?></div>
<?php endif; ?>


<a href="<?= base_url('loja/add_product'); ?>" class="btn btn-primary mb-3">Adicionar Produto</a>


<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Custo</th>
                <th>Lucro Esperado</th> 
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($produtos) && is_array($produtos)): ?>
                <?php foreach ($produtos as $produto): 
                    $preco = (float) $produto['preco'];
                    $custo = (float) $produto['custo'];
                    $lucro = $preco - $custo;
                    $lucro_formatado = number_format($lucro, 2, ',', '.');
                ?>
                    <tr>
                        <td><?= htmlspecialchars($produto['nome']); ?></td>
                        <td><?= nl2br(htmlspecialchars($produto['descricao'])); ?></td>
                        <td>R$ <?= number_format($preco, 2, ',', '.'); ?></td>
                        <td><?= (int) $produto['estoque']; ?></td>
                        <td>R$ <?= number_format($custo, 2, ',', '.'); ?></td>
                        <td style="color: <?= ($lucro >= 0) ? 'green' : 'red'; ?>;">
                            R$ <?= $lucro_formatado; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('loja/edit_product/' . (int) $produto['id_produto']); ?>"
                                class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= base_url('loja/delete_product/' . (int) $produto['id_produto']); ?>"
                                onclick="return confirm('Tem certeza que deseja deletar este produto?');"
                                class="btn btn-danger btn-sm">Deletar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Nenhum produto encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
