<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cliente extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Pedido_model');
		//$this->load->helper('url'); // Carrega o helper de URL
		//$this->load->library('session'); // Biblioteca de sessão
		$this->load->model('Produto_model');
		$this->load->library('cart');
		$this->load->library('template');
	}

	public function index()
	{
		$id_usuario = $this->session->userdata('id_usuario');
		$data['produtos'] = $this->Produto_model->get_all_products();
		$this->template->load('clientePaginaPrincipal', $data);
	}

	public function comprar_produto()
	{
		$id_produto = $this->input->post('id_produto');
		$quantidade = $this->input->post('quantidade');

		if (!$id_produto || !$quantidade) {
			echo json_encode(['success' => false, 'message' => 'ID do produto ou quantidade ausente!']);
			return;
		}

		$produto = $this->Produto_model->get_product_by_id($id_produto);

		if (!$produto) {
			echo json_encode(['success' => false, 'message' => 'Produto não encontrado!']);
			return;
		}

		if ($quantidade <= $produto['estoque']) {
			$novo_estoque = $produto['estoque'] - $quantidade;
			$this->Produto_model->update_stock($id_produto, $novo_estoque);

			$data = array(
				'id' => $produto['id_produto'],
				'qty' => $quantidade,
				'price' => $produto['preco'],
				'name' => $produto['nome'],
			);
			$this->cart->insert($data);

			echo json_encode([
				'success' => true,
				'cart_count' => $this->cart->total_items(),
				'new_stock' => $novo_estoque,
				'message' => 'Produto adicionado ao carrinho!'
			]);
		} else {
			echo json_encode([
				'success' => false,
				'message' => 'Quantidade maior que o estoque disponível!'
			]);
		}
	}

	public function update_cart()
	{
		$id_produto = $this->input->post('id_produto');
		$quantidade = $this->input->post('quantidade');

		$cart = $this->cart->contents();
		foreach ($cart as $item) {
			if ($item['id'] == $id_produto) {
				$dados = array(
					'rowid' => $item['rowid'],
					'qty'   => $quantidade
				);
				$this->cart->update($dados);
			}
		}

		echo json_encode(['success' => true, 'cart_count' => $this->cart->total_items()]);
	}

	public function remove_cart_item()
	{
		$id_produto = $this->input->post('id_produto');
		$quantidadeRemovida = 0;

		$cart = $this->cart->contents();
		foreach ($cart as $item) {
			if ($item['id'] == $id_produto) {
				$quantidadeRemovida = $item['qty'];
				$this->cart->remove($item['rowid']);
				break;
			}
		}

		$produto = $this->Produto_model->get_product_by_id($id_produto);
		$novo_estoque = $produto['estoque'] + $quantidadeRemovida;
		$this->Produto_model->update_stock($id_produto, $novo_estoque);

		echo json_encode([
			'success' => true,
			'cart_count' => $this->cart->total_items(),
			'novo_estoque' => $novo_estoque
		]);
	}

	public function get_cart_items()
	{
		$cart = $this->cart->contents();

		if (empty($cart)) {
			echo '<p class="text-center">' . htmlspecialchars('Carrinho vazio.') . '</p>';
			return;
		}

		foreach ($cart as $item) {
			echo '<li data-id="' . $item['id'] . '" class="cart-item">
                <p><strong>' . htmlspecialchars($item['name']) . '</strong></p>
                <p>R$ ' . number_format($item['price'], 2, ',', '.') . ' x 
                    <input type="number" class="update-cart" value="' . $item['qty'] . '" min="1" style="width: 50px;">
                </p>
                <button class="btn btn-danger btn-xs remove-cart-item" data-id="' . $item['id'] . '">Remover</button>
              </li>';
		}
	}
	public function checkout()
	{
		$data['cart_items'] = $this->cart->contents();

		if (empty($data['cart_items'])) {
			$data['mensagem'] = "Seu carrinho está vazio!";
		}

		$this->template->load('clienteCheckout', $data);
	}

	public function finalizar_pedido()
	{
		if (empty($this->cart->contents())) {
			redirect('cliente/checkout');
			return;
		}

		$id_usuario = $this->session->userdata('id_usuario');
		$id_cupom = NULL;

		// Criar um novo carrinho para a nova compra
		$carrinho_data = [
			'id_usuario' => $id_usuario
		];
		$this->db->insert('carrinho', $carrinho_data);
		$id_carrinho = $this->db->insert_id();

		$venda_data = [
			'id_carrinho' => $id_carrinho,
			'id_cupom'    => $id_cupom,
			'data_venda'  => date('Y-m-d H:i:s')
		];

		$this->db->insert('venda', $venda_data);
		$id_venda = $this->db->insert_id();

		foreach ($this->cart->contents() as $item) {
			$produto = $this->db->get_where('produto', ['id_produto' => $item['id']])->row();
			$preco_unitario = $produto->preco;

			$carrinho_item = [
				'id_carrinho' => $id_carrinho,
				'id_produto'  => $item['id'],
				'quantidade'  => $item['qty'],
				'preco_unitario' => $preco_unitario  // Inclui o preço unitário
			];

			$this->db->insert('carrinho_item', $carrinho_item);
		}

		$this->cart->destroy();

		redirect('cliente/checkout?status=success');
	}




	//public function pedidos()
	//{
	//	$data['cart_items'] = $this->cart->contents();

	//	if (empty($data['cart_items'])) {
	//		$data['mensagem'] = "Seu carrinho está vazio!";
	//	}

	//	$this->template->load('clientePedido', $data);
	//}

	public function pedidos()
	{
		$id_usuario = $this->session->userdata('id_usuario');

		if (!$id_usuario) {
			redirect('cliente/login');
			return;
		}

		$this->load->model('pedido_model');
		$data['pedidos'] = $this->pedido_model->get_pedidos_por_cliente($id_usuario);

		if (empty($data['pedidos'])) {
			log_message('debug', 'Nenhum pedido encontrado para o usuário ID: ' . $id_usuario);
		} else {
			log_message('debug', 'Pedidos carregados para o usuário ID: ' . $id_usuario);
		}

		$this->template->load('clientePedido', $data);
	}
	public function vendasDetalhadas($id_venda)
	{
		$id_usuario = $this->session->userdata('id_usuario');
		if (!$id_usuario) {
			redirect('cliente/login');
			return;
		}

		$this->load->model('pedido_model');

		$data['pedidos'] = $this->pedido_model->get_detalhes_pedido($id_venda, $id_usuario, true);

		if (empty($data['pedidos'])) {
			log_message('debug', 'Nenhuma venda encontrada no período.');
		} else {
			log_message('debug', 'Detalhe carregado com sucesso.');
		}

		$this->template->load('clientePedidoDetalhes', $data);
	}
}
