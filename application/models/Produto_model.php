<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produto_model extends CI_Model
{

    // Obtém todos os produtos de uma loja específica
    public function get_all_products_by_loja($id_usuario_loja)
    {

        return $this->db->get('produto')->result_array();
    }

    // Insere um novo produto no banco de dados
    public function insert_product($data)
    {
        return $this->db->insert('produto', $data);
    }

    // Obtém um produto específico pelo ID
    public function get_product_by_id($id_produto)
    {
        return $this->db->get_where('produto', array('id_produto' => $id_produto))->row_array();
    }

    // Atualiza um produto existente
    public function update_product($id_produto, $data)
    {
        $this->db->where('id_produto', $id_produto);
        return $this->db->update('produto', $data);
    }

    // Deleta um produto do banco de dados
    public function delete_product($id_produto)
    {
        $this->db->where('id_produto', $id_produto);
        return $this->db->delete('produto');
    }

    public function get_all_products()
    {
        $query = $this->db->get('produto');
        return $query->result();
    }

    public function update_stock($id_produto, $novo_estoque)
    {
        $this->db->where('id_produto', $id_produto);
        $this->db->update('produto', ['estoque' => $novo_estoque]);
    }
}
