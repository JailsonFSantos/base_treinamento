$(document).ready(function() {
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();

        let form = $(this).closest('form');
        let formData = form.serialize();
        let estoqueLabel = form.find('.estoque-produto'); 

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#quantidade_carrinho').text(response.cart_count);
                    estoqueLabel.text(response.novo_estoque + " unidades"); 
                    carregarCarrinho(); 
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Erro ao adicionar produto ao carrinho.');
            }
        });
    });

    function carregarCarrinho() {
        $.ajax({
            url: 'cliente/get_cart_items',
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#carrinho_dropdown').html(response);
            },
            error: function() {
                $('#carrinho_dropdown').html('<li><p class="text-center">Erro ao carregar carrinho.</p></li>');
            }
        });
    }

    
    $('#icone_carrinho').parent().on('mouseenter', function() {
        carregarCarrinho();
    });

    $(document).on('change', '.update-cart', function() {
        let row = $(this).closest('li');
        let id_produto = row.data('id');
        let quantidade = $(this).val();
        let estoqueLabel = $('#estoque_' + id_produto); 
        
        $.ajax({
            url: 'cliente/update_cart',
            type: 'POST',
            data: { id_produto: id_produto, quantidade: quantidade },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#quantidade_carrinho').text(response.cart_count); 
                    estoqueLabel.text(response.novo_estoque + " unidades"); 
                    carregarCarrinho(); 
                } else {
                    alert(response.message);
                }
            }
        });
    });

    $(document).on('click', '.remove-cart-item', function() {
        let id_produto = $(this).data('id');
        let estoqueLabel = $('#estoque_' + id_produto); 
    
        $.ajax({
            url: 'cliente/remove_cart_item',
            type: 'POST',
            data: { id_produto: id_produto },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#quantidade_carrinho').text(response.cart_count); 
                    estoqueLabel.text(response.novo_estoque + " unidades");
                    carregarCarrinho(); 
                }
            }
        });
    });
});
