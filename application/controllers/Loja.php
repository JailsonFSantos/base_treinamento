<?php

class Loja extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Produto_model');
		$this->load->library('template'); // Carrega a biblioteca Template
		$this->load->model('Pedido_model'); // Certifique-se de carregar o model correto
	}


	public function index()
	{
		$id_usuario = $this->session->userdata('id_usuario');
		$data['produtos'] = $this->Produto_model->get_all_products_by_loja($id_usuario);
		$data['tipo_acesso'] = $this->session->userdata('tipo_acesso');
		$data['homeUrl'] = '/';
		$data['nome_usuario'] = $this->session->userdata('nome_usuario');

		$data['conteudo'] = $this->load->view('lojaPaginaPrincipal', $data, TRUE);

		$this->load->view('template/index', $data);
	}


	public function add_product()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nome', 'Nome', 'required');
		$this->form_validation->set_rules('preco', 'Preço', 'required|numeric');
		$this->form_validation->set_rules('estoque', 'Estoque', 'required|integer');
		$this->form_validation->set_rules('custo', 'Custo', 'required|numeric');

		if ($this->form_validation->run() == FALSE) {
			$data['title'] = 'Adicionar Produto';
			$data['conteudo'] = $this->load->view('add_product_form', $data, TRUE);
			$this->load->view('template/index', $data);
		} else {
			$id_usuario = $this->session->userdata('id_usuario');
			$productData = array(
				'id_usuario_loja' => $id_usuario,
				'nome' => $this->input->post('nome'),
				'descricao' => $this->input->post('descricao'),
				'preco' => $this->input->post('preco'),
				'estoque' => $this->input->post('estoque'),
				'custo' => $this->input->post('custo')
			);

			$this->Produto_model->insert_product($productData);
			redirect('loja');
		}
	}



	public function edit_product($id_produto)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nome', 'Nome', 'required');
		$this->form_validation->set_rules('preco', 'Preço', 'required|numeric');
		$this->form_validation->set_rules('estoque', 'Estoque', 'required|integer');
		$this->form_validation->set_rules('custo', 'Custo', 'required|numeric');


		$data['produto'] = $this->Produto_model->get_product_by_id($id_produto);


		$data['title'] = 'Editar Produto';

		if ($this->form_validation->run() == FALSE) {
			$data['conteudo'] = $this->load->view('edit_product_form', $data, true);
			$this->load->view('template/index', $data);
		} else {
			$productData = array(
				'nome' => $this->input->post('nome'),
				'descricao' => $this->input->post('descricao'),
				'preco' => $this->input->post('preco'),
				'estoque' => $this->input->post('estoque'),
				'custo' => $this->input->post('custo')
			);

			$this->Produto_model->update_product($id_produto, $productData);
			redirect('loja');
		}
	}


	public function delete_product($id_produto)
	{
		$id_usuario = $this->session->userdata('id_usuario');
		$produto = $this->Produto_model->get_product_by_id($id_produto);
		if ($produto && $produto['id_usuario_loja'] == $id_usuario) {
			$this->Produto_model->delete_product($id_produto);
		} else {
			$this->session->set_flashdata('error', 'Você não tem permissão para deletar este produto.');
		}

		redirect('loja');
	}

	public function vendas()
	{
		$id_usuario = $this->session->userdata('id_usuario');
		if (!$id_usuario) {
			redirect('cliente/login');
			return;
		}

		$data_inicio = $this->input->get('data_inicio');
		$data_fim = $this->input->get('data_fim');

		$this->load->model('pedido_model');

		$data['pedidos'] = $this->pedido_model->get_vendas_com_lucro($data_inicio, $data_fim);

		if (empty($data['pedidos'])) {
			log_message('debug', 'Nenhuma venda encontrada no período.');
		} else {
			log_message('debug', 'Vendas carregadas com sucesso.');
		}

		$this->template->load('lojaPedidos', $data);
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

		$this->template->load('lojaPedidosDetalhes', $data);
	}
}
