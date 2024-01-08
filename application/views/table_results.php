<style>
#first-th{
    width: 100% !important;
    min-width: 170px !important;
}
</style>
<?php
/*
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
		//$this->load->view('dashboard', $data);
       // exit;
   ?>       
	<div id="products_table">
		<table id="tbl_id" class="display table-striped table-sm">
		<thead>
			<tr>
				<th id='first-th' style="vertical-align: middle; width: auto;">Ապրանքի անուն</th>
				<th style="vertical-align: middle; width: 25px;">Քան.</th>
				<th style="vertical-align: middle; width: 25px;">Վաճ.</th>
				<th style="vertical-align: middle; width: 25px;">Պահ.</th>
                <th style="vertical-align: middle; width: 25px;">Նախ. Պատվ․</th>
				<!-- <th style="vertical-align: middle; width: 30px;">Ջրդ.</th>
				<th style="vertical-align: middle; width: 30px;">Վն.</th> -->
				<th style="vertical-align: middle; width: 25px;">Վարձ.</th>
				<th style="vertical-align: middle; width: 25px;">Գ.</th>
				<?php
				$i = 0;
				$class = '';
				foreach($clients as $key => $client)
                //foreach($query_client_orders as $client)
				{
					if($i == 0)
					{
						$class = 'klass-erik';
					}
					else
					{
						$class = '';
					}
					echo '<th style="width: 30px;" data-client_id="'.$client_ids[$key].'" data-toggle="modal" data-target="#myModal" class="clickable '.$class.' client_info verticalTableHeader"><div>'.$client.'</div></th>';
					$i++;
				}
				/*foreach($clients_with_debt as $key => $value)
				{
					echo '<th style="width: 30px;" data-client_id="'.$value->id.'" data-toggle="modal" data-target="#myModal" class="clickable client_info verticalTableHeader"><div>'.$value->name.'</div></th>';
				}*/
/*
				echo '</tr></thead><tbody>';
				foreach($products as $key => $product)
				{
					echo '<tr><td data-toggle="modal" data-target="#myModal" class="clickable product_info" data-product_id="'.$product->id.'">'.$product->name.'</td>';
					echo '<td>'.($product->quantity+$product->new_quantity).'</td>';
					echo '<td>'.$product->sold_quantity.'</td>';
					echo '<td>'.($product->quantity+$product->new_quantity-$product->daily_order).'</td>';
                    
                    echo '<td>'.$product->preorder.'</td>';
					/*echo '<td>'.$product->useless_quantity.'</td>';
					echo '<td>'.$product->bad_quantity.'</td>';*/
                    
/*
					echo '<td data-id="'.$product->id.'" class="clickable daily_order">'.$product->daily_order.'</td>';
					echo '<td></td>';
					if(isset($res[$product->name]))
					{
						$i = 0;
						foreach($clients as $k => $client)
						{
							if($i == 0)
							{
								$class = 'klass-erik';
							}
							else
							{
								$class = '';
							}
							if(isset($res[$product->name][$client]))
							{
								echo '<td data-client_id="'.$client_ids[$k].'" data-product_id="'.$product->id.'"data-toggle="modal" data-target="#myModal" class="clickable '.$class.' product_client_info">'.$res[$product->name][$client].'</td>';
							}
							else
							{
								echo '<td data-client_id="'.$client_ids[$k].'" data-product_id="'.$product->id.'"data-toggle="modal" data-target="#myModal" class="clickable '.$class.' product_client_info"></td>';
							}
							$i++;
						}
					}
					else
					{
						$i = 0;
						foreach($clients as $k => $client)
						{
							if($i == 0)
							{
								$class = 'klass-erik';
							}
							else
							{
								$class = '';
							}
							echo '<td data-client_id="'.$client_ids[$k].'" data-product_id="'.$product->id.'"data-toggle="modal" data-target="#myModal" class="clickable '.$class.' product_client_info"></td>';
							$i++;
						}
					}
					/*for($i = 0; $i<count($clients_with_debt); $i++)
						echo '<td></td>';*/
/*
					echo '</tr>';
				}?>
			</tbody>
		</table>
	</div>
<?php
*/
///New///

//var_dump($this->input->post('start'));
$were = '';
//$if = '';
/*
if($this->input->post('start'))
{
    $start = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('start'))));
    //$this->db->where('orders.date >=', date('Y-m-d', strtotime($start)));
    $were .= " And date>='".$start."'"; 
    //$if .= " date>='".$start."'"; 
    
    //var_dump($start);
}
*/
if($this->input->post('end'))
{
    $end = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('end'))));
    //$this->db->where('orders.date <=', date('Y-m-d', strtotime($end)));
    $were .= " And date<='".$end."'"; 
    //$if .= " And date<='".$end."'";
}



$client_orders_sql = "SELECT c.id client_id, c.name as client_name, o.product_id, 
                    o.product_quantity, g.quantity giveback   
                    FROM clients c
                    LEFT JOIN (
                    SELECT client_id, product_id, sum(product_quantity)  product_quantity 
                    from orders 
                    WHERE daily_sale = 'daily' ".$were."
                    #and product_id = 318 AND client_id=4
                    GROUP BY client_id, product_id  
                    ) o on c.id = o.client_id 
                    left JOIN
                    (
                     SELECT gb.client_id, gb.product_id, SUM(gb.quantity) quantity FROM giveback gb
                     WHERE 1=1 ".$were."
                     GROUP BY gb.client_id, gb.product_id
                    )g ON g.client_id = o.client_id AND g.product_id = o.product_id
                    WHERE o.product_quantity>0 or g.quantity>0 
                    GROUP BY c.id, c.name, o.product_id"; //order by o.product_id
