<?php $this->load->view('header'); ?>
<div class="popup" id="new_client_popup">
    <form id="new_client_form" action="clients/new_client" method="post">
        Անուն:<br>
        <input type="text" name="name" /><br>
        Օբյեկտ:<br>
        <input type="checkbox" name="own" /><br>
        Պարտք:<br>
        <input type="number" step="0.001" name="debt" /><br>
        <input type="submit" value="Հաստատել" class="btn btn-default"/>
        <button id="close_new_client" class="btn btn-default">Փակել</button>
    </form>
</div>
<div class="popup" id="edit_client_popup">
    <form id="edit_client_form" action="clients/edit_client/<?php echo $clients[0]->id; ?>" method="post">
        Ընտրել կլիենտ:<br>
        <select id="client_to_edit" class="product_to_pick selectpicker" data-live-search="true"><br>
            <?php
            foreach($clients as $client)
            {
                echo '<option value="'.$client->id.'">'.$client->name.'</option>';
            }
            ?>
        </select><br>
        Անուն:<br>
        <input id="edit_client_name" type="text" name="name" value="<?php echo $clients[0]->name; ?>"/><br>
        Օբյեկտ:<br>
        <input id="edit_own" <?php if($clients[0]->own == 'yes')echo "checked"; ?> type="checkbox" name="own"/><br>
        Պարտք:<br>
        <input id="edit_debt" type="number" step="0.001" name="debt" value="<?php echo $clients[0]->debt; ?>"/><br>
        <input type="submit" class="btn btn-default" value="Հաստատել" />
        <button id="close_edit_client" class="btn btn-default">Պակել</button>
    </form>
</div>
<div class="clients">
    <button id="new_client" class="btn btn-default">Նոր կլիենտ</button>
    <button id="edit_client" class="btn btn-default">Փոփոխել</button>
    <button class="goToMenu btn btn-default">Հետ</button>
</div>

<?php $this->load->view('footer'); ?>
