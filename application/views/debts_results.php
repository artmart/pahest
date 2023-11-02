<style>
table.dataTable.no-footer {
  border: 1px solid #111;
}
</style>
<?php

//var_dump($this->input->post());
		$date = NULL;
        if($this->input->post('debts_date'))
        {
           $date = $this->input->post('debts_date');
        }
        $query = $this->db->get('clients');
        $clients = $query->result();
        $result_array = array();
        foreach($clients as $client)
        {
            $client_id = $client->id;
            /*$this->db->select('debt');
            $this->db->where('id', $client_id);
            $this->db->from('clients');
            $debt = $this->db->get()->result();
            $debt = $debt[0]->debt;*/
            
            $debt = $client->debt;
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
                }else{
                    if($date){
                        if(strtotime($order->date) > strtotime($date))
                        {
                            $order_debt += intval($order->product_quantity) * intval($order->sale_price);
                        }
                    }else{
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
	    	$this->db->where('daily_sale', 'daily');
                $query = $this->db->get('orders');
                $product_price = $query->result();
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
                $product_price = $giveback->product_price; //$product_price[0]->daily_price;
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
        
        

        //$data = array('datepicker_value' => $datepicker_value, 'result_array' => $result_array);
        //$this->load->view('debts', $data);
        
        //$html = '<div class="col-md-11">';
        //$html .= '<table class="table" id="deptable"><thead><tr><th>Կլիենտ</th><th>Պարտք (դրամ)</th></tr></thead><tbody>';
        $k=0;
        $table2JsonData = [];
        foreach($result_array as $name => $arr)
        {
            //$html .= '<tr data-client_id="'.$arr['id'].'" data-toggle="modal" data-target="#myModal" class="clickable client_info"><td>'.$name.'</td><td>'.$arr['debt'].'</td></tr>';
            $table2JsonData[$k] = [
				'<span data-client_id="'.$arr['id'].'" data-toggle="modal" data-target="#myModal" class="clickable client_info">'.$name.'</span>',
                '<span data-client_id="'.$arr['id'].'" data-toggle="modal" data-target="#myModal" class="clickable client_info">'.$arr['debt'].'</span>',
			];
        
        $k++;
        
        }
        //$html .= '</tbody></table></div>';
        // json_encode(array('html' => $html));
        /*
        foreach($result_array as $name => $arr)
            {
                echo '<tr data-client_id="'.$arr['id'].'" data-toggle="modal" data-target="#myModal" class="clickable client_info"><td>'.$name.'</td><td>'.$arr['debt'].'</td></tr>';
            }
*/
?>



    <?php //echo $html; ?>
    <div class="col-md-11">
        <table class="table" id="deptable">
        <thead><tr><th>Կլիենտ</th><th>Պարտք (դրամ)</th></tr>
        </thead>
        <tbody>
    </tbody></table></div>
    




<script>

	$(document).ready(function() {  
	  
     // show();
       
       
      
    $('#deptable').DataTable({
          "ordering": true,
          data: <?php echo json_encode($table2JsonData); ?>, 
          //"paging": false,
          //"searching": false,
          //"info":     false,
          dom: "Bflrtip",   
          buttons: [
            {extend: "copy", className: "btn-sm"},
            {extend: "csv", className: "btn-sm"},
            {extend: "excel", className: "btn-sm"},
            {extend: "print", className: "btn-sm"},
          ],
        });    
    });
</script>