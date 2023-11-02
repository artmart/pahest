<?php $this->load->view('header'); ?>
<div class="popup" id="new_product_popup">
    <form id="new_product_form" action="products/new_product" method="post">
        Անուն:<br>
        <input type="text" name="name" /><br>
        Չափման միավոր:<br>
        <input type="text" name="type" /><br>
        Քանակ:<br>
        <input type="number" step="0.001" name="quantity" /><br>
        Նոր:<br>
        <input type="number" step="0.001" name="new_quantity" /><br>
        Ներմուծում:<br>
        <input type="number" step="0.001" name="get_quantity" /><br>
        Վատ վիճակում:<br>
        <input type="number" step="1" name="bad_quantity" /><br>
        Ոչ պիտանի:<br>
        <input type="number" step="1" name="useless_quantity" /><br>
        <input type="submit" value="Հաստատել" class="btn btn-default"/>
        <button id="close_new_product" class="btn btn-default">Փակել</button>
    </form>
</div>
<div class="popup" id="edit_product_popup">
    <form id="edit_product_form" action="products/edit_product/<?php echo $products[0]->id; ?>" method="post">
        Ընտրել ապրանք:<br>
        <select id="product_to_edit" class="product_to_pick selectpicker" data-live-search="true"><br>
            <?php
            foreach($products as $product)
            {
                echo '<option value="'.$product->id.'">'.$product->name.'</option>';
            }
            ?>
        </select><br>
        Անուն:<br>
        <input id="edit_product_name" type="text" name="name" value="<?php echo $products[0]->name; ?>"/><br>
        Չափման միավոր:<br>
        <input id="edit_type" type="text" name="type" value="<?php echo $products[0]->type; ?>"/><br>
        Քանակ:<br>
        <input id="edit_quantity" type="number" step="0.001" name="quantity" value="<?php echo $products[0]->quantity; ?>"/><br>
        Նորերի քանակ:<br>
        <input id="edit_new_quantity" type="number" step="0.001" name="new_quantity" value="<?php echo $products[0]->new_quantity; ?>"/><br>
        Ներմուծում:<br>
        <input type="number" step="0.001" name="get_quantity" /><br>
        Վատ վիճակում:<br>
        <input id="edit_bad_quantity" type="number" step="1" name="bad_quantity" value="<?php echo $products[0]->bad_quantity; ?>" /><br>
        Ոչ պիտանի:<br>
        <input id="edit_useless_quantity" type="number" step="1" name="useless_quantity" value="<?php echo $products[0]->useless_quantity; ?>"/><br>
        <input type="submit" class="btn btn-default" value="Հաստատել" />
        <button id="close_edit_product" class="btn btn-default">Փակել</button>
    </form>
</div>
<div class="products">
    <button id="new_product" class="btn btn-default">Նոր ապրանք</button>
    <button id="edit_product" class="btn btn-default">Փոփոխել</button>
    <button class="goToMenu btn btn-default">Հետ</button>
</div>

<?php $this->load->view('footer'); ?>
