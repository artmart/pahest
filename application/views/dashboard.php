<?php $this->load->view('header'); ?>

<?php
$start = '';
$end = '';
if($this->input->post('start')){$start = $this->input->post('start');}
if($this->input->post('end')){$end = $this->input->post('end');}


//var_dump($products);
?>
<div id="container">
<div class="row">
    
    <div class="col-md-4" style="height: 45px; margin-top: 5px;">
	<button class="goToMenu btn btn-default">Հետ</button>
	<a href="debts"><button class="btn btn-default">Պարտքերի աղյուսակ</button></a>
	<a href="getproduct"><button class="btn btn-default">Ներմուծում</button></a><br><br>
    </div>
<form id="form_id" class="form-horizontal" action="/dashboard" method="POST" style="height: 45px; margin-top: 5px;">    
    <div class="col-md-7">
    <div class="row">
    <div class="col-md-4">
    <label for="start" class="col-sm-3 col-form-label">Սկիզբ։</label>
        <div class="input-group date col-sm-9" data-provide="datepicker1">
            <input type="text" data-auto-close="true"  class="form-control datepicker1" name="start" id="start" value="<?=$start;?>">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
    <label for="end" class="col-sm-3 col-form-label">Վերջ։</label>
        <div class="input-group date col-sm-9" data-provide="datepicker1">
            <input type="text" class="form-control datepicker1" name="end" id="end" value="<?=$end;?>">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>
    </div>
    
    <input type="submit" class="btn" value="Ֆիլտրել">
    </div>
  </div>
</form>  
  
</div>  
    
	<div id="products_table">
		<table id="tbl_id">
		<thead>
			<tr>
				<th id='first-th' style="vertical-align: middle; width: auto;">Ապրանքի անուն</th>
				<th style="vertical-align: middle; width: 25px;">Քան.</th>
				<th style="vertical-align: middle; width: 25px;">Վաճ.</th>
				<th style="vertical-align: middle; width: 25px;">Պահ.</th>
                <th style="vertical-align: middle; width: 25px;">Նախ. Վարձ</th>
                <th style="vertical-align: middle; width: 25px;">Նախ. Վաճ</th>
				<!-- <th style="vertical-align: middle; width: 30px;">Ջրդ.</th>
				<th style="vertical-align: middle; width: 30px;">Վն.</th> -->
				<th style="vertical-align: middle; width: 25px;">Վարձ.</th>
				<th style="vertical-align: middle; width: 25px;">Գ.</th>
				<?php
				$i = 0;
				$class = '';
				foreach($clients as $key => $client)
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
                    echo '<td>'.$product->presales.'</td>';
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

</div>
<script>
/*
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
    });*/
</script>
<?php $this->load->view('footer'); ?>

<script>
 $('.datepicker1').datepicker({
        "todayHighlight": true,
        "autoclose": true,
        "format": 'dd/mm/yyyy'

    });
    //$('.datepicker1').datepicker("setDate", '');
</script>