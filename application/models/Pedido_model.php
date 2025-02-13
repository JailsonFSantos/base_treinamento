<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedido_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Obtém todos os pedidos do cliente
    public function get_pedidos_por_cliente($id_usuario)
    {
        $this->db->select('
            venda.id_venda, 
            venda.data_venda, 
            SUM(carrinho_item.preco_unitario * carrinho_item.quantidade) as total
        ');
        $this->db->from('venda');
        $this->db->join('carrinho', 'venda.id_carrinho = carrinho.id_carrinho');
        $this->db->join('carrinho_item', 'carrinho.id_carrinho = carrinho_item.id_carrinho');
        $this->db->where('carrinho.id_usuario', $id_usuario);
        $this->db->group_by('venda.id_venda');
        $this->db->order_by('venda.data_venda', 'DESC');

        return  $this->db->get()->result_array();
    }

    public function get_vendas_loja($data_inicio = null, $data_fim = null)
    {
        $this->db->select('venda.id_venda, venda.data_venda, SUM(produto.preco * carrinho_item.quantidade) as total');
        $this->db->from('venda');
        $this->db->join('carrinho', 'venda.id_carrinho = carrinho.id_carrinho');
        $this->db->join('carrinho_item', 'carrinho.id_carrinho = carrinho_item.id_carrinho');
        $this->db->join('produto', 'carrinho_item.id_produto = produto.id_produto');

        if (!empty($data_inicio)) {
            $this->db->where('venda.data_venda >=', date('Y-m-d 00:00:00', strtotime($data_inicio)));
        }
        if (!empty($data_fim)) {
            $this->db->where('venda.data_venda <=', date('Y-m-d 23:59:59', strtotime($data_fim)));
        }

        $this->db->group_by('venda.id_venda');
        $this->db->order_by('venda.data_venda', 'DESC');

        return $this->db->get()->result_array();
    }


    // Obtém os detalhes de um pedido específico
    public function get_detalhes_pedido($id_venda, $id_usuario, $is_loja = false)
    {
        $this->db->select('produto.nome AS nome_produto, carrinho_item.quantidade, carrinho_item.preco_unitario, 
        (carrinho_item.preco_unitario * carrinho_item.quantidade) AS total_item');
        $this->db->from('venda');
        $this->db->join('carrinho', 'venda.id_carrinho = carrinho.id_carrinho');
        $this->db->join('carrinho_item', 'carrinho.id_carrinho = carrinho_item.id_carrinho');
        $this->db->join('produto', 'carrinho_item.id_produto = produto.id_produto');

        // Se não for loja, filtra pelos pedidos do cliente
        if (!$is_loja) {
            $this->db->where('carrinho.id_usuario', $id_usuario);
        }

        if ($id_venda) {
            $this->db->where('venda.id_venda', $id_venda); // Caso tenha ID de venda
        }

        return $this->db->get()->result_array();
    }


    public function get_vendas_com_lucro($data_inicio = NULL, $data_fim = NULL)
    {
        $this->db->select('
        venda.id_venda, 
        venda.data_venda, 
        SUM(carrinho_item.quantidade * (carrinho_item.preco_unitario - produto.custo)) as lucro_total,
        SUM(carrinho_item.quantidade * carrinho_item.preco_unitario) as total
    ');
        $this->db->from('venda');
        $this->db->join('carrinho', 'venda.id_carrinho = carrinho.id_carrinho');
        $this->db->join('carrinho_item', 'carrinho.id_carrinho = carrinho_item.id_carrinho');
        $this->db->join('produto', 'carrinho_item.id_produto = produto.id_produto');

        // No banco está formato DateTime
        if (!empty($data_inicio)) {
            $this->db->where('venda.data_venda >=', date('Y-m-d 00:00:00', strtotime($data_inicio)));
        }
        if (!empty($data_fim)) {
            $this->db->where('venda.data_venda <=', date('Y-m-d 23:59:59', strtotime($data_fim)));
        }

        $this->db->group_by('venda.id_venda');
        return $this->db->get()->result_array();
    }






    public function registrar_venda($id_usuario, $id_cupom = null)
    {

        $this->db->where('id_usuario', $id_usuario);
        $carrinho = $this->db->get('carrinho')->row();

        if (!$carrinho) {
            return false;
        }

        $dados_venda = [
            'id_carrinho' => $carrinho->id_carrinho,
            'id_cupom'    => $id_cupom,
            'data_venda'  => date('Y-m-d H:i:s'),
        ];

        // Insere a venda no banco
        $this->db->insert('venda', $dados_venda);
        return $this->db->insert_id();
    }
}
