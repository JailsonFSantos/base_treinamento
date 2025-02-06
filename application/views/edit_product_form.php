
<h2 class="mb-4">Editar Produto</h2>

<?= form_open('loja/edit_product/' . $produto['id_produto'], ['class' => 'needs-validation']); ?>

<div class="mb-3">
    <label for="nome" class="form-label">Nome:</label>
    <input type="text" class="form-control" id="nome" name="nome" value="<?= set_value('nome', $produto['nome']); ?>"
        required>
</div>

<div class="mb-3">
    <label for="descricao" class="form-label">Descrição:</label>
    <textarea class="form-control" id="descricao" name="descricao" rows="4"
        required><?= set_value('descricao', $produto['descricao']); ?></textarea>
</div>

<div class="mb-3">
    <label for="preco" class="form-label">Preço:</label>
    <input type="text" class="form-control" id="preco" name="preco"
        value="<?= set_value('preco', $produto['preco']); ?>" required>
</div>

<div class="mb-3">
    <label for="estoque" class="form-label">Estoque:</label>
    <input type="text" class="form-control" id="estoque" name="estoque"
        value="<?= set_value('estoque', $produto['estoque']); ?>" required>
</div>

<div class="mb-3">
    <label for="custo" class="form-label">Custo:</label>
    <input type="text" class="form-control" id="custo" name="custo"
        value="<?= set_value('custo', $produto['custo']); ?>" required>
</div>

<button type="submit" class="btn btn-primary">Salvar</button>

<?= form_close(); ?>