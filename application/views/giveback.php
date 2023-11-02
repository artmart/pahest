<?php $this->load->view('header'); ?>
<style>
.popup {
  position: relative;
}

.table > thead > tr > th {
  border-bottom: 1px solid black;
  border-top: 1px solid black !important;
}

.popup {
  width: 480px;
  }
  
.panel-group .panel {
  padding: 5px;
}
</style>
<div class="giveback">
    <button id="new_giveback" class="btn btn-default">Ապրանքի վերադարձ</button>
    <button class="goToMenu btn btn-default">Հետ</button>
</div>

<div class="popup" id="new_giveback_popup">

  <div class="panel-group">
    <div class="panel panel-default">

    <form id="new_giveback_form" action="giveback/new_giveback" class="form-horizontal" method="post">
    <div class="form-group">
        <label for="client_to_pick" class="control-label col-sm-6">Ընտրել կլիենտ:</label>
        <div class="col-sm-6">
        <select id="client_gives" name="client_to_pick" class="product_to_pick selectpicker" data-live-search="true"><br>
            <option selected disabled>Ընտրել</option>
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
        <label for="product_to_pick" class="control-label col-sm-6">Ընտրել ապրանք:</label>
        <div class="col-sm-6">
        <select id="product_given" name="product_to_pick" class="product_to_pick selectpicker" data-live-search="true"><br>
            <option selected disabled>Ընտրել</option>
            <?php
            foreach($products as $product)
            {
                echo '<option value="'.$product->id.'">'.$product->name.'</option>';
            }
            ?>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label for="product_to_pick" class="control-label col-sm-6">Ապրանքի քանակ:</label>
        <div class="col-sm-6">
        <input id="product_q" class="form-control"  type="number" step="0.01" name="product_quantity" />
        </div>
    </div>
    <div class="form-group">
        <label for="product_to_pick" class="control-label col-sm-6">Ապրանքի գին:</label>
        <div class="col-sm-6">
        <input id="product_p" class="form-control"  type="number" name="product_price" />
        </div>
    </div>
    <div class="form-group">
        <label for="product_to_pick" class="control-label col-sm-6">Վնասվածների քանակ:</label>
        <div class="col-sm-6">
        <input id="bad_q" type="number" class="form-control"  step="0.01" name="bad_quantity" />
        </div>
    </div>
    <div class="form-group">
        <label for="product_to_pick" class="control-label col-sm-6">Ջարդվածների քանակ:</label>
        <div class="col-sm-6">
        <input id="useless_q" type="number" class="form-control"  step="0.01" name="useless_quantity" />
        </div>
    </div>
    <div class="form-group">
        <label for="product_to_pick" class="control-label col-sm-6">Ամսաթիվ:</label>
        <div class="col-sm-6">
        <div class="input-group date" data-provide="datepicker">
            <input type="text" class="form-control datepicker" name="date">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-6"></label>
        <div class="col-sm-6">
        <input type="submit" value="Հաստատել"  class="btn btn-default col-sm-6"/>
        <button id="close_new_giveback" class="btn btn-default col-sm-6">Փակել</button>
        </div>
    </div>
    </form>
    
</div>
</div>
</div>

<div class="row">
<hr />
<div class="container" style="width: 700px;">
    <div id="products"></div>
</div>
</div>


<?php $this->load->view('footer'); ?>
