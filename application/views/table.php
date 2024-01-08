<?php
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 300);
$this->load->view('header'); 

//$start = date('d/m/Y');
$end = date('d/m/Y');
//if($this->input->post('start')){$start = $this->input->post('start');}
if($this->input->post('end')){$end = $this->input->post('end');}
/*
$categories = $this->db->query('select distinct Category from products_landing where Category not in("null", "#N/A", "")');
$category = '';
if ($categories->num_rows() > 0) {
	foreach ($categories->result() as $c) {
		$category .= "<option value='" . $c->Category . "'>" . $c->Category . "</option>";
	}
}

$skus = $this->db->query("select distinct Sku from products_landing");
$sku = '';
if ($skus->num_rows() > 0) {
	foreach ($skus->result() as $s) {
		$sku .= "<option value='" . $s->Sku . "'>" . $s->Sku . "</option>";
	}
}

$sale_statuses = $this->db->query("select distinct status_group from status_groups;");
$sale_status = '';
if ($sale_statuses->num_rows() > 0) {
	foreach ($sale_statuses->result() as $s) {
		$sale_status .= "<option value='" . $s->status_group . "'>" . $s->status_group . "</option>";
	}
}
*/
?>

<div class="body-content animated fadeIn" style="min-height: 985px;">

<?php /*
	<form id="form_id" class="form-inline">
		<div class="well">

			<div class="form-group">
				<select class="form-control" id="category" name="category" onchange="skusUpdate()">
					<option value="">All</option>
					<?php echo $category; ?>
				</select>
			</div>

			<div class="form-group">
				<div id="sku1">
					<select class="selectpicker show-menu-arrow  form-control" multiple data-actions-box="true" data-selected-text-format="count" id="sku[]" name="sku[]" data-show-subtext="true" data-live-search="true" onchange="showValues()">						
                        <?php echo $sku; ?>
					</select>
				</div>
			</div>

			<div class="form-group ">
				<input type="hidden" id="start" name="start" value="">
				<input type="hidden" id="end" name="end" value="">
				<div id="reportrange" class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" onchange="showValues()">
					<i class="fa fa-calendar"></i>&nbsp;
					<span></span> <i class="fa fa-caret-down"></i>
				</div>
			</div>

			<div class="form-group">
				<!--<label for="sel1">Select list:</label>-->
				<select class="form-control" id="frequency" name="frequency" onchange="showValues()">
					<option value="daily">Daily</option>
					<option value="weekly">Weekly</option>
					<option value="monthly">Monthly</option>
					<option value="custom">Custom</option>
                    <option value="not_summarize">Not Summarize</option>
				</select>
			</div>

			<div class="form-group">
				<select class="form-control" id="sale_status" name="sale_status" onchange="showValues()">
					<option value="" selected="selected">All</option>
                    <?php echo $sale_status; ?>
					<!--<option value="Shipped">Shipped</option>
					<option value="Cancelled">Cancelled</option>
					<option value="Returned">Returned</option>-->
				</select>
			</div>

		</div>
	</form>
 */ ?>
    
<form id="my_form_id" class="form-horizontal" style="height: 45px; margin-top: 5px;">    
    <div class="col-md-7">
    <div class="row">
    <?php /*
    <div class="col-md-4">
    <label for="start" class="col-sm-3 col-form-label">Սկիզբ։</label>
        <div class="input-group date col-sm-9" data-provide="datepicker1">
            <input type="text" data-auto-close="true"  class="form-control datepicker1" name="start" id="start" value="<?=$start;?>">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>
    </div>
    */ ?>

    <div class="col-md-4">
    <label for="end" class="col-sm-3 col-form-label">Ամսաթիվ։</label>
        <div class="input-group date col-sm-9" data-provide="datepicker1">
            <input type="text" class="form-control datepicker1" name="end" id="end" value="<?=$end;?>">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>
    </div>
    
    <input class="btn btn-default" onclick="showValues()" value="Ֆիլտրել">
    </div>
  </div>
</form> 
<hr />
<div id="my_loader" class="justify-content-md-center text-center" style="display: none;">
    <img src="<?=base_url() ?>assets/images/ajaxloader.gif" />Loading...
</div>  
	<div id="calc-results" class="row"></div>
</div>
<?php $this->load->view('footer'); ?>
<script type="text/javascript">
$("#my_form_id").submit(function() {return false;});

	function showValues(){

		var data = $("#my_form_id").serialize();
        $('.datepicker-dropdown').hide();
		$.ajax({
			type: 'post',
			url: '<?=base_url() ?>dashboardnew/tableresults',
			data: data,
			beforeSend: function() {
				$("#my_loader").css("display", "block");
			},
			success: function(response) {
				$("#my_loader").css("display", "none");
				$('#calc-results').html(response);
			}
		});
	}

	$(document).ready(function() {
	    $('.datepicker1').datepicker({
            "todayHighlight": true,
            "autoclose": true,
            "format": 'dd/mm/yyyy'
        });

    showValues();
	});
</script>