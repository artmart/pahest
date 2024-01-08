<style>
.mini-stat-type-6 > .inner {
  padding: 10px;
}

.bg-success {
  background-color: #8CC152 !important;
  border: 1px solid #8CC152;
  color: white;
}

.mini-stat-type-6 .icon {
  position: absolute;
  top: 20px;
  right: 25px;
  z-index: 0;
  font-size: 50px;
  color: rgba(0, 0, 0, 0.15);
}

.mini-stat-type-6 .small-box-footer {
  position: relative;
  text-align: center;
  padding: 10px 0;
  font-size: 15px;
  color: #fff;
  color: rgba(255, 255, 255, 0.8);
  display: block;
  z-index: 10;
  background: rgba(0, 0, 0, 0.1);
  text-decoration: none;
}

.marketing-body {
  padding: 20px;
  margin-bottom: 100px !important;
}

.bg-info {
  background-color: #63D3E9 !important;
  border: 1px solid #63D3E9;
  color: white;
}

.bg-danger {
  background-color: #E9573F !important;
  border: 1px solid #E9573F;
  color: white;
}

.bg-warning {
  background-color: #F6BB42 !important;
  border: 1px solid #F6BB42;
  color: white;
}
</style>

<?php
ini_set('memory_limit', '512M');
	ini_set('max_execution_time', 3000);
//var_dump($_REQUEST);
	$products = 'all';
	if (isset($_REQUEST['products'])) {
		$products = implode("', '", $_REQUEST['products']);
	}
    $vacharq = 0;
    $vardzakalutyun_qanak = 0;
	$vardzakalutyun_gumar = 0;
    
    $vacharq_trend = [];
    $vardzakalutyun_trend = [];
	$vardzakalutyun_gumar_trend = [];
	$chart_categories = [];
    
	$show_table = false;
	$show_chart = false;

	$where = ' 1 = 1 ';

	if ($products !== 'all') {
		$where .= "  and p.id in('$products') ";
	}

	if (isset($_REQUEST['start']) && $_REQUEST['start'] !== '') {
		$start = date("Y-m-d",strtotime( str_replace('/', '-', $_REQUEST['start'])));
		$where .= " and o.date>='$start' "; //STR_TO_DATE(o.date,'%d %M %Y')
	}
	if (isset($_REQUEST['end']) && $_REQUEST['end'] !== '') {
		$end = date("Y-m-d",strtotime( str_replace('/', '-', $_REQUEST['end'])));
		$where .= " and o.date<='$end' "; //STR_TO_DATE(o.date,'%d %M %Y')
	}
    
    $date1 = new DateTime($start);
    $date2 = new DateTime($end);
    $days  = $date2->diff($date1)->format('%a');

	$frequency = $_REQUEST['frequency'];
	if ($frequency == 'daily') {
		$day_week_month = 'Օր';
		$sql_part = " DATE_FORMAT( STR_TO_DATE(o.date,'%Y-%m-%d'), '%Y-%m-%d') ";
	}
	if ($frequency == 'weekly') {
		$day_week_month = 'Շաբաթ';
		$sql_part = " DATE_FORMAT( STR_TO_DATE(o.date,'%Y-%m-%d'), '%Y-%u') ";
	}
	if ($frequency == 'monthly') {
		$day_week_month = 'Ամիս';
		$sql_part = " DATE_FORMAT( STR_TO_DATE(o.date,'%Y-%m-%d'), '%Y-%m') ";
	}
    
    
    
/*    
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

	//table results//   
    /*        
	$sql = "select p.Category category, o.sku sku, o.sale_status, o.tstamp tmstamp, p.landedPrice, o.selling_price, o.quantity, o.cnt,
            p.successFees, p.handling_fee, p.landedPrice*o.quantity total_product_cost
        from products_landing p
        inner join 
        (select sku, g.status_group sale_status, " . $sql_part . " tstamp, sum(quantity) quantity, sum(selling_price) selling_price, count(order_id) cnt 
            from takealots_orders 
            inner join status_groups g on g.`sale_status` = takealots_orders.`sale_status`
            where $where1 
            group by sku, sale_status, tstamp) o on p.Sku = o.sku
        where $where2 "; */
        
 $sql = "SELECT ".$sql_part." dt, 
            round(sum(if(is_preorder=0 AND daily_sale='daily', product_quantity*daily_price, 0 ))) vardzakalutyun_gumar,
            sum(if(is_preorder=0 AND daily_sale='daily', product_quantity, 0 )) vardzakalutyun_qanak,
            sum(if(is_preorder=0 AND daily_sale<>'daily', product_quantity, 0 )) vacharq
            FROM orders o
            INNER JOIN products p ON p.id = o.product_id
            WHERE ".$where." 
           
            GROUP BY ".$sql_part."
            ORDER BY ".$sql_part." asc";
        //o.date>='2023-01-01' AND o.date<='2023-12-01'  #o.daily_sale = 'daily'  AND p.id IN('555', '421', '435')
 //echo $sql;
         
	$query = $this->db->query($sql);
	if ($query->num_rows() > 0) {
		$show_table = true;
		$table2JsonData = array();
		
		foreach ($query->result() as $q) {
		    $td_style = "";
			$unit_price = 0;
            $vacharq = $vacharq + $q->vacharq;
            $vardzakalutyun_qanak = $vardzakalutyun_qanak + $q->vardzakalutyun_qanak;
	        $vardzakalutyun_gumar = $vardzakalutyun_gumar + $q->vardzakalutyun_gumar;
            $chart_categories[] = $q->dt;
            
            $vacharq_trend[] = floatval($q->vacharq);
            $vardzakalutyun_trend[] = floatval($q->vardzakalutyun_qanak);
	        $vardzakalutyun_gumar_trend[] = floatval($q->vardzakalutyun_gumar);

			$table2JsonData[] = array(
				$q->dt,
				$q->vacharq,
				$q->vardzakalutyun_qanak,
				$q->vardzakalutyun_gumar
			);
		}
	}

