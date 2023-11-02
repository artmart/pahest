<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboardnew extends CI_Controller {

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
	   //$this->load->view('header');
	   $this->load->view('table');
       //$this->load->view('footer');
	}
    
    public function tableresults(){
        $this->load->view('table_results');
    }

	public function index1()
	{
	   
      // var_dump($this->input->post());
       
		$this->db->order_by("name", "esc");
		$query = $this->db->get('products');
		$products = $query->result();
		$this->db->where('orders.daily_sale', 'daily');
		$this->db->select('clients.name as client_name, clients.own, clients.id as client_id,products.id as product_id, products.name as product_name, orders.product_quantity');
		$this->db->from('orders');
        
        if($this->input->post('start'))
        {
            $start = str_replace('/', '-', $this->input->post('start'));
            $this->db->where('orders.date >=', date('Y-m-d', strtotime($start)));
        }
      
        if($this->input->post('end'))
        {
            $end = str_replace('/', '-', $this->input->post('end'));
            $this->db->where('orders.date <=', date('Y-m-d', strtotime($end)));
        }
       
		$this->db->join('products', 'products.id = orders.product_id');
		$this->db->join('clients', 'clients.id = orders.client_id');
		$query = $this->db->get();
        
        

		$data = $query->result();
		$clients = array();
		$client_ids = array();
		foreach($data as $key => $val)
		{
			if(!in_array($val->client_name, $clients))
			{
				if($val->own == "yes")
				{
					$clients[] = $val->client_name;
					$client_ids[] = $val->client_id;
				}
			}
		}
		foreach($data as $key => $val)
		{
			if(!in_array($val->client_name, $clients))
			{
				if($val->own == "no")
				{
					if($val->client_id == 188)
					{
						array_unshift($clients, $val->client_name);
						array_unshift($client_ids, $val->client_id);
					}
					else
					{
						$clients[] = $val->client_name;
						$client_ids[] = $val->client_id;
					}
				}
			}
		}
		$res = array();
        $giveback_quantity = array();
		foreach($data as $key => $value)
		{
            $this->db->where('product_id', $value->product_id);
            $this->db->where('client_id', $value->client_id);
            $query = $this->db->get('giveback');
            $givebacks = $query->result();
            $giveback_q = 0;
            foreach($givebacks as $giveback)
            {
                 $giveback_q += $giveback->quantity;
            }
            $giveback_quantity[$value->product_name][$value->client_name] = $giveback_q;
			if(isset($res[$value->product_name][$value->client_name]))
			{
				$res[$value->product_name][$value->client_name] += intval($value->product_quantity);
			}
			else
			{
				$res[$value->product_name][$value->client_name] = intval($value->product_quantity);
			}
		}
        foreach($res as $key => $value)
        {
            foreach($value as $k => $v)
            {
                $res[$key][$k] -= $giveback_quantity[$key][$k];
				if($res[$key][$k] == 0)
				{
					unset($res[$key][$k]);
				}
            }
        }
		foreach($clients as $key => $client)
		{
			$check = true;
			foreach($res as $element)
			{
				if(array_key_exists($client, $element))
				{
					$check = false;
				}
			}
			if($check)
			{
				unset($clients[$key]);
			}
		}
		$this->db->select('name, id');
		$this->db->where('debt !=', 0);
		$query = $this->db->get('clients');
		$clients_with_debt = $query->result();
		foreach($clients_with_debt as $key => $value)
		{
			if(in_array($value->name, $clients))
			{
				unset($clients_with_debt[$key]);
			}
		}
		$data = array('res' => $res, 'clients' => $clients, 'products' => $products, 'client_ids' => $client_ids, 'clients_with_debt' => $clients_with_debt);
		$this->load->view('dashboard', $data);
	}
    
    
    

    public function get_client_info()
    {
		$check_debt = 0;
		if(null !=$this->input->get('debt'))
		{
			$check_debt = 1;
		}
        $interval = false;
        $datepicker_start = date('d/m/Y');
        $datepicker_end = date('d/m/Y');
        if($this->input->get('date_start') && $this->input->get('date_start'))
        {
            $datepicker_start = str_replace('/', '-', $this->input->get('date_start'));
            $datepicker_end = str_replace('/', '-', $this->input->get('date_end'));
            $interval = true;
        }
		$client_id = $this->input->get('client_id');
		$date = NULL;
		if($this->input->get('date'))
		{
			$date = $this->input->get('date');
		}
        $this->db->where('id', $client_id);
        $query = $this->db->get('clients');
        $client = $query->result()[0];
        $client_name = $client->name;

 		$this->db->select('products.name as product_name, giveback.quantity, giveback.product_price, giveback.useless_quantity, giveback.date, giveback.id, giveback.product_id');
 		$this->db->where('client_id', $client_id);
 		$this->db->from('giveback');
 		$this->db->join('products', 'giveback.product_id = products.id');
        $this->db->order_by('giveback.date');
 		$givebacks_html = $this->db->get()->result();

        if($client->own == "yes")
        {
			$this->db->select('orders.id, products.name, orders.product_id, orders.product_quantity, orders.date');
			$this->db->where('client_id', $client_id);
			$this->db->from('orders');
			$this->db->join('products', 'products.id = orders.product_id');
			$orders = $this->db->get()->result();
			$html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">'.$client_name.' Օբյեկտ</h4></div>
            <div class="modal-body">
            <div class="row">
			<h4 class="col-md-2">Ընտրել ինտերվալ</h4>
            <div class="col-md-2">
            <div class="input-group date" data-provide="datepicker">
                <input type="text" id="date_start" data-client_id="'.$client_id.'" value="'.$datepicker_start.'" id="debt_date" class="form-control datepicker" name="date_start">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
            </div>
            <div class="col-md-2">
            <div class="input-group date" data-provide="datepicker">
                <input type="text" id="date_end" data-client_id="'.$client_id.'" value="'.$datepicker_end.'" id="debt_date" class="form-control datepicker" name="date_end">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
            </div>
            
            <button class="btn btn-lg btn-sm btn-success col-md-2" data-client_id="'.$client->id.'" id="set_interval">Հաստատել</button></div><hr />';
			$html .= '<div class="col-md-6"><h3>Գործարքներ</h3>';
			$html .= '<table class="table"><thead><th>Ապրանք</th><th>Քանակ</th><th>Ամսաթիվ</th><th>Գործ.</th></thead>';
			foreach($orders as $order)
			{
                if($interval)
                {
                    if(strtotime($order->date) < strtotime($datepicker_start) || strtotime($order->date) > strtotime($datepicker_end))
                    {
                        continue;
                    }
                }
				$html .= '<tr data-id="'.$order->id.'" data-product_id="'.$order->product_id.'"><td>'.$order->name.'</td><td>'.$order->product_quantity.'</td><td>'.$order->date.'</td><td><button class="btnEditOwn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button><button class="btnDeleteOwn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button></td><tr>';
			}
			$html .= '</table></div><div class="col-md-6"><h3>Վերադարձ</h3>';
			$html .= '<table class="table"><thead><th>Ապրանք</th><th>Քանակ</th><th>Ամսաթիվ</th><th>Գործ.</th></thead>';
			foreach($givebacks_html as $giveback)
			{
                if($interval)
                {
                    if(strtotime($giveback->date) < strtotime($datepicker_start) || strtotime($giveback->date) > strtotime($datepicker_end))
                    {
                        continue;
                    }
                }
				$html .= '<tr data-id="'.$giveback->id.'" data-product_id="'.$giveback->product_id.'"><td>'.$giveback->product_name.'</td><td>'.$giveback->quantity.'</td><td>'.$giveback->date.'</td><td><button class="btnEditGB btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button><button class="btnDeleteGB btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button></td></tr>';
			}
			$html .= '</table></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Փակել</button></div>';

            echo json_encode(array('html' => $html));
        }
        else
        {
            $this->db->select('debt');
            $this->db->where('id', $client_id);
            $this->db->from('clients');
            $debt = $this->db->get()->result();
            $debt = $debt[0]->debt;
            $this->db->select('orders.id, products.id as product_id, orders.daily_sale, orders.date, orders.product_quantity, orders.daily_price, orders.sale_price, products.name');
            $this->db->where('client_id', $client_id);
			$this->db->from('orders');
			$this->db->join('products', 'products.id = orders.product_id');
			$this->db->order_by('orders.date');
            $orders = $this->db->get()->result();
            $order_debt = 0;
            foreach($orders as $order)
            {
                if($order->daily_sale == "daily")
                {
					$now = time();
					if($date)
					{
						$date = str_replace('/', '-', $date);
						$now = strtotime($date);
					}
					$your_date = strtotime($order->date);
					$datediff = $now - $your_date;

					$day = floor($datediff / (60 * 60 * 24));
                    $order_debt += intval($order->product_quantity) * intval($order->daily_price) * $day;
                }
                else
                {
					if($date)
					{
						if(strtotime($order->date) > strtotime($date))
						{
							$order_debt += intval($order->product_quantity) * intval($order->sale_price);
						}
					}
					else
					{
						$order_debt += intval($order->product_quantity) * intval($order->sale_price);
					}
                }
            }
            $this->db->where('client_id', $client_id);
            $givebacks = $this->db->get('giveback')->result();
            $giveback_amount = 0;
            foreach($givebacks as $giveback)
            {
                $product_price = $giveback->product_price;
				$now = time(); // or your date as well
				if($date)
				{
					$now = strtotime($date);
				}
				$your_date = strtotime($giveback->date);
				$datediff = $now - $your_date;

				$day = floor($datediff / (60 * 60 * 24));
				if($date)
				{
					if(strtotime($giveback->date) > strtotime($date))
					{
						$giveback_amount += intval($giveback->quantity) * intval($product_price) * $day;
					}
				}
				else
				{
					$giveback_amount += intval($giveback->quantity) * intval($product_price) * $day;
				}
            }
            $this->db->where('client_id', $client_id);
            $query = $this->db->get('payment');
            $payments = $query->result();
            $paid = 0;
            foreach($payments as $payment)
            {
				if($date)
				{
					if(strtotime($payment->date) > strtotime($date))
					{
						$paid += $payment->amount;
					}
				}
				else
				{
					$paid += $payment->amount;
				}
            }

            $final_debt = $debt + $order_debt - $giveback_amount - $paid;
			if($date)
			{
				echo $final_debt; die();
			}
            $datepicker_value = date('d/m/Y');
			$html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title" id="client_debt">'.$client_name.'<br>Պարտք: '.$final_debt.' դրամ</h4>
			<div class="input-group date" style="width: 200px;" data-provide="datepicker">
                <input type="text" data-client_id="'.$client_id.'" value="'.$datepicker_value.'" id="debt_date" class="form-control datepicker" name="debt_date">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
            <h4>Ընտրել ինտերվալ</h4>
            <div class="input-group date" style="width: 200px;" data-provide="datepicker">
                <input type="text" id="date_start" data-client_id="'.$client_id.'" value="'.$datepicker_start.'" id="debt_date" class="form-control datepicker" name="date_start">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
            <div class="input-group date" style="width: 200px;" data-provide="datepicker">
                <input type="text" id="date_end" data-client_id="'.$client_id.'" value="'.$datepicker_end.'" id="debt_date" class="form-control datepicker" name="date_end">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div><button class="btn btn-lg btn-success" data-client_id="'.$client_id.'" id="set_interval">Հաստատել</button></div>';
			$html .= '<div class="modal-body"><div class="horizontal"><div class="table-div"><div class="horizontal-div"><h3>Վճարումներ</h3>';
			$html .= '<table class="table"><thead><th>Գին</th><th>Ամսաթիվ</th><th>Գործ.</th></thead>';
			foreach($payments as $payment)
			{
                if($interval)
                {
                    if(strtotime($payment->date) < strtotime($datepicker_start) || strtotime($payment->date) > strtotime($datepicker_end))
                    {
                        continue;
                    }
                }
				$html .= '<tr data-id="'.$payment->id.'"><td>'.$payment->amount.'</td><td>'.$payment->date.'</td><td><button class="btnEditP btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button><button class="btnDeleteP btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button></td></tr>';
			}
			$html .= '</table></div><div class="horizontal-div"><h3>Գործարքներ</h3>';
			$html .= '<table class="table"><thead><th>Ապրանք</th><th>Քանակ</th><th>Գին</th><th>Ամսաթիվ</th><th>Գործարքի տեսակ</th><th>Գործ.</th></thead>';
			$check = 0;
			$sum = 0;
			$check_tr = false;
			$res_sum = 0;
			foreach($orders as $order)
			{
				$check_tr = false;
                if($interval)
                {
                    if(strtotime($order->date) < strtotime($datepicker_start) || strtotime($order->date) > strtotime($datepicker_end))
                    {
                        continue;
                    }
                }
				if($order->daily_sale == 'daily')
				{
					if(strtotime($order->date) != $check)
					{
						if($check != 0)
						{
							$check_tr = true;
							$res_sum = $sum;
						}
						$check = strtotime($order->date);
						$sum = $order->product_quantity * $order->daily_price;
					}
					else
					{
						$sum += $order->product_quantity * $order->daily_price;
					}
					$daily_sale = '<td>Օրավարձ</td>';
					$price = $order->daily_price;
				}
				else
				{
					$daily_sale = '<td>Վաճառք</td>';
					$price = $order->sale_price;
				}
				if($check_tr == true)
				{
					$html .= '<tr><td></td><td colspan="2">'.$res_sum.'</td><td colspan="3"></td></tr>';
				}
				$html .= '<tr data-id="'.$order->id.'" data-product_id="'.$order->product_id.'"><td>'.$order->name.'</td><td>'.$order->product_quantity.'</td><td>'.$price.'</td><td>'.$order->date.'</td>'.$daily_sale.'<td><button class="btnEdit btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button><button class="btnDelete btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button></td></tr>';
			}
			$html .= '<tr><td></td><td colspan="2">'.$sum.'</td><td colspan="3"></td></tr>';
			$html .= '</table></div><div class="horizontal-div"><h3>Վերադարձ</h3>';
			$html .= '<table class="table"><thead><th>Ապրանք</th><th>Քանակ</th><th>Գին</th><th>Ամսաթիվ</th><th>Գործ.</th></thead>';
            $check = 0;
            $sum = 0;
            $check_tr = false;
            $res_sum = 0;
			foreach($givebacks_html as $giveback)
			{
                $this->db->where('product_id', $giveback->product_id);
                $this->db->where('client_id', $client_id);
                $this->db->where('daily_sale', 'daily');
		        $this->db->where('date <=', $giveback->date);
                $this->db->order_by("id", "desc");
                $query = $this->db->get('orders')->result();
                $giveback_price = $giveback->product_price;
                $check_tr = false;
                if($interval)
                {
                    if(strtotime($giveback->date) < strtotime($datepicker_start) || strtotime($giveback->date) > strtotime($datepicker_end))
                    {
                        continue;
                    }
                }
                if(strtotime($giveback->date) != $check)
                {
                    if($check != 0)
                    {
                        $check_tr = true;
                        $res_sum = $sum;
                    }
                    $check = strtotime($giveback->date);
                    $sum = $giveback->quantity * $giveback_price;
                }
                else
                {
                    $sum += $giveback->quantity * $giveback_price;
                }
                if($check_tr == true)
                {
                    $html .= '<tr><td></td><td colspan="2">'.$res_sum.'</td><td colspan="2"></td></tr>';
                }
				$html .= '<tr data-id="'.$giveback->id.'" data-product_id="'.$giveback->product_id.'"><td>'.$giveback->product_name.'</td><td>'.$giveback->quantity.'</td><td>'.$giveback->product_price.'</td><td>'.$giveback->date.'</td><td><button class="btnEditGB btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button><button class="btnDeleteGB btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button></td></tr>';
			}
            $html .= '<tr><td></td><td colspan="2">'.$sum.'</td><td colspan="2"></td></tr>';
			$html .= '</table></div></div></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Փակել</button></div>';
            echo json_encode(array('html' => $html));
        }
    }

    public function get_product_client_info()
    {
        $interval = false;
        $datepicker_start = date('d/m/Y');
        $datepicker_end = date('d/m/Y');
        if($this->input->get('date_start') && $this->input->get('date_start'))
        {
            $datepicker_start = str_replace('/', '-', $this->input->get('date_start'));
            $datepicker_end = str_replace('/', '-', $this->input->get('date_end'));
            $interval = true;
        }
        $client_id =  $this->input->get('client_id');
        $product_id =  $this->input->get('product_id');;
        
        $this->db->where('id', $client_id);
        $client = $this->db->get('clients')->result()[0];
        $client_name = $client->name;

        $this->db->where('id', $product_id);
        $product = $this->db->get('products')->result()[0];
        $product_name = $product->name;

        $this->db->where('client_id', $client_id);
        $this->db->where('product_id', $product_id);
        $orders = $this->db->get('orders')->result();

        $this->db->where('client_id', $client_id);
        $this->db->where('product_id', $product_id);
        $givebacks = $this->db->get('giveback')->result();

		$html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">'.$client_name.' - '.$product_name.'</h4></div>
        <br />
        <div class="row">
		<h4 class="col-sm-2">Ընտրել ինտերվալ</h4>
        <div class="col-sm-2">
            <div class="input-group date" data-provide="datepicker">
                <input type="text" id="date_start" data-client_id="'.$product_id.'" value="'.$datepicker_start.'" id="debt_date" class="form-control datepicker" name="date_start">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="input-group date" data-provide="datepicker">
                <input type="text" id="date_end" data-client_id="'.$product_id.'" value="'.$datepicker_end.'" id="debt_date" class="form-control datepicker" name="date_end">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
        <button class="btn btn-sm btn-lg btn-success col-sm-1" data-client_id="'.$client_id.'" data-product_id="'.$product_id.'" id="set_interval_product_client">Հաստատել</button>
        </div>
            <hr />';
		$html .= '<div class="modal-body"><div class="col-md-6"><h3>Ապրանքի վերադարձ</h3>';
        if($client->own == "yes") {
            $html .= '<table class="table"><th>Քանակ</th><th>Ամսաթիվ</th>';
        }
        else {
            $html .= '<table class="table"><th>Քանակ</th><th>Գին</th><th>Ամսաթիվ</th>';
        }
		foreach($givebacks as $giveback)
		{
            if($interval)
            {
                if(strtotime($giveback->date) < strtotime($datepicker_start) || strtotime($giveback->date) > strtotime($datepicker_end))
                {
                    continue;
                }
            }
            if($client->own == "yes") {
                $html .= '<tr><td>'.$giveback->quantity.'</td><td>'.$giveback->date.'</td></tr>';
            }
            else {
                $html .= '<tr><td>'.$giveback->quantity.'</td><td>'.$giveback->product_price.'</td><td>'.$giveback->date.'</td></tr>';
            }

		}
		$html .= '</table></div><div class="col-md-6"><h3>Գործարքներ</h3>';
		$html .= '<table class="table"><th>Քանակ</th><th>Գին</th><th>Ամսաթիվ</th><th>Գործարքի տեսակ</th>';
		foreach($orders as $order)
		{
            if($interval)
            {
                if(strtotime($order->date) < strtotime($datepicker_start) || strtotime($order->date) > strtotime($datepicker_end))
                {
                    continue;
                }
            }
			if($order->daily_sale == 'daily')
			{
				$daily_sale = '<td>Օրավարձ</td>';
			}
			else
			{
				$daily_sale = '<td>Վաճառք</td>';
			}
			if($order->daily_sale == 'daily')
			{
				$price = $order->daily_price;
			}
			else
			{
				$price = $order->sale_price;
			}
			$html .= '<tr><td>'.$order->product_quantity.'</td><td>'.$price.'</td><td>'.$order->date.'</td>'.$daily_sale.'</tr>';
		}
		$html .= '</table></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Փակել</button></div>';

        echo json_encode(array('orders' => $orders, 'givebacks' => $givebacks, 'html' => $html));
    }

	public function get_product_info()
	{
        $product_id = $this->input->get('product_id');
        $interval = false;
        $datepicker_start = date('d/m/Y');
        $datepicker_end = date('d/m/Y');
        if($this->input->get('date_start') && $this->input->get('date_start'))
        {
            $datepicker_start = str_replace('/', '-', $this->input->get('date_start'));
            $datepicker_end = str_replace('/', '-', $this->input->get('date_end'));
            $interval = true;
        }
		$this->db->select('name');
		$this->db->from('products');
		$this->db->where('id', $product_id);
		$product_name = $this->db->get()->result();

		$this->db->where('product_id', $product_id);
		$orders = $this->db->get('orders')->result();

		$this->db->select('giveback.quantity, giveback.product_price, giveback.date, clients.name');
		$this->db->from('giveback');
		$this->db->join('clients', 'giveback.client_id=clients.id');
		$this->db->where('product_id', $product_id);
		$givebacks = $this->db->get()->result();

		$html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">'.$product_name[0]->name.'</h4></div>';
		$html .= '<div class="modal-body">
        <div class="row">
            <h4 class="col-md-2">Ընտրել ինտերվալ</h4>
            <div class="col-md-2">
            <div class="input-group date" data-provide="datepicker">
                <input type="text" id="date_start" data-client_id="'.$product_id.'" value="'.$datepicker_start.'" id="debt_date" class="form-control datepicker" name="date_start">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
            </div>
            <div class="col-md-2">
            <div class="input-group date" data-provide="datepicker">
                <input type="text" id="date_end" data-client_id="'.$product_id.'" value="'.$datepicker_end.'" id="debt_date" class="form-control datepicker" name="date_end">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
            </div>
            
            <button class="btn btn-lg btn-sm btn-success col-md-2" data-product_id="'.$product_id.'" id="set_interval_product">Հաստատել</button>
            </div>
            <hr />
            <div class="col-md-4"><h3>Ապրանքի վերադարձ</h3>';
		$html .= '<table class="table"><th>Կլիենտ</th><th>Քանակ</th><th>Գին</th><th>Ամսաթիվ</th>';
		foreach($givebacks as $giveback)
		{
            if($interval)
            {
                if(strtotime($giveback->date) < strtotime($datepicker_start) || strtotime($giveback->date) > strtotime($datepicker_end))
                {
                    continue;
                }
            }
			$html .= '<tr><td>'.$giveback->name.'</td><td>'.$giveback->quantity.'</td><td>'.$giveback->product_price.'</td><td>'.$giveback->date.'</td></tr>';
		}
		$sales = array();
		$dailies = array();
		foreach($orders as $value)
		{
            if($interval)
            {
                if(strtotime($value->date) < strtotime($datepicker_start) || strtotime($value->date) > strtotime($datepicker_end))
                {
                    continue;
                }
            }
			if($value->daily_sale == 'daily')
			{
				$dailies[] = $value;
			}
			else
			{
				$sales[] = $value;
			}
		}

		$html .= '</table></div><div class="col-md-4"><h3>Օրավարձ</h3>';
		$html .= '<table class="table"><th>Կլիենտ</th><th>Քանակ</th><th>Գին</th><th>Ամսաթիվ</th>';
		foreach($dailies as $order)
		{
			$this->db->where('id', $order->client_id);
			$this->db->select('name');
			$query = $this->db->get('clients');
			if(isset($query->result()[0]))
			{
				$client_name = $query->result()[0]->name;
				if($order->daily_sale == 'daily')
				{
					$price = $order->daily_price;
				}
				else
				{
					$price = $order->sale_price;
				}
				$html .= '<tr><td>'.$client_name.'</td><td>'.$order->product_quantity.'</td><td>'.$price.'</td><td>'.$order->date.'</td></tr>';
			}
		}
		$html .= '</table>';

		$html .= '</table></div><div class="col-md-4"><h3>Վաճառք</h3>';
		$html .= '<table class="table"><th>Կլիենտ</th><th>Քանակ</th><th>Գին</th><th>Ամսաթիվ</th>';
		foreach($sales as $order)
		{
			$this->db->where('id', $order->client_id);
			$this->db->select('name');
			$query = $this->db->get('clients');
			if(isset($query->result()[0]))
			{
				$client_name = $query->result()[0]->name;
				if($order->daily_sale == 'daily')
				{
					$price = $order->daily_price;
				}
				else
				{
					$price = $order->sale_price;
				}
				$html .= '<tr><td>'.$client_name.'</td><td>'.$order->product_quantity.'</td><td>'.$price.'</td><td>'.$order->date.'</td></tr>';
			}
		}
		$html .= '</table></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Փակել</button></div>';

		echo json_encode(array('orders' => $orders, 'givebacks' => $givebacks, 'html' => $html));
	}

    public function get_debts()
    {
        $date = NULL;
        if($this->input->get('date'))
        {
            $date = $this->input->get('date');
        }
        $query = $this->db->get('clients');
        $clients = $query->result();
        $result_array = array();
        foreach($clients as $client)
        {
            $client_id = $client->id;
            $this->db->select('debt');
            $this->db->where('id', $client_id);
            $this->db->from('clients');
            $debt = $this->db->get()->result();
            $debt = $debt[0]->debt;
            $this->db->select('orders.id, products.id as product_id, orders.daily_sale, orders.date, orders.product_quantity, orders.daily_price, orders.sale_price, products.name');
            $this->db->where('client_id', $client_id);
            $this->db->from('orders');
            $this->db->join('products', 'products.id = orders.product_id');
            $orders = $this->db->get()->result();
            $order_debt = 0;
            foreach($orders as $order)
            {
                if($order->daily_sale == "daily")
                {
                    $now = time();
                    if($date)
                    {
                        $now = strtotime($date);
                    }
                    $your_date = strtotime($order->date);
                    $datediff = $now - $your_date;

                    $day = floor($datediff / (60 * 60 * 24));
                    $order_debt += intval($order->product_quantity) * intval($order->daily_price) * $day;
                }
                else
                {
                    if($date)
                    {
                        if(strtotime($order->date) < strtotime($date))
                        {
                            $order_debt += intval($order->product_quantity) * intval($order->sale_price);
                        }
                    }
                    else
                    {
                        $order_debt += intval($order->product_quantity) * intval($order->sale_price);
                    }
                }
            }
            $this->db->where('client_id', $client_id);
            $givebacks = $this->db->get('giveback')->result();
            $giveback_amount = 0;
            foreach($givebacks as $giveback)
            {
                $this->db->where('client_id', $client_id);
                $this->db->where('product_id', $giveback->product_id);
            $this->db->where('daily_sale', 'daily'); //Added by Artak
                $query = $this->db->get('orders');
                $product_price = $query->result();
                
                
//Added by Artak//                
if(!isset($product_price[0])){
		    	$this->db->where('id', $client_id);
		    	$query = $this->db->get('clients');
			$client_name = $query->result()[0]->name;
			    
			$this->db->where('id', $giveback->product_id);
		    	$query = $this->db->get('products');
			$product_name = $query->result()[0]->name;
			    
			echo "<h1>Հետևյալ կլիենտով ու ապրանքով ձևակերպած է վերադարձ, բայց չի եղել այդպիսի վարձակալություն:</h1>";
			echo "<h3>Կլիենտ: ".$client_name."</h3><h3>Ապրանք: ".$product_name."</h3><h3>Ամսաթիվ: ".$giveback->date."</h3>";
		    }        
//////////////////////////////////////////////////////////////                

                
                $product_price = $product_price[0]->daily_price;
                $now = time(); // or your date as well
                if($date)
                {
                    $now = strtotime($date);
                }
                $your_date = strtotime($giveback->date);
                $datediff = $now - $your_date;

                $day = floor($datediff / (60 * 60 * 24));
                if($date)
                {
                    if(strtotime($giveback->date) > strtotime($date))
                    {
                        $giveback_amount += intval($giveback->quantity) * intval($product_price) * $day;
                    }
                }
                else
                {
                    $giveback_amount += intval($giveback->quantity) * intval($product_price) * $day;
                }
            }
            $this->db->where('client_id', $client_id);
            $query = $this->db->get('payment');
            $payments = $query->result();
            $paid = 0;
            foreach($payments as $payment)
            {
                if($date)
                {
                    if(strtotime($payment->date) < strtotime($date))
                    {
                        $paid += $payment->amount;
                    }
                }
                else
                {
                    $paid += $payment->amount;
                }
            }

            $final_debt = $debt + $order_debt - $giveback_amount - $paid;
            if($final_debt > 0 || $final_debt < 0)
            {
                $result_array[$client->name] = array('debt' => $final_debt, 'id' => $client->id);
            }
        }
        if($date)
        {
            $datepicker_value = $date;
        }
        else
        {
            $datepicker_value = date('d/m/Y');
        }
        $html = '<div class="input-group date" style="width: 200px;" data-provide="datepicker"><input type="text" id="debts_date" value="'.$datepicker_value.'" class="form-control datepicker" name="debts_date"><div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div><div class="col-md-6">';
        $html .= '<table class="table" id="deptable"><th>Կլիենտ</th><th>Պարտք</th>';
        foreach($result_array as $name => $arr)
        {
            $html .= '<tr data-client_id="'.$arr['id'].'"><td>'.$name.'</td><td>'.$arr['debt'].' դրամ</td></tr>';
        }
        $html .= '</table></div>';
        echo json_encode(array('html' => $html));
    }
}
