<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedido_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Carrega o banco de dados
    }

    // Obtém todos os pedidos do cliente logado
    public function get_pedidos_por_cliente($id_usuario)
    {
        $this->db->select('venda.id_venda, venda.data_venda, SUM(produto.preco * carrinho_item.quantidade) as total');
        $this->db->from('venda');
        $this->db->join('carrinho', 'venda.id_carrinho = carrinho.id_carrinho');
        $this->db->join('carrinho_item', 'carrinho.id_carrinho = carrinho_item.id_carrinho');
        $this->db->join('produto', 'carrinho_item.id_produto = produto.id_produto');
        $this->db->where('carrinho.id_usuario', $id_usuario);
        $this->db->group_by('venda.id_venda');
        $this->db->order_by('venda.data_venda', 'DESC');

        $query = $this->db->get();
        return $query->result_array();
    }

    // Obtém os detalhes de um pedido específico
    public function get_detalhes_pedido($id_venda, $id_usuario)
    {
        $this->db->select('produto.nome, produto.preco, carrinho_item.quantidade, (produto.preco * carrinho_item.quantidade) as subtotal');
        $this->db->from('venda');
        $this->db->join('carrinho', 'venda.id_carrinho = carrinho.id_carrinho');
        $this->db->join('carrinho_item', 'carrinho.id_carrinho = carrinho_item.id_carrinho');
        $this->db->join('produto', 'carrinho_item.id_produto = produto.id_produto');
        $this->db->where('venda.id_venda', $id_venda);
        $this->db->where('carrinho.id_usuario', $id_usuario);

        $query = $this->db->get();
        return $query->result_array();
    }
    public function registrar_venda($id_usuario, $id_cupom = null)
    {
        // Obtém o carrinho do usuário
        $this->db->where('id_usuario', $id_usuario);
        $carrinho = $this->db->get('carrinho')->row();

        if (!$carrinho) {
            return false; // Se não houver carrinho, não faz a venda
        }

        $dados_venda = [
            'id_carrinho' => $carrinho->id_carrinho,
            'id_cupom'    => $id_cupom,
            'data_venda'  => date('Y-m-d H:i:s'),
        ];

        // Insere a venda no banco
        $this->db->insert('venda', $dados_venda);
        return $this->db->insert_id(); // Retorna o ID da venda criada
    }
}
