<?php

class Loja extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Produto_model');
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
}


