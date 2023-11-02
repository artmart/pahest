<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
    }

    public function index()
    {
        /*$query = $this->db->get('clients');
        $clients = $query->result();
        $query = $this->db->get('products');
        $products = $query->result();
        $data = array('clients' => $clients, 'products' => $products);*/
        $this->load->view('login');
    }

    public function login($user_data = null)
    {
        if($user_data)
        {
            $username = $user_data;
            $data = array('username' => $username);
            $this->load->view('welcome', $data);
        }
        else
        {
             $ci = get_instance();

            $this->db->close();

            $this->db = $this->load->database($this->input->post('database'), true, false);
            $_SESSION["database"] = $this->input->post('database');
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $this->db->select();
            $this->db->from('users');
            $this->db->where('username', $username);
            $this->db->where('password', md5($password));
            $query = $this->db->get();
            if($query->num_rows() > 0)
            {
                $data = array('username' => $username);
                $this->load->view('welcome', $data);
            }
            else
            {
                redirect('welcome');
            }
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('username');
        redirect('welcome');
    }

    public function check_user()
    {
        echo json_encode(array('res' => $this->session->userdata('username')));
    }
}