$show_chart = true;
	?>
    <div class="marketing-body">
    <div class="row1">
        <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
            <div class="mini-stat-type-6 bg-success shadow">
                <div class="inner">
                    <h4>Վաճառք (Քանակ)</h4>
                    <h3><?php echo number_format($vacharq); ?></h3>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
            <div class="mini-stat-type-6 bg-info shadow">
                <div class="inner">
                    <h4>Վարձակալություն (Քանակ)</h4>
                    <h3><?php echo number_format($vardzakalutyun_qanak); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
            <div class="mini-stat-type-6 bg-danger shadow">
                <div class="inner">
                    <h4>Վարձակալություն (Գումար)</h4>
                    <h3><?php echo number_format($vardzakalutyun_gumar); ?> Դրամ</h3>
                </div>
            </div>
        </div>
    </div>
    </div>
  
	<div class="row1">
		<div class="col-md-12">
			<div class="panel panel-default shadow">
				<div class="panel-heading">
					<div class="pull-left">
						<h3 class="panel-title">Վաճառքի և վարձակայության ընդհանուր ցուցանիշներ</h3>
					</div><!-- /.pull-left -->
					<div class="pull-right">
						<button class="btn btn-sm option" id="line" data-toggle="tooltip" data-placement="top" data-title="Line Chart"><i class="fa fa-line-chart" aria-hidden="true"></i></button>
						<button class="btn btn-sm option" id="bar" data-toggle="tooltip" data-placement="top" data-title="Bar Chart"><i class="fa fa-bar-chart fa-rotate-90" aria-hidden="true"></i></button>
						<button class="btn btn-sm option" id="column" data-toggle="tooltip" data-placement="top" data-title="Column Chart"><i class="fa fa-bar-chart" aria-hidden="true"></i></button>
					</div><!-- /.pull-right -->
					<div class="clearfix"></div>
				</div><!-- /.panel-heading -->
				<div class="panel-body" style="padding: 0;">
					<div class="ct-chart ct-month-inventory" style="height: 370px;">
						<?php if ($show_chart) { ?>
							<!--Highchart container-->
							<div id="container1" style="min-width: 100%; height: 100%; margin: 0 auto"></div>
						<?php } else {
							echo "<center><img style='height: 100%;' src='".base_url()."assets/images/nodata.png'/></center>";
						} ?>
					</div>
				</div><!-- /.panel-body -->
			</div>
		</div>
        
	</div>
	<div class="row1">
		<div class="col-md-12">

			<div class="panel panel-default shadow">
				<div class="panel-heading">
					<div class="pull-left">
						<h3 class="panel-title">Վաճառքի և վարձակայության ցուցանիշներն ըստ ապրանքների</h3>
					</div><!-- /.pull-left -->
					<div class="clearfix"></div>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<div class="ct-chart ct-month-inventory">
						<?php if ($show_table) { ?>
							<!-- data table 2-->
							<table id="example2" class="table-success dataTable table-striped" width="100%">
								<thead>
									<tr>
										<th><strong><?=$day_week_month;?></strong></th>
										<th><strong>Վաճառք (Քանակ)</strong></th>
										<th><strong>Վարձակալություն (Քանակ)</strong></th>
										<th><strong>Վարձակալություն (Գումար)</strong></th>
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
	//Highchart//
	$(function() {
		var chartype = 'line';

		setDynamicChart(chartype);

		$('.option').click(function() {
			var chartype = $(this).attr('id');
			setDynamicChart(chartype);
		});

		function setDynamicChart(chartype) {
			Highcharts.setOptions({
				lang: {
					decimalPoint: '.',
					thousandsSep: ','
				}
			});

			Highcharts.chart('container1', {
				colors: ['#81b71a', '#24CBE5',  '#E9573F', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
				chart: {
					type: chartype
				},
				title: {
					text: ''
				},
				subtitle: {
					text: ''
				},
				xAxis: {
					type: 'category',
                    //categories: [''],
					categories: <?= json_encode($chart_categories); ?>, scrollbar: {
						enabled: true
					},
					min: 0,
					//max: 1
				},
				yAxis: {
					//type: 'category',
					title: {
						text: ''
					},
					labels: {
						formatter: function() {
							return Highcharts.numberFormat(this.value, 0);
						}
					},
					scrollbar: {enabled: true},
					min: 0,
					//max: 500,
				},
                legend: {
                    enabled: true
                },
				plotOptions: {
					line: {
						dataLabels: {
							enabled: true
						} /*, enableMouseTracking: true*/
					},
                    series: {
    			   	     turboThreshold: 0
    			     }
				},
				tooltip: {
					yDecimals: 2, // If you want to add 2 decimals
					valueDecimals: 2
				},
				credits: {
					enabled: false
				},
				series: [
                            {name: 'Վաճառք (Քանակ)', data: <?php echo json_encode($vacharq_trend); ?>},
                            {name: 'Վարձակալություն (Քանակ)', data: <?php echo json_encode($vardzakalutyun_trend); ?>},
                            {name: 'Վարձակալություն (Գումար)', data: <?php echo json_encode($vardzakalutyun_gumar_trend); ?>},
				]
			});
		}
	});

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
		/*	"columnDefs": [
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
			],*/
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