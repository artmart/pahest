<?php $this->load->view('header'); ?>
<div class="orders col-md-12 col-lg-12">
    <button id="new_order" class="btn btn-default">Նոր գործարք</button>
    <button class="goToMenu btn btn-default">Հետ</button>
</div>
<div class="order_popup col-md-12 col-lg-12" id="new_order_popup">
    <div class="col-md-12 col-lg-12" style="padding-left: 0px;">
        <button id="add_form" class="btn btn-success">+</button>
        <button id="del_form" class="btn btn-danger">-</button>
    </div>
    <form id="new_order_form" action="orders/new_order" method="post">
        <div id='clonedSection1' class='clonedSection col-md-3 col-lg-3'>
            Ընտրել կլիենտ:<br>
            <select id="client_to_pick1" name="client_to_pick1" class="client_to_pick"><br>
                <?php
                foreach($clients as $client)
                {
                    echo '<option value="'.$client->id.'">'.$client->name.'</option>';
                }
                ?>
            </select><br>
            Նոր կլիենտ:
            <input id="new_client_for_order1" class="new_client_for_order" type="checkbox" name="new_client1" /><br>
            <div id="new_client_name1" class="new_client_name" style="display: none;">
                Կլիենտի անունը:<br>
                <input type="text" name="new_client_name1"/><br>
                Օբյեկտ:
                <input id="own_client" class="own_client" type="checkbox" name="own_client1" /><br>
            </div>
            Ընտրել ապրանք:<br>
            <select id="product_to_pick1" class="product_to_pick" name="product_to_pick1"><br>
                <?php
                foreach($products as $product)
                {
                    echo '<option value="'.$product->id.'">'.$product->name.'</option>';
                }
                ?>
            </select><br>
            Ապրանքի քանակ:<br>
            <input id="product_quantity1" class="product_quantity" type="number" step="0.001" max="<?php echo $products[0]->quantity+$products[0]->new_quantity-$products[0]->daily_order; ?>" name="product_quantity1" />
            <span id="product_type1" class="product_type"><?php echo $products[0]->type; ?></span><br>
            Վարձակալություն:
            <input id="daily1" class="daily" type="checkbox" name="daily1" checked/>
            Վաճառք:
            <input id="sale1" class="sale" type="checkbox" name="sale1" /><br>
            <div id="product_price1" class="product_price" <?php if($clients[0]->own == 'yes') echo 'style="display: none;" data-show="false"'; ?>>
                Ապրանքի գին:<br>
                <input type="number" step="0.001" name="product_price1" /><br>
            </div>

            Ամսաթիվ:
            <div class="input-group date" data-provide="datepicker">
                <input type="text" class="form-control datepicker" name="date1">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <input type="submit" value="Հաստատել" class="btn btn-default"/>
        <input type="text" id="post_number" name="post_number" value="1" style="display: none;"/>
        <button id="close_new_order" class="btn btn-default">Փակել</button>
    </form>
</div>

<?php $this->load->view('footer'); ?>