//echo $client_orders_sql;

$query_client_orders = $this->db->query($client_orders_sql)->result();  

$i = 0;
$th_clients = '';
$clients_data = [];
$client_names = [];
foreach($query_client_orders as $client){
    $clients_data[$client->client_id][$client->product_id] = $client->product_quantity-$client->giveback;
    $client_names[$client->client_id] = $client->client_name;
    }

foreach($client_names as $key=> $client){
	$class = ($i == 0)?'klass-erik':'';
	$th_clients .= '<th style="width: 30px;" data-client_id="'.$key.'" data-toggle="modal" data-target="#myModal" class="clickable '.$class.' client_info verticalTableHeader"><div>'.$client.'</div></th>';
	$i++;
}

/*Քան. Վաճ. Պահ. Նախ. Պատվ․ Վարձ.Գ. */

$sql = "SELECT p.id, p.name,   
        SUM(p.quantity) quantity,
        sum(p.new_quantity) new_quantity,
        #SUM(p.quantity)+sum(p.new_quantity) p_qty,
        
        #SUM(p.sold_quantity) sold_quantity,
        SUM(p.daily_order) p_daily_order,
        #o.product_quantity o_product_quantity,
        o.vacharq vacharq,
        o.naxnakan_vacharq,
        o.naxnakan_patver,
        o.vardzakalutyun - g.quantity vardzakalutyun,
        g.quantity g_quantity
        #o.client_ids
        FROM products p
        LEFT JOIN 
        (
        SELECT product_id, 
        #SUM(product_quantity) product_quantity,
        sum(if(is_preorder=0 AND daily_sale='daily', product_quantity, 0 )) vardzakalutyun,
        sum(if(is_preorder=0 AND daily_sale<>'daily', product_quantity, 0 )) vacharq,
        sum(if(is_preorder=1 AND daily_sale='daily', product_quantity, 0)) naxnakan_patver,
        sum(if(is_preorder=1 AND daily_sale<>'daily', product_quantity, 0)) naxnakan_vacharq
        
        FROM orders 
        WHERE 1 = 1 ".$were."
        GROUP BY product_id  #client_id, 
        ) o ON o.product_id = p.id 
        left JOIN
        (
        SELECT gb.product_id,   #gb.client_id, 
        SUM(gb.quantity) quantity 
        FROM giveback gb 
        where 1=1 ".$were."
        GROUP BY gb.product_id  #gb.client_id, 
        )g ON g.product_id = p.id
        #WHERE o.date >='2023-01-01'
        WHERE 1 = 1 #p.id <> 691
        GROUP BY p.id, p.name
        ORDER BY p.name asc";
$query1 = $this->db->query($sql);
    
$table2JsonData = [];
if($query1->num_rows()>0){
$k=0;
foreach($query1->result() as $product){
	$table2JsonData[$k] = [
		'<span data-toggle="modal" data-target="#myModal" class="clickable product_info" data-product_id="'.$product->id.'">'.$product->name.'</span>',
        $product->quantity+$product->new_quantity,
        $product->vacharq,
        $product->quantity+$product->new_quantity-$product->vardzakalutyun, //$product->quantity+$product->new_quantity-$product->daily_order, ///???? Պահ  
		$product->naxnakan_vacharq, //Նախն Վաճ․
        $product->vardzakalutyun, //Վարձ․
        $product->naxnakan_patver, //Նախ․ Վարձ․
        '', //Գ․
	];
    
    $i=0;
    foreach($client_names as $client_id=>$client){
            //array_push($table2JsonData[$k], $clients_data[$key][$q->id]);
            $class = ($i == 0)?'klass-erik':'';
            array_push($table2JsonData[$k], '<span data-client_id="'.$client_id.'" data-product_id="'.$product->id.'"data-toggle="modal" data-target="#myModal" class="clickable '.$class.' product_client_info">'.$clients_data[$client_id][$product->id].'</span>');
            $i++;
    }
    $k++;
}
}
?>
<div id="products_table">
<table id="tbl_id2" class="display table-striped table-sm" width="100%">
<thead>
	<tr>
		<th id='first-th' style="vertical-align: middle; width: auto;">Ապրանքի անուն</th>
		<th style="vertical-align: middle; width: 25px;">Քան.</th>
		<th style="vertical-align: middle; width: 25px;">Վաճ.</th>
		<th style="vertical-align: middle; width: 25px;">Պահ.</th>
        <th style="vertical-align: middle; width: 25px;">Նախն Վաճ</th>
		<th style="vertical-align: middle; width: 25px;">Վարձ.</th>
        <th style="vertical-align: middle; width: 25px;">Նախ. Վարձ</th>
		<th style="vertical-align: middle; width: 25px;">Գ.</th>
        <?=$th_clients;?>
   </tr>
</thead>
<tbody></tbody>
</table>
</div>

<script>
$(document).ready(function(){  
           
var table = $('#tbl_id2').DataTable({
      data: <?php echo json_encode($table2JsonData); ?>, 
      //scrollX: '100%',
	  //scrollCollapse: true,
      //responsive: true,
      "paging": true,
      "searching": true,
      "info": true,
      "ordering": true,
      //"paging": false,
      //"searching": false,
      //"info":     false,
      //"columnDefs":[{"width":"100%"}],
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