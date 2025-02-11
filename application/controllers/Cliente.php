<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cliente extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Produto_model');
		$this->load->library('cart');
	}

	public function index()
	{
		$id_usuario = $this->session->userdata('id_usuario');
		$data['produtos'] = $this->Produto_model->get_all_products();
		$data['tipo_acesso'] = $this->session->userdata('tipo_acesso');
		$data['homeUrl'] = '/';
		$data['nome_usuario'] = $this->session->userdata('nome_usuario');

		$data['conteudo'] = $this->load->view('clientePaginaPrincipal', $data, TRUE);

		$this->load->view('template/index', $data);
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
			echo '<p class="text-center">Carrinho vazio.</p>';
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
}
