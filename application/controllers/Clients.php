<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends CI_Controller {

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
        $data = array('clients' => $clients);
        $this->load->view('clients', $data);
    }
    public function new_client()
    {
        $data = array(
            'name' => $this->input->post('name') ,
            'own' => $this->input->post('own')?'yes':'no' ,
            'debt' => $this->input->post('debt')
        );
        $this->db->insert('clients', $data);
        redirect('clients');
    }
    public function edit_client($id)
    {
        $data = array(
            'name' => $this->input->post('name') ,
            'own' => $this->input->post('own')?'yes':'no' ,
            'debt' => $this->input->post('debt')
        );
        $this->db->where('id', $id);
        $this->db->update('clients', $data);
        redirect('clients');
    }
    public function get_client_details($id)
    {
        $query = $this->db->get_where('clients', array('id' => $id));
        echo json_encode(array('data' => $query->result()));
    }
}
