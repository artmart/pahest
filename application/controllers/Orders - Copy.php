<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        $query = $this->db->get('products');
        $products = $query->result();
        $query = $this->db->get('clients');
        $clients = $query->result();
        $data = array('products' => $products, 'clients' => $clients);
        $this->load->view('orders', $data);
    }
    public function new_order()
    {
        $post_number = $this->input->post('post_number');
        $post_number = intval($post_number);
        for($i = 1; $i <= $post_number; $i++)
        {
            if($this->input->post('new_client'.$i))
            {
                $data = array(
                    'name' => $this->input->post('new_client_name'.$i) ,
                    'own' => $this->input->post('own_client'.$i)?'yes':'no' ,
                    'debt' => 0
                );
                $this->db->insert('clients', $data);
                $client_id = $this->db->insert_id();
            }
            else
            {
                $client_id = $this->input->post('client_to_pick'.$i);
            }
            $daily_sale = "sale";
            if($this->input->post('daily'.$i))
            {
                $sale_price = 0;
                $daily_sale = "daily";
                $daily_price = $this->input->post('product_price'.$i);
                $this->db->where('id', $this->input->post('product_to_pick'.$i));
                $this->db->select('daily_order');
                $this->db->from('products');
                $query = $this->db->get();
                $daily_order = $query->result();
                $daily_order = intval($daily_order[0]->daily_order);
                $new_daily_order = $daily_order + $this->input->post('product_quantity'.$i);
                $this->db->where('id', $this->input->post('product_to_pick'.$i));
                $this->db->update('products', array('daily_order' => $new_daily_order));
            }
            else
            {
                $daily_price = 0;
                $sale_price = $this->input->post('product_price'.$i);
                $this->db->where('id', $this->input->post('product_to_pick'.$i));
                $this->db->select('quantity, sold_quantity, new_quantity');
                $this->db->from('products');
                $query = $this->db->get();
                $res = $query->result();
                $sold = intval($res[0]->sold_quantity);
                $quantity = intval($res[0]->quantity);
                $new_product_quantity = intval($res[0]->new_quantity);
                if($quantity < intval($this->input->post('product_quantity'.$i)))
                {
                    $new_product_quantity = $new_product_quantity - (intval($this->input->post('product_quantity'.$i)) - $quantity);
                    $new_quantity = 0;
                }
                else
                {
                    $new_quantity = $quantity - intval($this->input->post('product_quantity'.$i));
                }
                
                $new_sold = $sold + intval($this->input->post('product_quantity'.$i));
                $this->db->where('id', $this->input->post('product_to_pick'.$i));
                $this->db->update('products', array('quantity' => $new_quantity, 'sold_quantity' => $new_sold, 'new_quantity' => $new_product_quantity));
            }
            $date = str_replace('/', '-', $this->input->post('date'.$i));
            $data = array(
                'client_id' => $client_id ,
                'product_id' => $this->input->post('product_to_pick'.$i) ,
                'product_quantity' => $this->input->post('product_quantity'.$i),
                'sale_price' => $sale_price,
                'daily_price' => $daily_price,
                'daily_sale' => $daily_sale,
                'date' => date('Y-m-d', strtotime($date))
            );
            $this->db->insert('orders', $data);
        }
        redirect('orders');
    }

    public function update_order()
    {
        if($this->input->post('type') == 'Օրավարձ')
        {
            $sale_price = 0;
            $daily_sale = "daily";
            $daily_price = $this->input->post('price');
            $this->db->where('id', $this->input->post('product_id'));
            $this->db->select('daily_order');
            $this->db->from('products');
            $query = $this->db->get();
            $daily_order = $query->result();
            $daily_order = intval($daily_order[0]->daily_order);

            $this->db->where('id', $this->input->post('id'));
            $this->db->select('daily_sale, product_quantity');
            $this->db->from('orders');
            $query = $this->db->get();
            $res = $query->result();
            $d_s = $res[0]->daily_sale;
            $old_order = intval($res[0]->product_quantity);
            if($d_s == "daily")
            {
                $new_daily_order = $daily_order - $old_order + $this->input->post('quantity');
                $this->db->where('id', $this->input->post('product_id'));
                $this->db->update('products', array('daily_order' => $new_daily_order));
            }
            else
            {
                $this->db->where('id', $this->input->post('product_id'));
                $this->db->select('quantity');
                $this->db->from('products');
                $query = $this->db->get();
                $quantity = $query->result();
                $quantity = intval($quantity[0]->quantity);

                $new_daily_order = $daily_order + $this->input->post('quantity');
                $new_quantity = $quantity + $old_order;
                $this->db->where('id', $this->input->post('product_id'));
                $this->db->update('products', array('daily_order' => $new_daily_order, 'quantity' => $new_quantity));
            }
        }
        else
        {
            $daily_sale = "sale";
            $daily_price = 0;
            $sale_price = $this->input->post('price');
            $this->db->where('id', $this->input->post('product_id'));
            $this->db->select('quantity');
            $this->db->from('products');
            $query = $this->db->get();
            $quantity = $query->result();
            $quantity = intval($quantity[0]->quantity);

            $this->db->where('id', $this->input->post('id'));
            $this->db->select('daily_sale, product_quantity');
            $this->db->from('orders');
            $query = $this->db->get();
            $res = $query->result();
            $d_s = $res[0]->daily_sale;
            $old_order = intval($res[0]->product_quantity);
            if($d_s == "sale")
            {
                $new_quantity = $quantity + $old_order - $this->input->post('quantity');
                $this->db->where('id', $this->input->post('product_id'));
                $this->db->update('products', array('quantity' => $new_quantity));
            }
            else
            {
                $this->db->where('id', $this->input->post('product_id'));
                $this->db->select('daily_order');
                $this->db->from('products');
                $query = $this->db->get();
                $daily_order = $query->result();
                $daily_order = intval($daily_order[0]->daily_order);

                $new_daily_order = $daily_order - $old_order;
                $new_quantity = $quantity - $this->input->post('quantity');
                $this->db->where('id', $this->input->post('product_id'));
                $this->db->update('products', array('daily_order' => $new_daily_order, 'quantity' => $new_quantity));
            }
        }
        $date = str_replace('/', '-', $this->input->post('date'));
        $data = array(
            'product_quantity' => $this->input->post('quantity'),
            'sale_price' => $sale_price,
            'daily_price' => $daily_price,
            'daily_sale' => $daily_sale,
            'date' => date('Y-m-d', strtotime($date))
        );

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('orders', $data);
    }

    public function update_order_own()
    {
        $sale_price = 0;
        $daily_price = 0;
        $daily_sale = "daily";
        $this->db->where('id', $this->input->post('product_id'));
        $this->db->select('daily_order');
        $this->db->from('products');
        $query = $this->db->get();
        $daily_order = $query->result();
        $daily_order = intval($daily_order[0]->daily_order);

        $this->db->where('id', $this->input->post('id'));
        $this->db->select('product_quantity');
        $this->db->from('orders');
        $query = $this->db->get();
        $res = $query->result();
        $old_order = intval($res[0]->product_quantity);

        $new_daily_order = $daily_order - $old_order + $this->input->post('quantity');
        $this->db->where('id', $this->input->post('product_id'));
        $this->db->update('products', array('daily_order' => $new_daily_order));
        $date = str_replace('/', '-', $this->input->post('date'));

        $data = array(
            'product_quantity' => $this->input->post('quantity'),
            'sale_price' => $sale_price,
            'daily_price' => $daily_price,
            'daily_sale' => $daily_sale,
            'date' => date('Y-m-d', strtotime($date))
        );

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('orders', $data);
    }

    public function delete_order()
    {
        $this->db->where('id', $this->input->post('id'));
        $this->db->select('daily_sale, product_quantity');
        $this->db->from('orders');
        $query = $this->db->get();
        $res = $query->result();
        $d_s = $res[0]->daily_sale;
        $old_order = intval($res[0]->product_quantity);
        if($d_s == "sale")
        {
            $this->db->where('id', $this->input->post('product_id'));
            $this->db->select('quantity');
            $this->db->from('products');
            $query = $this->db->get();
            $quantity = $query->result();
            $quantity = intval($quantity[0]->quantity);

            $new_quantity = $quantity + $old_order;
            $this->db->where('id', $this->input->post('product_id'));
            $this->db->update('products', array('quantity' => $new_quantity));
        }
        else
        {
            $this->db->where('id', $this->input->post('product_id'));
            $this->db->select('daily_order');
            $this->db->from('products');
            $query = $this->db->get();
            $daily_order = $query->result();
            $daily_order = intval($daily_order[0]->daily_order);

            $new_quantity = $daily_order - $old_order;
            $this->db->where('id', $this->input->post('product_id'));
            $this->db->update('products', array('daily_order' => $new_quantity));
        }
        $this->db->delete('orders', array('id' => $this->input->post('id')));
    }
}
