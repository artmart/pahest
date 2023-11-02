<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Getproduct extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function index()
    {
        $this->db->select('product_name');
        $this->db->select_sum('product_quantity');
        $this->db->group_by('product_name');
        $query = $this->db->get('new_products')->result();
        $data = ['query' => $query];
        $this->load->view('getproduct', $data);
    }

    public function get_products()
    {
        $product_name = $this->input->get('product_name');
        $this->db->where('product_name', $product_name);
        $query = $this->db->get('new_products')->result();
        $html = '<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            <div class="col-md-6">
            <table class="table">
                <thead>
                <th>Ապրանք</th>
                <th>Քանակ</th>
                <th>Ամսաթիվ</th>
                </thead>
                <tbody>';
        foreach($query as $value)
        {
            $html .= '<tr><td>'.$value->product_name.'</td><td>'.$value->product_quantity.'</td><td>'.$value->date.'</td></tr>';
        }
        $html .= '</tbody>
            </table>
        </div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Փակել</button>
            </div>';
        echo json_encode(array('html' => $html));
    }
}
