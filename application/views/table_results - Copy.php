<?php
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
					echo '</tr>';
				}
			?>
			</tbody>
		</table>
	</div>




<?php
///New///

$client_orders_sql = "SELECT c.id client_id, c.name as client_name, o.product_id, 
                    sum(o.product_quantity)  product_quantity, sum(g.quantity) giveback   
                    FROM clients c
                    LEFT JOIN (
                    SELECT client_id, product_id, sum(product_quantity)  product_quantity 
                    from orders WHERE daily_sale = 'daily'
                    #and product_id = 318 AND client_id=4
                    GROUP BY client_id, product_id  
                    ) o on c.id = o.client_id 
                    
                    #inner JOIN clients c ON c.id = o.client_id 
                    left JOIN
                     (
                     SELECT gb.client_id, gb.product_id, SUM(gb.quantity) quantity FROM giveback gb
                     #WHERE gb.client_id = 4 AND gb.product_id=318
                     GROUP BY gb.client_id, gb.product_id
                     )g ON g.client_id = o.client_id AND g.product_id = o.product_id
                     
                    GROUP BY c.id, c.name, o.product_id"; //order by o.product_id
       
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

$sql = "SELECT p.id, p.name, SUM(DISTINCT p.quantity) quantity, sum(distinct p.new_quantity) new_quantity,
        SUM(o.product_quantity) product_quantity,
        sum(if(o.daily_sale='daily', o.product_quantity, 0 )) vardzakalutyun,
        sum(if(o.daily_sale<>'daily', o.product_quantity, 0)) vacharq,
        sum(if(o.is_preorder=1 AND o.daily_sale='daily', o.product_quantity, 0)) naxnakan_patver,
        sum(if(o.is_preorder=1 AND o.daily_sale<>'daily', o.product_quantity, 0)) naxnakan_vacharq
        FROM products p
        left JOIN orders o ON o.product_id = p.id
         #WHERE o.date >='2023-01-01'
         #WHERE p.id = 309
        GROUP BY p.id, p.name
        ORDER BY p.id asc";


/*
echo '<td>'.($product->quantity+$product->new_quantity).'</td>';
echo '<td>'.$product->sold_quantity.'</td>';
echo '<td>'.($product->quantity+$product->new_quantity-$product->daily_order).'</td>';
echo '<td>'.$product->preorder.'</td>';
echo '<td data-id="'.$product->id.'" class="clickable daily_order">'.$product->daily_order.'</td>';
*/
	$query1 = $this->db->query($sql);
    

