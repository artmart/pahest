<?php $this->load->view('header'); ?>
    <h4 id="client_debt">Ներմուծում</h4>
    <div id="get_product_table">
        <!--<div class="input-group date" style="width: 200px;" data-provide="datepicker">
            <input type="text" id="debts_date" value="<?php /*echo $datepicker_value; */?>" class="form-control datepicker" name="debts_date">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>-->
        <div class="col-md-6">
            <table class="table">
                <thead>
                <th>Ապրանք</th>
                <th>Քանակ</th>
                </thead>
                <tbody>
                <?php
                foreach($query as $value)
                {
                    echo '<tr data-product_name="'.$value->product_name.'" data-toggle="modal" data-target="#myModal" class="clickable get_product"><td>'.$value->product_name.'</td><td>'.$value->product_quantity.'</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div>
        <a href="dashboard"><button class="btn btn-default">Հետ</button></a>
    </div>

<?php $this->load->view('footer'); ?>