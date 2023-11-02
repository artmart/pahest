<?php $this->load->view('header'); ?>
<style>
.popup {
  width: 400px;
  }
  
.panel-group .panel {
  padding: 5px;
}
</style>
<br />
<div class="popup" id="new_payment_popup">
  <div class="panel-group">
    <div class="panel panel-default">
    <form id="new_payment_form" class="form-horizontal" action="payment/new_payment" method="post">
    <div class="form-group">
        <label for="amount" class="control-label col-sm-5">Ընտրել կլիենտ:</label>
        <div class="col-sm-7">
        <select id="client_pays" class="form-control product_to_pick selectpicker" data-live-search="true" name="client_to_pick">
            <?php
            foreach($clients as $client)
            {
                echo '<option value="'.$client->id.'">'.$client->name.'</option>';
            }
            ?>
        </select>
        </div>
    </div>
        
    <div class="form-group">
        <label for="amount" class="control-label col-sm-5">Գին:</label>
        <div class="col-sm-7">
        <input type="number" class="form-control" step="1" name="amount" />
        </div>
    </div>
    <div class="form-group">    
        <label for="date" class="control-label col-sm-5">Ամսաթիվ:</label>
        <div class="col-sm-7">
        <div class="input-group date" data-provide="datepicker">
            <input type="text" class="form-control datepicker" name="date">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>
        </div>
    </div>
    <div class="form-group">
        <label for="discount" class="control-label col-sm-5">Զեղչ:</label>
        <div class="col-sm-2">
        <input id="discount" class="form-control pull-left" type="checkbox" value="1" name="discount" />
        </div>
        <div class="col-sm-5">        
        <select id="discount_type" class="form-control" name="discount_type" style="margin-top: 4px;">
                <option value="">- Ընտրել -</option>
                <option value="0">զեղչ</option>
                <option value="1">դադար</option>
        </select>
        </div>
    </div>
    <div class="form-group">  
    <div class="col-sm-5"></div>
    <div class="col-sm-7">
        <input type="submit" value="Հաստատել" class="btn btn-default col-sm-6" />
        <button id="close_new_payment" class="btn btn-default col-sm-6">Փակել</button>
    </div>
    </div>
    </form>
</div>
</div>
</div>
<div class="payment">
    <button id="new_payment" class="btn btn-default">Նոր վճարում</button>
    <button class="goToMenu btn btn-default">Հետ</button>
</div>

<?php $this->load->view('footer'); ?>

<script>


$(document).ready(function() {
    $('#discount_type').hide();
    $(document).on("click", "#discount", function(e) {
       if ($('#discount').is(':checked')) {
           $('#discount_type').show();
       } else {
           $('#discount_type').hide();
       }
    });
});
</script>
