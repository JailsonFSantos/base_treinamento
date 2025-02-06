<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?= $title ?? 'Treinamento'; ?> </title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('assets/js/cliente_funcionalidades.js'); ?>"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd"
        crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #eee;
        }

        main {
            margin: 2rem;
        }
    </style>

    <script>
        function exibirAviso(aviso_texto, div_id, tipo_aviso = "ERRO") {
            $('#' + div_id).show();
            let avisoClass = tipo_aviso === "SUCESSO" ? "alert-success" : tipo_aviso === "AVISO" ? "alert-warning" : "alert-danger";
            let avisoHtml = `
                <div class="alert ${avisoClass} alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                    <p>${aviso_texto}</p>
                </div>`;

            $('#' + div_id).html(avisoHtml);

            setTimeout(() => {
                $('#' + div_id).html('');
            }, 4000);

            $('html, body').animate({
                scrollTop: $('#' + div_id).offset().top
            }, 0);
        }
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
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="glyphicon glyphicon-shopping-cart"></i>
                                <span id="quantidade_carrinho" class="badge badge-primary">
                                    <?= isset($this->cart) ? $this->cart->total_items() : 0; ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu" id="carrinho_dropdown">
                                <li>
                                    <p class="text-center">Carregando...</p>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <!-- Restaurando a parte do nome do usuário e logout -->
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

</body>

</html>
