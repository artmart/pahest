<style>
table.dataTable.no-footer {
  border: 1px solid #111;
}
</style>

<?php $this->load->view('header'); ?>
<div class="container">
<br />
<form id="form_id" class="form-inline col-md-6">
    <h4 id="client_debt" class="col-md-5">Պարտքերի ցուցակ</h4>
    <div class="input-group date col-md-6" data-provide="datepicker">
        <input type="text" id="debts_date" value="<?php echo $datepicker_value; ?>" class="form-control datepicker" name="debts_date">
        <div class="input-group-addon">
            <span class="glyphicon glyphicon-th"></span>
        </div>
    </div>

</form>
    <div class="giveback col-md-2">
    <a href="dashboard"><button class="btn btn-default">Հետ</button></a>
    </div>
<div class="row">
<div class="col-md-12">
    
    <hr />

<div id="my_loader" class="justify-content-md-center text-center" style="display: none;">
    <img src="<?=base_url() ?>/assets/images/ajaxloader.gif" />Loading...
</div>   
    <div id="calc-results"></div>
</div>
</div>
</div>


<?php $this->load->view('footer'); ?>
<script>
	$("#form_id").submit(function() {return false;});

	function showValues() {
		var data = $("#form_id").serialize();
        $('.datepicker-dropdown').hide();
		$.ajax({
			type: 'post',
			url: '<?=base_url() ?>debts/debts_results',
			data: data,
			beforeSend: function() {
				$("#my_loader").css("display", "block");
			},
			success: function(response){
				$("#my_loader").css("display", "none");
				$('#calc-results').html(response);
                
			}
		});
	}
    
 /*   
    $(document).on('change', '#debts_date', function(){
        
        showValues();
        
        $('.datepicker').datepicker({
            "todayHighlight": true,
            "autoclose": true,
            "format": 'dd/mm/yyyy'
        });
        
    })
   */ 
 	$(document).ready(function() {
	    $('.datepicker').datepicker({
            "todayHighlight": true,
            "autoclose": true,
            "format": 'dd/mm/yyyy'
        });
    //$("#my_loader").css("display", "none");
    showValues();
	});
    


</script>