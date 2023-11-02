<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Giveback extends CI_Controller {

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
        $query = $this->db->get('clients');
        $clients = $query->result();
        $query = $this->db->get('products');
        $products = $query->result();
        $data = array('clients' => $clients, 'products' => $products);
        $this->load->view('giveback', $data);
    }
    public function new_giveback()
    {

        $bad_quantity = intval($this->input->post('bad_quantity'));
        $useless_quantity = intval($this->input->post('useless_quantity'));
        $product_id = intval($this->input->post('product_to_pick'));
        $this->db->select('bad_quantity, useless_quantity, daily_order');
        $this->db->where('id', $product_id);
        $query = $this->db->get('products');
        $query = $query->result()[0];
        $new_bad = $bad_quantity + $query->bad_quantity;
        $new_useless = $useless_quantity + $query->useless_quantity;

        $daily_order = $query->daily_order;
        $new_daily_order = intval($daily_order) - intval($this->input->post('product_quantity'));

        $this->db->where('id', $product_id);
        $this->db->update('products', array('bad_quantity' => $new_bad, 'useless_quantity' => $new_useless, 'daily_order' => $new_daily_order));
        $date = str_replace('/', '-', $this->input->post('date'));

        $data = array(
            'client_id' => $this->input->post('client_to_pick'),
            'product_id' => $this->input->post('product_to_pick'),
            'quantity' => $this->input->post('product_quantity'),
            'product_price' => $this->input->post('product_price'),
            'bad_quantity' => $bad_quantity,
            'useless_quantity' => $useless_quantity,
            'date' => date('Y-m-d', strtotime($date))
        );
        $this->db->insert('giveback', $data);
        redirect('giveback');
    }
    public function get_client_products($client_id)
    {
        $this->db->where('orders.daily_sale', 'daily');
        $this->db->select('clients.name as client_name, orders.product_quantity, clients.id as client_id,products.id as product_id, products.name as product_name, orders.daily_price, orders.date');
        $this->db->from('orders');
        $this->db->join('products', 'products.id = orders.product_id');
        $this->db->join('clients', 'clients.id = orders.client_id');
        $query = $this->db->get();
        $data = $query->result();
        
        //Var_dump($data);
        //exit;
        $products = array();
        $product_q = array();
        foreach($data as $key => $value)
        {
            if($value->client_id == $client_id)
            {
                //$products[$value->product_id] = $value->product_name;
                $products[$value->product_id] = ['product_name'=>$value->product_name, 'daily_price'=>$value->daily_price, 'date'=>$value->date];
                if(isset($product_q[$value->product_id])){
                    $product_q[$value->product_id] += $value->product_quantity;
                }else{
                    $product_q[$value->product_id] = $value->product_quantity;
                }
            }
            
            
        }
        foreach($product_q as $key => $value)
        {
            $this->db->where('client_id', $client_id);
            $this->db->where('product_id', $key);
            $query = $this->db->get('giveback');
            $res = $query->result();
            $giveback_count = 0;
            foreach($res as $value)
            {
                $giveback_count += $value->quantity;
            }
            $product_q[$key] -= $giveback_count;
        }
        
        
        //$products = array_unique($products);
        $res = '<option selected disabled>Ընտրել</option>';
        $products_tbl = '<h3>Բոլոր ապրանքները</h3><table class="table"><thead><tr><th>Ապրանք</th><th>Քանակ</th><th>Գին</th><th>Ամսաթիվ</th></tr></thead><tbody>';
        foreach($products as $key => $value)
        {
            $res .= '<option value="'.$key.'" data-q="'.$product_q[$key].'">'.$value['product_name'].'</option>';
            $products_tbl .= '<tr><td>'.$value['product_name'].'</td><td>'.$product_q[$key].'</td><td>'.$value['daily_price'].'</td><td>'.$value['date'].'</td></tr>';
        }
        $products_tbl .= '</tbody></table>';
        
        echo json_encode(['result' => $res, 'products' =>$products_tbl]);
    }

    public function update_giveback()
    {
        $product_id = $this->input->post('product_id');
        $id = $this->input->post('id');

        $this->db->where('id', $id);
        $query = $this->db->get('giveback', $id)->result();
        $giveback = $query[0];

        $old_quantity = $giveback->quantity;
        $old_useless_quantity = $giveback->useless_quantity;

        $this->db->where('id', $product_id);
        $query = $this->db->get('products')->result();
        $product = $query[0];

        $quantity = $product->daily_order;
        $useless_quantity = $product->useless_quantity;

        $new_quantity = $quantity + $old_quantity - $this->input->post('quantity');
        //$new_useless_quantity = $useless_quantity - $old_useless_quantity + $this->input->post('useless_quantity');

        $this->db->where('id', $product_id);
        $this->db->update('products', array('daily_order' => $new_quantity));
        $date = str_replace('/', '-', $this->input->post('date'));

        $data = array(
            'quantity' => $this->input->post('quantity'),
            'product_price' => $this->input->post('product_price'),
            'date' => date('Y-m-d', strtotime($date))
        );

        $this->db->where('id', $id);
        $this->db->update('giveback', $data);
    }

    public function delete_giveback()
    {
        $product_id = $this->input->post('product_id');
        $id = $this->input->post('id');

        $this->db->where('id', $id);
        $query = $this->db->get('giveback', $id)->result();
        $giveback = $query[0];

        $old_quantity = $giveback->quantity;
        $old_useless_quantity = $giveback->useless_quantity;

        $this->db->where('id', $product_id);
        $query = $this->db->get('products')->result();
        $product = $query[0];

        $quantity = $product->daily_order;
        $useless_quantity = $product->useless_quantity;

        $new_quantity = $quantity + $old_quantity;
        $new_useless_quantity = $useless_quantity - $old_useless_quantity;

        $this->db->where('id', $product_id);
        $this->db->update('products', array('daily_order' => $new_quantity, 'useless_quantity' => $new_useless_quantity));

        $this->db->delete('giveback', array('id' => $id));
    }
}
