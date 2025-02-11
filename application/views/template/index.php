<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?= $title ?? 'Treinamento'; ?> </title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>

    <style>
        body {
            background-color: #eee;
        }

        main {
            margin: 2rem;
        }
    </style>

    <script>
        $(document).ready(function() {
            // 🛒 Abrir o modal do carrinho e carregar os itens
            $('#abrirCarrinho').on('click', function() {
                carregarCarrinho();
                $('#modalCarrinho').modal('show');
            });

            // 🛍️ Função de adicionar produto ao carrinho
            $(document).on('click', '.add-to-cart', function() {
                let id_produto = $(this).data('id');
                let quantidade = 1; // ou qualquer valor desejado

                $.ajax({
                    url: 'cliente/comprar_produto',
                    type: 'POST',
                    data: {
                        id_produto: id_produto,
                        quantidade: quantidade
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#quantidade_carrinho').text(response.cart_count); // Atualiza o ícone do carrinho

                            // Verifica se há redirecionamento
                            if (response.redirect_url) {
                                window.location.href = response.redirect_url; // Redireciona para a página de compra
                            }
                        } else {
                            alert(response.message); // Exibe mensagem de erro
                        }
                    },
                    error: function() {
                        alert('Erro ao adicionar produto ao carrinho!');
                    }
                });
            });

            // 🔄 Atualizar a quantidade de um item no carrinho
            $(document).on('change', '.update-cart', function() {
                let row = $(this).closest('li');
                let id_produto = row.data('id');
                let quantidade = $(this).val();

                $.ajax({
                    url: 'cliente/update_cart',
                    type: 'POST',
                    data: {
                        id_produto: id_produto,
                        quantidade: quantidade
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#quantidade_carrinho').text(response.cart_count);
                            carregarCarrinho(); // Atualiza o modal sem fechar
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            // ❌ Remover item do carrinho
            $(document).on('click', '.remove-cart-item', function() {
                let id_produto = $(this).data('id');

                $.ajax({
                    url: 'cliente/remove_cart_item',
                    type: 'POST',
                    data: {
                        id_produto: id_produto
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#quantidade_carrinho').text(response.cart_count);
                            carregarCarrinho(); // Atualiza o modal sem fechar
                        } else {
                            alert('Erro ao remover item.');
                        }
                    }
                });
            });

            // 📦 Função para carregar o carrinho
            function carregarCarrinho() {
                $.ajax({
                    url: 'cliente/get_cart_items',
                    type: 'GET',
                    dataType: 'html',
                    success: function(response) {
                        $('#carrinho_itens').html(response);
                    },
                    error: function() {
                        $('#carrinho_itens').html('<p class="text-center text-danger">Erro ao carregar o carrinho.</p>');
                    }
                });
            }
        });
    </script>

</head>

<body>

    <?php if (isset($tipo_acesso)): ?>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?= $homeUrl ?? '#' ?>">HOME</a>
                </div>

                <ul class="nav navbar-nav navbar-right">
                    <?php if ($tipo_acesso == '1'): ?>
                        <li>
                            <a id="abrirCarrinho" data-toggle="modal" data-target="#modalCarrinho" style="cursor: pointer;">
                                <i class="glyphicon glyphicon-shopping-cart"></i>
                                <span id="quantidade_carrinho" class="badge badge-primary">
                                    <?= isset($this->cart) ? $this->cart->total_items() : 0; ?>
                                </span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                            aria-expanded="false"><?= $nome_usuario ?? 'Usuário' ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php if ($tipo_acesso == '1'): ?>
                                <li><a href="#">Pedidos</a></li>
                            <?php elseif ($tipo_acesso == '2'): ?>
                                <li><a href="#">Produtos</a></li>
                                <li><a href="#">Vendas</a></li>
                            <?php endif; ?>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?= base_url('login/logout') ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    <?php endif; ?>

    <main>
        <?= $conteudo ?? '' ?>
    </main>

    <!-- Modal do Carrinho -->
    <div id="modalCarrinho" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Seu Carrinho</h4>
                </div>
                <div class="modal-body">
                    <ul id="carrinho_itens" class="list-unstyled">
                        <li class="text-center">Carregando...</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <a href="cliente/checkout" class="btn btn-success">Finalizar Compra</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>