$table2JsonData = [];
	if ($query1->num_rows() > 0) {
		//$show_table = true;
        $k=0;
		foreach($query1->result() as $product) {
			$table2JsonData[$k] = [
				'<span data-toggle="modal" data-target="#myModal" class="clickable product_info" data-product_id="'.$product->id.'">'.$product->name.'</span>',
				$product->quantity+$product->new_quantity,
				$product->product_quantity,
				$product->vardzakalutyun,
				$product->vacharq,
				$product->quantity,
				$product->naxnakan_patver,
				//$q->naxnakan_vacharq,

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
    
    //var_dump($table2JsonData);
?>

	<div id="products_table">
		<table id="tbl_id2" class="display table-striped table-sm" width="100%">
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
                <?=$th_clients;?>
	       </tr>
		</thead>
		<tbody></tbody>
	</table>
</div>


<script>

	$(document).ready(function() {  
      $('#tbl_id').DataTable({
          "ordering": true,
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
        
       
      
    var table = $('#tbl_id2').DataTable({
          data: <?php echo json_encode($table2JsonData); ?>, 
          scrollX: '100%',
			scrollCollapse: true,
			//responsive: true,
			"paging": true,
			"searching": true,
			"info": true,
          "ordering": true,
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
<?php


/*
	if (isset($_REQUEST['start']) && $_REQUEST['start'] !== '') {
		$start = $_REQUEST['start'];
		$where1 .= " and STR_TO_DATE(order_date,'%d %M %Y')>='$start' ";
	}
	if (isset($_REQUEST['end']) && $_REQUEST['end'] !== '') {
		$end = $_REQUEST['end'];
		$where1 .= " and STR_TO_DATE(order_date,'%d %M %Y')<='$end' ";
	}
    
    $date1 = new DateTime($start);
    $date2 = new DateTime($end);
    $days  = $date2->diff($date1)->format('%a');

	$frequency = $_REQUEST['frequency'];
	if ($frequency == 'daily') {
		$day_week_month = 'Day';
		$sql_part = " DATE_FORMAT( STR_TO_DATE(order_date,'%d %M %Y'), '%Y-%m-%d') ";
	}
	if ($frequency == 'weekly') {
		$day_week_month = 'Week';
		$sql_part = " DATE_FORMAT( STR_TO_DATE(order_date,'%d %M %Y'), '%Y-%u') ";
	}
	if ($frequency == 'monthly') {
		$day_week_month = 'Month';
		$sql_part = " DATE_FORMAT( STR_TO_DATE(order_date,'%d %M %Y'), '%Y-%m') ";
	}
    
    if($frequency == 'custom'){
        $day_week_month = 'days';
		$sql_part = " CONCAT($days, '-days') ";
    }
    
    if($frequency == 'not_summarize'){
        $day_week_month = 'days';
		$sql_part = " DATE_FORMAT( STR_TO_DATE(order_date,'%d %M %Y %T'), '%Y-%m-%d %T')  ";
    }
    
  	if (isset($_REQUEST['category']) && $_REQUEST['category'] !== '') {
		$category = $_REQUEST['category'];
		$where2 .= " and  p.Category ='$category' ";
	}

	$show_totals = true;
	if(isset($_REQUEST['sale_status']) && $_REQUEST['sale_status'] !== '') {
		$sale_status = $_REQUEST['sale_status'];
        $where1 .= " and g.status_group='$sale_status' ";
		if ($sale_status !== 'Shipped'){$show_totals = false;}
    }


	?>


	<div class="row">
		<div class="col-md-12">

			<div class="panel panel-default shadow">
				<div class="panel-heading">
					<div class="pull-left">
						<h3 class="panel-title">Sales Detailed Results</h3>
					</div><!-- /.pull-left -->
					<div class="clearfix"></div>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<div class="ct-chart ct-month-inventory">
						<?php if ($show_table) { ?>
							<!-- data table 2-->
							<table id="example2" class="table  table-success dataTable draggable" width="100%">
								<thead>
									<tr>
										<th><strong>Category</strong></th>
										<th><strong>SKU</strong></th>
										<th><strong>Status</strong></th>
										<th><strong>Date (<?php echo $day_week_month; ?>)</strong></th>
										<th><strong>Unit Price</strong></th>
										<th><strong>Qty</strong></th>
										<th><strong>Total Sales</strong></th>
										<th><strong>Landed Cost Per SKU</strong></th>
										<th><strong>Total Product Cost</strong></th>
										<th><strong>Total Paid to GretMol Inc VAT</strong></th>
										<th><strong>Total Paid to GretMol Ex VAT</strong></th>
										<th><strong>Total Gross ProfitEx VAT</strong></th>
										<th><strong>TakealotFees</strong></th>
										<th><strong>Margin Ex VAT</strong></th>
										<th><strong>Margin Inc VAT</strong></th>
										<th><strong>GP on Takealot Payout (Vat inc)</strong></th>
										<th><strong>GP%</strong></th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						<?php } else {
							echo "<center><img style='height: 100%;' src='".base_url()."assets/images/nodata.png'/></center>";
						} ?>
					</div>
				</div><!-- /.panel-body -->
			</div>
		</div>
	</div>

<script>
/*



	//Datatable//
	$(document).ready(function() {
		var table = $('#example2').DataTable({
			data: <?php echo json_encode($table2JsonData); ?>,            
			scrollX: '100%',
			scrollCollapse: true,
			responsive: true,
			"ordering": true,
			"paging": true,
			"searching": true,
			"info": true,
            //colReorder: true,
			"columnDefs": [
				{targets: 0, width: 150},
				{targets: 1, width: 100},
				{targets: 2, width: 100},
				{targets: 3, width: 100},
				{targets: 4, width: 100},
				{targets: 5, width: 100},
				{targets: 6, width: 100},
				{targets: 7, width: 100,
				/*	"createdCell": function (td, cellData, rowData, row, col) {
						if (!(cellData > 0)){
							$(td).css('background-color', 'fuchsia')
						}else{$(td).css('background-color', '#fff')}
					} *//*
				},
				{targets: 8, width: 100},
				{targets: 9, width: 100},
				{targets: 10, width: 100},
				{targets: 11, width: 100},
				{targets: 12, width: 100},
				{targets: 13, width: 100},
				{targets: 14, width: 100},
				{targets: 15, width: 100},
				{targets: 16, width: 100}
			],
			dom: "RflBrtip",
			//'colReorder': true,
			//"pageLength": 100,
			buttons: [
				{extend: "copy", className: "btn-sm"},
				{extend: "csv", className: "btn-sm"},
				{extend: "excel", className: "btn-sm"},
			],

		});
		//$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
	});
</script>

*/ ?>