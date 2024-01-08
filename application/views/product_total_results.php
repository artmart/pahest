<style>
#first-th{
    width: 100% !important;
    min-width: 170px !important;
}
</style>
<?php
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