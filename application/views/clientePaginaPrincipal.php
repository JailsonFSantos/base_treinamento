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

                            <!-- Formulário para adicionar ao carrinho -->
                            <div class="add-to-cart-form">
                                <div class="form-group">
                                    <label for="quantidade_<?= (int) $produto->id_produto; ?>">Quantidade:</label>
                                    <input type="number" name="quantidade" id="quantidade_<?= (int) $produto->id_produto; ?>"
                                        class="form-control" min="1" max="<?= (int) $produto->estoque; ?>" value="1" required>
                                </div>
                                <input type="hidden" name="id_produto" value="<?= (int) $produto->id_produto; ?>">
                                <button type="button" class="btn btn-primary btn-block add-to-cart" data-id="<?= (int) $produto->id_produto; ?>">Adicionar ao Carrinho</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="alert alert-warning text-center">Nenhum produto disponível no momento.</p>
    <?php endif; ?>
</div>

<!-- Adicionar o script JavaScript ao final da página -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Função para adicionar produto ao carrinho via AJAX
    $(document).on('click', '.add-to-cart', function () {
        var id_produto = $(this).data('id');  // Obtém o id do produto
        var quantidade = $('#quantidade_' + id_produto).val(); // Obtém a quantidade do input

        if (!id_produto || !quantidade) {
            alert("ID do produto ou quantidade ausente!");
            return;  // Interrompe o processo caso algum parâmetro esteja faltando
        }

        $.ajax({
            url: 'cliente/comprar_produto',  // Rota para o método no controlador
            type: 'POST',
            data: { id_produto: id_produto, quantidade: quantidade },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#quantidade_carrinho').text(response.cart_count); // Atualiza o ícone do carrinho
                    alert(response.message); // Mensagem de sucesso
                } else {
                    alert(response.message); // Mensagem de erro
                }
            },
            error: function () {
                alert('Erro ao adicionar produto ao carrinho!');
            }
        });
    });
});
</script>
